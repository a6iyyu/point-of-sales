<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);
        $credentials = $request->only('username', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return Response::json(['success' => false, 'message' => 'Username atau Password Anda salah.'], 401);
        }

        return Response::json(['success' => true, 'user' => Auth::guard('api')->user(), 'token' => $token], 200);
    }
}