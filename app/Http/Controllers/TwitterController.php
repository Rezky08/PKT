<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Tweet;
use App\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\VarDumper\VarDumper;

class TwitterController extends Controller
{

    private $twitter_consumser_key = "DzhALYes7Jk2WA5ZeqFwIZIfN";
    private $twitter_consumser_key_secret = "vZJshZCHxaJKChpkrgT2RliPUz3ckeHaK9sYiGVAbDFbG72A37";
    private $twitter_access_token = "834235722-z2RDctM2iwvBSUm9ALtjxU7bcRTclVlE8vnKEIkx";
    private $twitter_access_token_secret = "jSZiuAKJ24GlLHowLUAe0oInjM7RlrhJHQOONCgfQnuil";
    private $twit_conn;
    private $user_model;
    private $tweet_model;

    function __construct()
    {
        $this->twit_conn = new TwitterOAuth($this->twitter_consumser_key, $this->twitter_consumser_key_secret, $this->twitter_access_token, $this->twitter_access_token_secret);
        $this->user_model = new User();
        $this->tweet_model = new Tweet();
    }

    public function index(Request $request)
    {
        if ($request->username) {
            $resp =  $this->getTweet($request->username);
            $tweets = $resp['tweets'];
            $profile = $resp['profile'];
            $data = [
                'title' => 'Tweet dari akun ' . $request->username,
                'profile' => $profile
            ];
            if (!isset($tweets->errors)) {
                $data['tweets'] = $tweets;
            }
            return view('list_tweet', $data);
        } else {
            $data = ['title' => 'Get User Timeline Tweets'];
            return view('get_tweet', $data);
        }
    }

    public function save_tweets(Request $request)
    {
        $rules = [
            'idUser' => ['required', 'filled'],
            'username' => ['required', 'filled'],
            'name' => ['required', 'filled'],
            'idTweet' => ['required', 'filled'],
            'textTweet' => ['required', 'filled']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $insertToDB = $this->storeTweet($request);
        if ($insertToDB) {
            $message = [
                'success' => 'Tweet Berhasil disimpan'
            ];
            return redirect()->back()->with($message);
        } else {
            $errors = [
                'error' => 'Terdapat Kesalahan Penyimpanan'
            ];
            return redirect()->back()->withErrors($errors);
        }
    }

    public function storeTweet(Request $request)
    {
        $user = [
            'id' => $request->idUser,
            'username' => $request->username,
            'name' => $request->name,
            'created_at' => new \DateTime
        ];
        $tweets = [];
        $userSearch = $this->user_model->find($user['id']);
        if ($userSearch) {
            $userId = $userSearch->id;
        } else {
            try {
                $userId = $this->user_model->insertGetId($user);
            } catch (Exception $e) {
                echo "<script>console.log('" . $e->getMessage() . "')</script>";
                return false;
            }
        }
        foreach ($request->idTweet as $index => $idTweet) {
            $tweets[] = [
                'idUser' => $userId,
                'id' => $idTweet,
                'textTweet' => $request->textTweet[$index],
                'created_at' => new \DateTime
            ];
        }
        try {
            $status = $this->tweet_model->insert($tweets);
        } catch (Exception $e) {
            dd($e->getMessage());
            echo "<script>console.log(" . $e->getMessage() . ")</script>";
            return false;
        }
        return true;
    }


    public function getTweet($username = "")
    {

        $params  = [
            'count' => 200,
            'screen_name' => $username,
            'tweet_mode' => 'extended',
        ];

        $user = $this->user_model->where('username', $username)->first();
        if ($user) {
            $tweet = $this->tweet_model->where('idUser', $user->id)->orderBy('id', 'asc')->first();
            if ($tweet) {
                $params['max_id'] = $tweet->id;
            }
        }

        $tweets = $this->twit_conn->get('statuses/user_timeline', $params);

        if (isset($tweet)) {
            $tweets = collect($tweets)->filter(function ($item, $index) use ($tweet) {
                if ($item->id_str != $tweet->id) {
                    return $item;
                }
            })->values()->toArray();
        }

        $params  = [
            'screen_name' => $username,
        ];
        $profile = $this->twit_conn->get('users/show', $params);

        $resp = [
            'tweets' => $tweets,
            'profile' => $profile
        ];
        return $resp;
    }
}
