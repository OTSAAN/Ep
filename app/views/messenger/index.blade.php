@extends('master')

@section('feed')

<h3>Last Active threads <a href="/messages/create" class=" class="btn btn-default">New thread</a></h3>
@if (Session::has('error_message'))
<div class="alert alert-danger" role="alert">
    {{Session::get('error_message')}}
</div>
@endif
@if($threads->count() > 0)
@foreach($threads as $thread)
<?php $class = $thread->isUnread($currentUserId) ? 'alert-info' : ''; ?>
<div class="panel panel-default {{$class}}">
    <div class="panel-heading">{{link_to('messages/' . $thread->id, $thread->subject)}}</div>
    <div class="panel-body">
        <label>Last msg :</label>{{$thread->latestMessage()->body}}
    </div>
</div>


@endforeach


@else
<p>Sorry, no threads.</p>
@endif
{{ $threads->links()  }}
@stop




