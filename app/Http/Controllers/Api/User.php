<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class User extends Controller
{
    public function index(): Collection
    {
        return UserModel::all();
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);
        $user = UserModel::create($request->all());
        return Response::json(['success' => true, 'user' => $user], 201);
    }

    public function show(UserModel $user): UserModel
    {
        return $user;
    }

    public function update(Request $request, UserModel $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => "nullable|string|min:3|max:20|unique:m_user,username,{$user->user_id},user_id",
            'nama'     => 'nullable|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);

        $user->update([
            'username' => $request->username ?: $user->username,
            'nama'     => $request->nama ?: $user->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id ?: $user->level_id,
        ]);

        return Response::json(['success' => true, 'user' => $user], 200);
    }

    public function destroy(UserModel $user): JsonResponse
    {
        $user->delete();
        return Response::json(['success' => true, 'message' => 'Data berhasil dihapus!'], 200);
    }
}