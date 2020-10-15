<?php

namespace App\Http\Controllers;

use App\Slangword;
use App\Stopword;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $slangword_model;
    private $stopword_model;
    private $user_model;
    private $tweet_model;
    function __construct()
    {
        $this->slangword_model = new Slangword();
        $this->stopword_model = new Stopword();
        $this->user_model = new User();
        $this->tweet_model = new Tweet();
    }

    public function index()
    {
        $count = [
            'slangword_count' => $this->slangword_model->all()->count(),
            'stopword_count' => $this->stopword_model->all()->count(),
            'user_count' => $this->user_model->all()->count(),
            'tweet_count' => $this->tweet_model->all()->count(),

        ];
        $data = [
            'counts' => $count,
            'title' => 'Dashboard'
        ];
        return view('dashboard', $data);
    }
}
