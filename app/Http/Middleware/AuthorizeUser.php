<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        $role = $request->user()->get_role();
        if (in_array($role, $roles)) return $next($request);
        abort(403, 'Maaf, Anda tidak punya akses ke halaman ini!');
    }
}