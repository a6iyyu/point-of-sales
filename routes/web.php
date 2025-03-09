<?php

use App\Http\Controllers\Level;
use App\Http\Controllers\User;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', [Welcome::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [User::class, 'index']);          // menampilkan halaman awal user 
    Route::post('/list', [User::class, 'list']);      // menampilkan data user dalam bentuk json untuk datatables 
    Route::get('/create', [User::class, 'create']);   // menampilkan halaman form tambah user 
    Route::post('/', [User::class, 'store']);         // menyimpan data user baru 
    Route::get('/{id}', [User::class, 'show']);       // menampilkan detail user 
    Route::get('/{id}/edit', [User::class, 'edit']);  // menampilkan halaman form edit user 
    Route::put('/{id}', [User::class, 'update']);     // menyimpan perubahan data user 
    Route::delete('/{id}', [User::class, 'destroy']); // menghapus data user
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [Level::class, 'index']);          // menampilkan halaman awal level 
    Route::post('/list', [Level::class, 'list']);      // menampilkan data level dalam bentuk json untuk datatables 
    Route::get('/create', [Level::class, 'create']);   // menampilkan halaman form tambah level 
    Route::post('/', [Level::class, 'store']);         // menyimpan data level baru 
    Route::get('/{id}', [Level::class, 'show']);       // menampilkan detail level 
    Route::get('/{id}/edit', [Level::class, 'edit']);  // menampilkan halaman form edit level 
    Route::put('/{id}', [Level::class, 'update']);     // menyimpan perubahan data level 
    Route::delete('/{id}', [Level::class, 'destroy']); // menghapus data level
});