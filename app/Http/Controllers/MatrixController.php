<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\alternatif;
use App\kriteria;
use App\topik;
use Auth;


class MatrixController extends Controller
{
    public function index($topikId)
    {
        $user = Auth::user();
        $topik = topik::where('id',$topikId)->first();
        $alterntifs = alternatif::where('topik_id',$topikId)->get();
        $kriterias = kriteria::where('topik_id',$topikId)->get();

        return view('matrix.index', [
            'user' => $user,
            'class' => "topik",
            'title' => "Matrix Perbandingan",
            'topik' => $topik,
            'alterntifs' => $alterntifs,
            'kriterias' => $kriterias,
        ]);
    }
}
