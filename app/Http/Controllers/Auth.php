<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth as Authentication;
use Illuminate\View\View;

class Auth extends Controller
{
    public function login(): Factory|Redirector|RedirectResponse|View
    {
        if (Authentication::check()) return redirect('/');
        return view('auth.login');
    }

    public function postlogin(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Authentication::attempt($credentials)) return response()->json(['status' => true, 'message' => 'Berhasil Masuk!', 'redirect' => url('/')]);
            return response()->json(['status' => false, 'message' => 'Proses masuk gagal!']);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Authentication::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}