<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Welcome extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome'],
        ];

        return view('welcome', ['breadcrumb' => $breadcrumb, 'active_menu' => 'dashboard']);
    }
}