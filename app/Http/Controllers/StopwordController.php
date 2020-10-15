<?php

namespace App\Http\Controllers;

use App\Stopword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class StopwordController extends Controller
{

    private $stopword_model;
    function __construct()
    {
        $this->wordword_model = new Stopword();
    }

    public function index()
    {
        $stopwords = $this->wordword_model->paginate(15);
        $data = [
            'title' => 'Stop Word',
            'stopwords' => $stopwords
        ];
        return view('stopword', $data);
    }
    public function view_save(Request $request)
    {
        $data = [
            'title' => 'Tambah Stop Word'
        ];
        if ($request->all()) {
            $rules = [
                'word' => ['required', 'filled', 'unique:stopwords,word'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($this->save($request)) {
                $message = [
                    'success' => 'Stop word berhasil ditambahkan'
                ];
                return redirect()->to(URL::to('/stopword'))->with($message);
            }
        } else {
            return view('save_stopword', $data);
        }
    }
    public function view_edit(Request $request, $id)
    {

        if ($request->all()) {
            $rules = [
                'word' => ['required', 'filled', 'unique:stopwords,word'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($this->edit($request, $id)) {
                $message = [
                    'success' => 'Stop word berhasil diubah'
                ];
                return redirect()->to(URL::to('/stopword'))->with($message);
            }
        } else {
            $stopword = $this->wordword_model->find($id);
            $data = [
                'title' => 'Ubah Stop Word',
                'stopword' => $stopword
            ];
            return view('save_stopword', $data);
        }
    }
    public function save(Request $request)
    {
        try {
            $data = [
                'word' => $request->word,
                'created_at' => new \DateTime
            ];
            $status = $this->wordword_model->insert($data);
        } catch (Exception $e) {
            echo "<script>console.log('" . $e->getMessage() . "')</script>";
            return false;
        }
        return true;
    }
    public function edit(Request $request, $id)
    {
        try {
            $data = [
                'word' => $request->word,
            ];
            $status = $this->wordword_model->where('id', $id)->update($data);
        } catch (Exception $e) {
            echo "<script>console.log('" . $e->getMessage() . "')</script>";
            return false;
        }
        return true;
    }
    public function delete($id)
    {
        try {
            $stopword = $this->wordword_model->findOrFail($id);
            if ($stopword) {
                $status = $this->wordword_model->where('id', $stopword->id)->delete();
            }
        } catch (Exception $e) {
            echo "<script>console.log('" . $e->getMessage() . "')</script>";
            $message = [
                'error' => 'Stop word ' . $id . ' tidak ditemukan'
            ];
            return redirect()->to(URL::to('/stopword'))->with($message);
        }
        $message = [
            'success' => 'Stop word ' . $stopword->word . ' berhasil dihapus'
        ];
        return redirect()->to(URL::to('/stopword'))->with($message);
    }
}
