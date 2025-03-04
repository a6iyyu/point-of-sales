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

Route::prefix('pengguna')->group(function () {
    Route::get('/', [User::class, 'index']);
    Route::get('/tambah', [User::class, 'add']);
    Route::get('/edit/{id}', [User::class, 'edit']);
    Route::get('/hapus/{id}', [User::class, 'delete'])->name('hapus-pengguna');
    Route::post('/simpan', [User::class, 'save'])->name('simpan-pengguna');
    Route::put('/edit/{id}', [User::class, 'put'])->name('edit-pengguna');
});