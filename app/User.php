<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    public function tweet()
    {
        return $this->hasMany(Tweet::class, 'idUser', 'id');
    }
}
