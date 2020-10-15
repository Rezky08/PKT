<?php

namespace App\Http\Controllers;

use App\Tweetscore;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPInsight\Sentiment;

class TweetController extends Controller
{
    private $user_model;
    private $tweet_score_model;
    function __construct()
    {
        $this->user_model = new User();
        $this->tweet_score_model = new Tweetscore();
    }

    public function index(Request $request)
    {
        $users = $this->user_model->all();
        $data = [
            'title' => 'Tweet Yang Disimpan',
            'users' => $users
        ];
        if ($request->search && $request->_token) {
            return redirect('/tweet/' . $users[$request->user]->id);
        }
        return view('list_tweet_db', $data);
    }

    public function show($idUser)
    {
        $users = $this->user_model->all();
        $current_user = $users->find($idUser);
        if (!$current_user) {
            $message = [
                'error' => 'User Tidak Ditemukan'
            ];
            return redirect('/tweet')->with($message);
        }
        $data = [
            'title' => 'Tweet Yang Disimpan',
            'users' => $users,
            'current_user' => $current_user
        ];
        if ($current_user->tweet) {
            $data['tweets'] = $current_user->tweet()->paginate(15);
        }
        return view('list_tweet_db', $data);
    }

    public function detail($idUser, $idTweet)
    {
        $user = $this->user_model->find($idUser);
        $tweet = $user->tweet->where('id', $idTweet)->first();
        if (!$tweet->score) {
            $message = [
                'error' => 'Tweet belum memiliki arah sentiment'
            ];
            return redirect('/tweet')->with($message);
        }
        $data = [
            'user' => $user,
            'tweet' => $tweet,
            'title' => 'Detail Score Tweet ' . $tweet->id
        ];
        return view('detail_tweet_db', $data);
    }

    public function getSentiment($id)
    {
        $user = $this->user_model->find($id);
        $tweets = $user->tweet;
        $sentiment = new Sentiment();
        $scores = [];
        $scores_update = [];

        foreach ($tweets as $index => $item) {
            $score = $sentiment->score($item->textTweet);

            $class = $sentiment->categorise($item->textTweet);
            $scores[] = [
                'idTweet' => $item->id,
                'positif' => $score['pos'],
                'negatif' => $score['neg'],
                'netral' => $score['neu'],
                'label' => $class,
                'created_at' => new \DateTime
            ];
            $rules = [
                'idTweet' => ['unique:tweetscores,idTweet']
            ];
            $validator = Validator::make($scores[$index], $rules);
            if ($validator->fails()) {
                $scores_update[] = $scores[$index];
                unset($scores[$index]);
            }
        }
        try {
            foreach ($scores_update as $index => $item) {
                $status = $this->tweet_score_model->where('idTweet', $item['idTweet'])->update($item);
            }
            $this->tweet_score_model->insert($scores);
            $message = [
                'success' => 'Berhasil mendapatkan arah sentiment dari akun ' . $user->username
            ];
            return redirect()->back()->with($message);
        } catch (Exception $e) {
            echo "<script>console.log(" . $e->getMessage() . ")</script>";
            $message = [
                'error' => 'Gagal mendapatkan arah sentiment'
            ];
            return redirect()->back()->with($message);
        }
    }
}
