<?php

use App\Http\Controllers\Kategori;
use App\Http\Controllers\Level;
use App\Http\Controllers\Supplier;
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

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [Kategori::class, 'index']);          // menampilkan halaman awal kategori 
    Route::post('/list', [Kategori::class, 'list']);      // menampilkan data kategori dalam bentuk json untuk datatables 
    Route::get('/create', [Kategori::class, 'create']);   // menampilkan halaman form tambah kategori 
    Route::post('/', [Kategori::class, 'store']);         // menyimpan data kategori baru 
    Route::get('/{id}', [Kategori::class, 'show']);       // menampilkan detail kategori 
    Route::get('/{id}/edit', [Kategori::class, 'edit']);  // menampilkan halaman form edit kategori 
    Route::put('/{id}', [Kategori::class, 'update']);     // menyimpan perubahan data kategori 
    Route::delete('/{id}', [Kategori::class, 'destroy']); // menghapus data kategori
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [Supplier::class, 'index']);          // menampilkan halaman awal supplier 
    Route::post('/list', [Supplier::class, 'list']);      // menampilkan data supplier dalam bentuk json untuk datatables 
    Route::get('/create', [Supplier::class, 'create']);   // menampilkan halaman form tambah supplier 
    Route::post('/', [Supplier::class, 'store']);         // menyimpan data supplier baru 
    Route::get('/{id}', [Supplier::class, 'show']);       // menampilkan detail supplier 
    Route::get('/{id}/edit', [Supplier::class, 'edit']);  // menampilkan halaman form edit supplier 
    Route::put('/{id}', [Supplier::class, 'update']);     // menyimpan perubahan data supplier 
    Route::delete('/{id}', [Supplier::class, 'destroy']); // menghapus data supplier
});