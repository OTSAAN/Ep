<?php

class Channel extends Eloquent {

    protected $guarded = array('id');
    protected $hidded = array('created_at','updated_at');

    public function user()
    {
        return $this->belongsTo('User');
    }
    public function users()
    {
        return $this->belongsToMany('User','user_channel');
    }

    public function posts()
    {
        return $this->hasMany('Post');
    }

    public function tasks()
    {
        return $this->hasMany('Task');
    }

    public function comments()
    {
        return $this->hasManyThrough('Comment','Post');
    }

} 
