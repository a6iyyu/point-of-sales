<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use Illuminate\View\View;

class User extends Controller
{
    public function index(): View
    {
        return view('pengguna', ['data' => UserModel::findOr(20, ['username', 'nama'], fn() => abort(404))]);
    }
}