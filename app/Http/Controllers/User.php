<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

class User extends Controller
{
    public function index()
    {
        UserModel::where('username', 'customer-1')->update(['nama' => 'Pelanggan Pertama']);
        return view('pengguna', ['data' => UserModel::all()]);
    }
}