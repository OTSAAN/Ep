<?php

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class MessagesController extends BaseController
{
    /**
     * Just for testing - the user should be logged in. In a real
     * app, please use standard authentication practices
     */
    public function __construct()
    {
        $user = Auth::user();
    }

    /**
     * Show all of the message threads to the user
     *
     * @return mixed
     */
    public function index()
    {
        $currentUserId = Auth::user()->id;

        // All threads, ignore deleted/archived participants
        $threads = Thread::getAllLatest();
        $threads = new Thread;
        $threads = $threads->scopeForUser($threads,$currentUserId);

        // All threads that user is participating in
        // $threads = Thread::forUser($currentUserId);

        // All threads that user is participating in, with new messages
       //  $threads = Thread::forUserWithNewMessages($currentUserId);

        return View::make('messenger.index', compact('threads', 'currentUserId'));
    }

    /**
     * Shows a message thread
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return Redirect::to('messages');
        }

        // don't show the current user in list
        $userId = Auth::user()->id;

        //redirect back if the user is not a participante
        if(!in_array($userId,$thread->participantsUserIds())){
            return Redirect::to('/messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();


        $participants =User::whereIn('id', $thread->participantsUserIds($userId))->get();
        $thread->markAsRead($userId);
        $threadsNotifications=   \Cmgmyr\Messenger\Models\Thread::forUserWithNewMessages(Auth::user()->id);

        return View::make('messenger.show', compact('thread','participants','threadsNotifications'));
    }

    /**
     * Creates a new message thread
     *
     * @return mixed
     */
    public function create()
    {
        $datas=Auth::user()->channels()->with('users')->get();
        $usersId=array();
        foreach($datas as $data){
            foreach($data->users as $user)
            {
                $usersId[]=$user->id;
            }
        }
        $usersId= array_unique($usersId);
        $users = User::where('id', '!=', Auth::id())->where('is_type','!=','professor')->whereIn('id',$usersId)->get();
        $professors = User::where('id', '!=', Auth::id())->where('is_type','=','professor')->whereIn('id',$usersId)->get();

        return View::make('messenger.create', compact('users','professors'));
    }

    /**
     * Stores a new message thread
     *
     * @return mixed
     */
    public function store()
    {
        $input = Input::all();

        $validator = Validator::make(Input::all(),['message'=>'required','subject'=>'required','recipients'=>'required']);

        if($validator->fails())
        {
            return Redirect::back();
        }


        $thread = Thread::create(
            [
                'subject' => $input['subject'],
            ]
        );

        // Message
        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'body'      => $input['message'],
            ]
        );

        // Sender
        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id,
                'last_read' => new Carbon
            ]
        );

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants($input['recipients']);
        }

        return Redirect::to('messages');
    }

    /**
     * Adds a new message to a current thread
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return Redirect::to('messages');
        }

        $thread->activateAllParticipants();

        //validation
        $validator = Validator::make(Input::all(),['message'=>'required']);

        if($validator->fails())
        {
            return Redirect::back();
        }

        Message::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::id(),
                'body'      => Input::get('message'),
            ]
        );

        // Add replier as a participant
        $participant = Participant::firstOrCreate(
            [
                'thread_id' => $thread->id,
                'user_id'   => Auth::user()->id
            ]
        );
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants(Input::get('recipients'));
        }

        return Redirect::to('messages/' . $id);
    }
}
