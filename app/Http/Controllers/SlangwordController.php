<?php

namespace App\Http\Controllers;

use App\Slangword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class SlangwordController extends Controller
{

    private $slangword_model;
    function __construct()
    {
        $this->slangword_model = new Slangword();
    }

    public function index()
    {
        $slangwords = $this->slangword_model->paginate(15);
        $data = [
            'title' => 'Slang Word',
            'slangwords' => $slangwords
        ];
        return view('slangword', $data);
    }
    public function view_save(Request $request)
    {
        $data = [
            'title' => 'Tambah Slang Word'
        ];
        if ($request->all()) {
            $rules = [
                'slang' => ['required', 'filled', 'unique:slangwords,slang'],
                'mean' => ['required', 'filled']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($this->save($request)) {
                $message = [
                    'success' => 'Slang word berhasil ditambahkan'
                ];
                return redirect()->to(URL::to('/slangword'))->with($message);
            }
        } else {
            return view('save_slangword', $data);
        }
    }
    public function view_edit(Request $request, $id)
    {

        if ($request->all()) {
            $rules = [
                'slang' => ['required', 'filled', 'unique:slangwords,slang'],
                'mean' => ['required', 'filled']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($this->edit($request, $id)) {
                $message = [
                    'success' => 'Slang word berhasil diubah'
                ];
                return redirect()->to(URL::to('/slangword'))->with($message);
            }
        } else {
            $slangword = $this->slangword_model->find($id);
            $data = [
                'title' => 'Ubah Slang Word',
                'slangword' => $slangword
            ];
            return view('save_slangword', $data);
        }
    }
    public function save(Request $request)
    {
        try {
            $data = [
                'slang' => $request->slang,
                'mean' => $request->mean,
                'created_at' => new \DateTime
            ];
            $status = $this->slangword_model->insert($data);
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
                'slang' => $request->slang,
                'mean' => $request->mean,
            ];
            $status = $this->slangword_model->where('id', $id)->update($data);
        } catch (Exception $e) {
            echo "<script>console.log('" . $e->getMessage() . "')</script>";
            return false;
        }
        return true;
    }
    public function delete($id)
    {
        try {
            $slangword = $this->slangword_model->findOrFail($id);
            if ($slangword) {
                $status = $this->slangword_model->where('id', $slangword->id)->delete();
            }
        } catch (Exception $e) {
            echo "<script>console.log('" . $e->getMessage() . "')</script>";
            $message = [
                'error' => 'Slang word ' . $id . ' tidak ditemukan'
            ];
            return redirect()->to(URL::to('/slangword'))->with($message);
        }
        $message = [
            'success' => 'Slang word ' . $slangword->slang . ' berhasil dihapus'
        ];
        return redirect()->to(URL::to('/slangword'))->with($message);
    }
}
