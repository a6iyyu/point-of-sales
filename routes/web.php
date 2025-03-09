<?php

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