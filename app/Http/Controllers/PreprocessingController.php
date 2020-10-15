<?php

namespace App\Http\Controllers;

use App\Slangword;
use App\Stopword;
use App\Tweet;
use App\TweetPreprocess;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Sastrawi\Stemmer\StemmerFactory;

class PreprocessingController extends Controller
{
    private $sastrawi;
    private $tweet_model;
    private $slangword_model;
    private $stopword_model;
    private $user_model;
    private $tweet_preprocess_model;
    function __construct()
    {
        $stemmerFactory = new StemmerFactory();
        $this->sastrawi = $stemmerFactory->createStemmer();
        $this->tweet_model = new Tweet();
        $this->slangword_model = new Slangword();
        $this->stopword_model = new Stopword();
        $this->user_model = new User();
        $this->tweet_preprocess_model = new TweetPreprocess();
    }

    public function index(Request $request)
    {
        $users = $this->user_model->all();
        $tweets = $this->tweet_model->paginate(15);
        if ($request->search) {
            return redirect(URL::to('/preprocessing/' . $users[$request->user]->id));
        }
        $data = [
            'title' => 'Tweet Preprocess',
            'users' => $users,
        ];
        if ($tweets) {
            $data['tweets'] = $tweets;
        }

        return view('list_tweet_preprocess', $data);
    }
    public function show($idUser)
    {
        $users = $this->user_model->all();
        $current_user = $users->find($idUser);
        if (!$current_user) {
            $message = [
                'error' => "User Tidak Ditemukan"
            ];
            return redirect('/preprocessing')->with($message);
        }
        $data = [
            'title' => 'Tweet Preprocess',
            'users' => $users,
            'current_user' => $current_user
        ];
        if ($current_user->tweet) {
            $data['tweets'] = $current_user->tweet()->paginate(15);
        }
        return view('list_tweet_preprocess', $data);
    }

    public function store($idUser)
    {
        $user = $this->user_model->find($idUser);
        if (!$user->tweet) {
            $message = [
                'error' => 'Tweet tidak ditemukan'
            ];
            return redirect('/preprocessing')->with($message);
        }
        $tweets = $user->tweet;
        $tweets_preprocess = $this->preprocess($tweets->toArray());
        $data = [];
        foreach ($tweets_preprocess as $index => $item) {
            $data[] = [
                'idTweet' => $item['id'],
                'textTweet' => $item['textTweet'],
                'created_at' => new \DateTime
            ];
        }
        $data = collect($data);
        $data_insert = $tweets->map(function ($item, $index) use ($data) {
            if ($item->preprocess) {
                $data_update = $data->where('idTweet', $item->id)->first();
                if ($data_update) {
                    try {
                        $status = $this->tweet_preprocess_model->where('idTweet', $item->id)->update($data_update);
                    } catch (Exception $e) {
                        echo "<script>console.log(" . $e->getMessage() . ")</script>";
                    }
                }
            } else {
                $item = $data->where('idTweet', $item->id)->first();
                return $item;
            }
        });
        $data_insert = $data_insert->filter();
        try {
            $status = $this->tweet_preprocess_model->insert($data_insert->toArray());
            if ($status) {
                $message = [
                    'success' => "Berhasil melakukan preprocessing"
                ];
                return redirect()->back()->with($message);
            }
        } catch (Exception $e) {
            echo "<script>console.log(" . $e->getMessage() . ")</script>";
            $message = [
                'error' => "Terjadi kesalahan proses"
            ];
            return redirect()->back()->with($message);
        }
    }

    public function changeLabel(Request $request)
    {
        if (!$request->id) {
            $message = [
                'error' => 'Tweet tidak ditemukan'
            ];
            return redirect('/preprocessing/')->with($message);
        }
        $tweet = $this->tweet_model->find($request->id);
        $tweet->label = $request->label;
        if ($tweet->save()) {
            $message = [
                'success' => 'Berhasil mengubah label tweet'
            ];
            return redirect()->back()->with($message);
        } else {
            $message = [
                'error' => 'Gagal mengubah label tweet'
            ];
            return redirect()->back()->with($message);
        }
    }

    public function preprocess(array $tweets)
    {
        $slangwords = $this->slangword_model->all()->mapWithKeys(function ($item, $index) {
            return [$item->slang => $item->mean];
        })->toArray();
        $stopwords = $this->stopword_model->all()->pluck('word')->toArray();
        $tweets_preprocess = [];
        foreach ($tweets as $index => $tweet) {
            $tweet['textTweet'] = strtolower($tweet['textTweet']);
            // Remove URL
            $regex = "/(?:(?:(?:(?:(?:http|https)\:\/\/)|(?:www\.))[a-zA-Z0-9]{1,}(?:[\.\/\-\_][a-zA-Z0-9]{1,}){1,})|(?:[a-zA-Z0-9]{1,}(?:\.[a-zA-Z0-9]{1,}){1,}(?:[\.\/\-\_][a-zA-Z0-9]{1,}){0,}))/";
            $tweet['textTweet'] = preg_replace($regex, " ", $tweet['textTweet']);
            // Remove except alphabet
            $regex = "/[^a-z]+/";
            $tweet['textTweet'] = preg_replace($regex, " ", $tweet['textTweet']);
            // Convert SlangWords
            $tweet_string = $tweet['textTweet'];
            $tweet_string = explode(' ', $tweet_string);
            $tweet_slang = str_replace(array_keys($slangwords), $slangwords, $tweet_string);
            $tweet['textTweet'] = implode(" ", $tweet_slang);

            // Remove Stopwords
            $tweet_stopwords = explode(" ", $tweet['textTweet']);
            foreach ($tweet_stopwords as $index => $item) {
                if (in_array($item, $stopwords)) {
                    unset($tweet_stopwords[$index]);
                }
            }
            $tweet['textTweet'] = implode(" ", $tweet_stopwords);

            // Stemming
            $tweet_stem = $this->sastrawi->stem($tweet['textTweet']);
            $tweet['textTweet'] = $tweet_stem;

            // Tokenisasi
            $tweet_tokenization = explode(' ', $tweet['textTweet']);
            $tweet_tokenization = array_unique($tweet_tokenization);

            $data_bersih[] = $tweet_tokenization;
            $imploding = implode(" ", $tweet_tokenization);
            $tweet['textTweet'] = $imploding;
            $tweets_preprocess[] = $tweet;
        }
        return $tweets_preprocess;
    }
}
