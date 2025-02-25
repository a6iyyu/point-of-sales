<?php

use App\Http\Controllers\Kategori;
use App\Http\Controllers\Level;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/level', [Level::class, 'index']);
Route::get('/kategori', [Kategori::class, 'index']);
Route::get('/pengguna', [User::class, 'index']);