<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweetscore extends Model
{
    protected $table = "tweetscores";
    public function tweet()
    {
        return $this->belongsTo(Tweet::class, 'idTweet', 'id');
    }
}
