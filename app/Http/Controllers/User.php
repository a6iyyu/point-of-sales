<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class User extends Controller
{
    public function index(): View
    {
        UserModel::create([
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345'),
        ]);

        return view('pengguna', ['data' => UserModel::all()]);
    }
}