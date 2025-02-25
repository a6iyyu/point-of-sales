<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Kategori extends Controller
{
    public function index()
    {
        return view('kategori', ['data' => DB::table('m_kategori')->get()]);
    }
}