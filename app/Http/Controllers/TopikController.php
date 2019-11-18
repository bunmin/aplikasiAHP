<?php

namespace App\Http\Controllers;

use App\alternatif;
use App\kriteria;
use App\topik;
use Illuminate\Http\Request;
use Auth;

class TopikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $topik = topik::where('user_id',auth::user()->id)->get();

        return view('topik.index', [
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
            'keterangan' => $request->keterangan,
        ];
        topik::create($data);

        if ($error > 0) {
            $message = ['error' => 'Gagal simpan'];
        }else{
            $message = ['message' => 'Berhasil simpan'];
        }

        return redirect('topik')->with($message);
    }

    public function detail($topikId)
    {
        $user = Auth::user();
        $topik = topik::where('id',$topikId)->first();
        $alterntifs = alternatif::where('topik_id',$topikId)->get();
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        return view('topik.detail', [
            'user' => $user,
            'class' => "topik",
            'title' => "Detail Topik",
            'topik' => $topik,
            'alterntifs' => $alterntifs,
            'kriterias' => $kriterias,
        ]);
    }

    public function storeKriteria(Request $request, $topikId)
    {
        $error = 0;

        $data = [
            'user_id' => auth::user()->id,
            'topik_id' => $topikId,
            'nama' => $request->kriteria,
        ];
        kriteria::create($data);

        if ($error > 0) {
            $message = ['error' => 'Gagal simpan'];
        }else{
            $message = ['message' => 'Berhasil simpan'];
        }

        $kriterias = kriteria::where('topik_id',$topikId)->get();

        // return redirect('/topik/detail/'.$topikId)->refresh()->with($message);
        return response()->json($kriterias, 200);
    }

    public function getKriteria(Request $request)
    {
        $kriteria = kriteria::where('id',$request->id)->first();

        return response()->json($kriteria, 200);
    }

    public function updateKriteria(Request $request, $topikId)
    {
        $data = [
            'nama' => $request->kriteria,
        ];
        kriteria::where('id',$request->id)->update($data);
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        return response()->json($kriterias, 200);
    }

    public function deleteKriteria(Request $request, $topikId)
    {
        kriteria::where('id',$request->id)->delete();
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        return response()->json($kriterias, 200);
    }


    public function storeAlternatif(Request $request, $topikId)
    {
        $error = 0;

        $data = [
            'user_id' => auth::user()->id,
            'topik_id' => $topikId,
            'nama' => $request->alternatif,
        ];
        alternatif::create($data);

        if ($error > 0) {
            $message = ['error' => 'Gagal simpan'];
        }else{
            $message = ['message' => 'Berhasil simpan'];
        }

        $alternatifs = alternatif::where('topik_id',$topikId)->get();

        return response()->json($alternatifs, 200);
    }

    public function getAlternatif(Request $request)
    {
        $alternatif = alternatif::where('id',$request->id)->first();

        return response()->json($alternatif, 200);
    }

    public function updateAlternatif(Request $request, $topikId)
    {
        $data = [
            'nama' => $request->alternatif,
        ];
        alternatif::where('id',$request->id)->update($data);
        $alternatifs = alternatif::where('topik_id',$topikId)->get();

        return response()->json($alternatifs, 200);
    }

    public function deleteAlternatif(Request $request, $topikId)
    {
        alternatif::where('id',$request->id)->delete();
        $alternatifs = alternatif::where('topik_id',$topikId)->get();

        return response()->json($alternatifs, 200);
    }
}
