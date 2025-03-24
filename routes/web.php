<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Barang;
use App\Http\Controllers\Kategori;
use App\Http\Controllers\Level;
use App\Http\Controllers\Supplier;
use App\Http\Controllers\User;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');

Route::get('login', [Auth::class, 'login'])->name('login');
Route::get('logout', [Auth::class, 'logout'])->middleware('auth');
Route::post('login', [Auth::class, 'postlogin']);

Route::middleware('auth')->group(function () {
    Route::get('/', [Welcome::class, 'index']);

    Route::prefix('user')->group(function () {
        Route::middleware(['authorize:ADM'])->group(function() {
            Route::get('/', [User::class, 'index']);
            Route::post('/list', [User::class, 'list']);
            Route::get('/create', [User::class, 'create']);
            Route::post('/', [User::class, 'store']);
            Route::get('/create-ajax', [User::class, 'create_ajax']);
            Route::post('/ajax', [User::class, 'store_ajax']);
            Route::get('/{id}', [User::class, 'show']);
            Route::get('/{id}/edit', [User::class, 'edit']);
            Route::put('/{id}', [User::class, 'update']);
            Route::get('/{id}/edit-ajax', [User::class, 'edit_ajax']);
            Route::put('/{id}/update-ajax', [User::class, 'update_ajax']);
            Route::get('/{id}/delete-ajax', [User::class, 'confirm_ajax']);
            Route::delete('/{id}/delete-ajax', [User::class, 'delete_ajax']);
            Route::delete('/{id}', [User::class, 'destroy']);
        });
    });

    Route::prefix('level')->group(function () {
        Route::middleware(['authorize:ADM'])->group(function() {
            Route::get('/', [Level::class, 'index']);
            Route::post('/list', [Level::class, 'list']);
            Route::get('/create', [Level::class, 'create']);
            Route::post('/', [Level::class, 'store']);
            Route::get('/create-ajax', [Level::class, 'create_ajax']);
            Route::post('/ajax', [Level::class, 'store_ajax']);
            Route::get('/{id}', [Level::class, 'show']);
            Route::get('/{id}/edit', [Level::class, 'edit']);
            Route::put('/{id}', [Level::class, 'update']);
            Route::get('/{id}/edit-ajax', [Level::class, 'edit_ajax']);
            Route::put('/{id}/update-ajax', [Level::class, 'update_ajax']);
            Route::get('/{id}/delete-ajax', [Level::class, 'confirm_ajax']);
            Route::delete('/{id}/delete-ajax', [Level::class, 'delete_ajax']);
            Route::delete('/{id}', [Level::class, 'destroy']);
        });
    });

    Route::prefix('kategori')->group(function () {
        Route::middleware(['authorize:ADM,MNG'])->group(function() {
            Route::get('/', [Kategori::class, 'index']);
            Route::post('/list', [Kategori::class, 'list']);
            Route::get('/create', [Kategori::class, 'create']);
            Route::post('/', [Kategori::class, 'store']);
            Route::get('/create-ajax', [Kategori::class, 'create_ajax']);
            Route::post('/ajax', [Kategori::class, 'store_ajax']);
            Route::get('/{id}', [Kategori::class, 'show']);
            Route::get('/{id}/edit', [Kategori::class, 'edit']);
            Route::put('/{id}', [Kategori::class, 'update']);
            Route::get('/{id}/edit-ajax', [Kategori::class, 'edit_ajax']);
            Route::put('/{id}/update-ajax', [Kategori::class, 'update_ajax']);
            Route::get('/{id}/delete-ajax', [Kategori::class, 'confirm_ajax']);
            Route::delete('/{id}/delete-ajax', [Kategori::class, 'delete_ajax']);
            Route::delete('/{id}', [Kategori::class, 'destroy']);
        });
    });

    Route::prefix('supplier')->group(function () {
        Route::middleware(['authorize:ADM,MNG'])->group(function() {
            Route::get('/', [Supplier::class, 'index']);
            Route::post('/list', [Supplier::class, 'list']);
            Route::get('/create', [Supplier::class, 'create']);
            Route::post('/', [Supplier::class, 'store']);
            Route::get('/create-ajax', [Supplier::class, 'create_ajax']);
            Route::post('/ajax', [Supplier::class, 'store_ajax']);
            Route::get('/{id}', [Supplier::class, 'show']);
            Route::get('/{id}/edit', [Supplier::class, 'edit']);
            Route::put('/{id}', [Supplier::class, 'update']);
            Route::get('/{id}/edit-ajax', [Supplier::class, 'edit_ajax']);
            Route::put('/{id}/update-ajax', [Supplier::class, 'update_ajax']);
            Route::get('/{id}/delete-ajax', [Supplier::class, 'confirm_ajax']);
            Route::delete('/{id}/delete-ajax', [Supplier::class, 'delete_ajax']);
            Route::delete('/{id}', [Supplier::class, 'destroy']);
        });
    });

    Route::prefix('barang')->group(function () {
        Route::middleware(['authorize:ADM,MNG,STF'])->group(function() {
            Route::get('/', [Barang::class, 'index']);
            Route::post('/list', [Barang::class, 'list']);
            Route::get('/create', [Barang::class, 'create']);
            Route::post('/', [Barang::class, 'store']);
            Route::get('/create-ajax', [Barang::class, 'create_ajax']);
            Route::post('/ajax', [Barang::class, 'store_ajax']);
            Route::get('/{id}', [Barang::class, 'show']);
            Route::get('/{id}/edit', [Barang::class, 'edit']);
            Route::put('/{id}', [Barang::class, 'update']);
            Route::get('/{id}/edit-ajax', [Barang::class, 'edit_ajax']);
            Route::put('/{id}/update-ajax', [Barang::class, 'update_ajax']);
            Route::get('/{id}/delete-ajax', [Barang::class, 'confirm_ajax']);
            Route::delete('/{id}/delete-ajax', [Barang::class, 'delete_ajax']);
            Route::delete('/{id}', [Barang::class, 'destroy']);
        });
    });
});