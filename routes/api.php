<?php

use App\Http\Controllers\Api\Login;
use App\Http\Controllers\Api\Logout;
use App\Http\Controllers\Api\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', Register::class)->name('register');
Route::post('/login', Login::class)->name('login');
Route::post('/logout', Logout::class)->name('logout');
Route::middleware('auth:api')->get('/user', fn(Request $request): User => $request->user());
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());