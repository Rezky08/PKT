<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
    public function score()
    {
        return $this->hasOne(Tweetscore::class, 'idTweet', 'id');
    }
    public function preprocess()
    {
        return $this->hasOne(TweetPreprocess::class, 'idTweet', 'id');
    }
}
