<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Register extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'nama'     => 'required',
            'password' => 'required|min:5|confirmed',
            'level_id' => 'required',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $user = User::create([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => bcrypt($request->password),
            'level_id'  => $request->level_id,
            'image'     => $image->hashName(),
        ]);

        if ($user) return Response::json(['success' => true, 'user' => $user], 200);
        return Response::json(['success' => false, 'message' => 'Terjadi kesalahan'], 409);
    }
}