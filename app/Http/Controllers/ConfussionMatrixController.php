<?php

namespace App\Http\Controllers;

use App\Tweet;
use App\User;
use Exception;
use Illuminate\Http\Request;

class ConfussionMatrixController extends Controller
{
    private $user_model;
    private $tweet_model;
    function __construct()
    {
        $this->user_model = new User();
        $this->tweet_model = new Tweet();
    }
    public function index(Request $request)
    {
        if ($request->_token && $request->user) {
            return redirect('/confmatrix/' . $request->user);
        }
        $users = $this->user_model->all();
        $tweets = $this->tweet_model->all();
        $data = [
            'title' => "Confussion Matrix",
            'users' => $users,
            'tweets' => $tweets
        ];
        if ($tweets) {
            $confclass = $this->confussion_matrix($tweets->whereNotNull('label'));
            $data['confmatrix'] = $confclass;
        }
        return view('confmatrix', $data);
    }
    public function show($idUser)
    {
        $users = $this->user_model->all();
        $current_user = $users->find($idUser);
        if (!$current_user) {
            $message = [
                'error' => 'User Tidak Ditemukan'
            ];
            return redirect('/confmatrix')->with($message);
        }
        $tweets = $current_user->tweet->whereNotNull('label');
        $confclass = $this->confussion_matrix($tweets);

        $data = [
            'title' => 'Confussion Matrix ' . $current_user->username,
            'users' => $users,
            'current_user' => $current_user,
            'tweets' => $current_user->tweet,
            'confmatrix' => $confclass
        ];
        return view('confmatrix', $data);
    }

    public function confussion_matrix($tweets)
    {

        // get confussion matrix
        $confmatrix = [
            'TP'    => 0,
            'FP'    => 0,
            'FN'    => 0,
            'TN'    => 0,
            'acc'   => 0,
            'pre'  => 0,
            'rec'   => 0
        ];
        $confclass = [
            'pos' => $confmatrix,
            'neu' => $confmatrix,
            'neg' => $confmatrix,
        ];
        if ($tweets->isEmpty()) {
            return $confclass;
        }

        foreach ($tweets as $index => $tweet) {
            if ($tweet->score && $tweet->label) {
                if ($tweet->label == $tweet->score->label) {
                    $confclass[$tweet->label]['TP']++;
                } else {
                    $confclass[$tweet->label]['FN']++;
                    $confclass[$tweet->score->label]['FP']++;
                }
            }
        }
        foreach ($confclass as $key => $value) {
            $total_wo_tn = $confclass[$key]['TP'] + $confclass[$key]['FP'] + $confclass[$key]['FN'];
            $confclass[$key]['TN'] = $tweets->count() - $total_wo_tn;
            $confclass[$key]['acc'] = ($confclass[$key]['TP'] + $confclass[$key]['TN']) / $tweets->count();
            $confclass[$key]['pre'] = $confclass[$key]['TP'] / ($confclass[$key]['TP'] + $confclass[$key]['FP']);
            $confclass[$key]['rec'] = $confclass[$key]['TP'] / ($confclass[$key]['TP'] + $confclass[$key]['FN']);
        }
        return $confclass;
    }
}
