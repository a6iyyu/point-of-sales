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
        $user = UserModel::firstOrNew([
            'username' => 'manager33',
            'nama' => 'Manager Tiga Tiga',
            'password' => Hash::make('12345'),
            'level_id' => 2,
        ])->save();

        return view('pengguna', ['data' => $user]);
    }
}