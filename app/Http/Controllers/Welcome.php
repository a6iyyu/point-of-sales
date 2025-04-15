<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class Welcome extends Controller
{
    public function index(): View
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome'],
        ];

        return view('welcome', ['breadcrumb' => $breadcrumb, 'active_menu' => 'dashboard']);
    }

    public function profile(): View
    {
        $breadcrumb = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Profile'],
        ];

        return view('profil', ['breadcrumb' => $breadcrumb, 'active_menu' => 'profile']);        
    }

    public function upload_photo(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = User::find(Auth::id());

            if (!$user) return redirect()->back()->with('error', 'User tidak ditemukan.');

            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $filename = 'profile-' . $user->user_id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profile-photo', $filename);
    
                $user->foto_profil = $filename;
                $user->save();
            }

            return redirect()->back()->with('success', 'Foto profil berhasil diunggah.');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Gagal mengunggah foto profil.');
        }
    }
}