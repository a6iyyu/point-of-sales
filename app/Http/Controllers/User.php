<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class User extends Controller
{
    public function index(): View
    {
        return view('pengguna', ['data' => UserModel::all()]);
    }

    public function add(): View
    {
        return view('tambah-pengguna');
    }

    public function save(Request $request): RedirectResponse
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/pengguna');
    }

    public function edit($id): View
    {
        return view('edit-pengguna', ['data' => UserModel::find($id)]);
    }

    public function put(Request $request, $id): RedirectResponse
    {
        $user = UserModel::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;
        $user->save();
        return redirect('/pengguna');
    }

    public function delete($id): RedirectResponse
    {
        UserModel::find($id)->delete();
        return redirect('/pengguna');
    }
}