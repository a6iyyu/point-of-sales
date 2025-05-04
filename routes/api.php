<?php

use App\Http\Controllers\Api\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', Register::class)->name('register');
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());