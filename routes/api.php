<?php

use App\Http\Controllers\Api\Kategori;
use App\Http\Controllers\Api\Level;
use App\Http\Controllers\Api\Login;
use App\Http\Controllers\Api\Logout;
use App\Http\Controllers\Api\Register;
use App\Http\Controllers\Api\User as ApiUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', Register::class)->name('register');
Route::post('/login', Login::class)->name('login');
Route::post('/logout', Logout::class)->name('logout');
Route::middleware('auth:api')->get('/user', fn(Request $request): User => $request->user());

Route::middleware('auth:api')->group(function () {
    Route::get('/level', [Level::class, 'index']);
    Route::get('/level/{level}', [Level::class, 'show']);
    Route::post('/level', [Level::class, 'store']);
    Route::put('/level/{level}', [Level::class, 'update']);
    Route::delete('/level/{level}', [Level::class, 'destroy']);

    Route::get('/user', [ApiUser::class, 'index']);
    Route::get('/user/{user}', [ApiUser::class, 'show']);
    Route::post('/user', [ApiUser::class, 'store']);
    Route::put('/user/{user}', [ApiUser::class, 'update']);
    Route::delete('/user/{user}', [ApiUser::class, 'destroy']);

    Route::get('/kategori', [Kategori::class, 'index']);
    Route::get('/kategori/{kategori}', [Kategori::class, 'show']);
    Route::post('/kategori', [Kategori::class, 'store']);
    Route::put('/kategori/{kategori}', [Kategori::class, 'update']);
    Route::delete('/kategori/{kategori}', [Kategori::class, 'destroy']);
});