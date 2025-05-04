<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Logout extends Controller
{
    public function __invoke()
    {
        if (JWTAuth::invalidate(JWTAuth::getToken())) {
            return Response::json(['success' => true, 'message' => 'Logout Berhasil!'], 200);
        }
    }
}