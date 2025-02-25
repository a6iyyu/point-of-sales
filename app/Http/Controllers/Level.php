<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Level extends Controller
{
    public function index()
    {
        return view('level', ['data' => DB::select('SELECT * FROM m_level')]);
    }
}