<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next, $role = ''): Response
    {
        $user = $request->user();
        if ($user->has_role($role)) return $next($request);
        abort(403, 'Maaf, Anda tidak punya akses ke halaman ini!');
    }
}