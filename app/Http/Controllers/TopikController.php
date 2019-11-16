<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopikController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        return view('topik', [
            'class' => "topik",
            'title' => "Topik",
        ]);
    }
}
