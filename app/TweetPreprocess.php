<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TweetPreprocess extends Model
{
    protected $table = "tweetpreprocess";
    public function tweet()
    {
        return $this->belongsTo(Tweet::class, 'idTweet', 'id');
    }
}
