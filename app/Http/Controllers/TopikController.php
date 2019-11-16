<?php

namespace App\Http\Controllers;

use App\topik;
use Illuminate\Http\Request;
use Auth;

class TopikController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $topik = topik::where('user_id',auth::user()->id)->get();

        return view('topik', [
            'user' => $user,
            'class' => "topik",
            'title' => "Topik",
            'topiks' => $topik,
        ]);
    }

    public function storeTopik(Request $request)
    {
        // dd($request->all());
        $error = 0;

        $data = [
            'user_id' => auth::user()->id,
            'judul' => $request->judultopik,
            'keterangan' => $request->judultopik,
        ];
        topik::create($data);

        if ($error > 0) {
            $message = ['error' => 'Gagal simpan'];
        }else{
            $message = ['message' => 'Berhasil simpan'];
        }

        return redirect('topik')->with($message);
    }
}
