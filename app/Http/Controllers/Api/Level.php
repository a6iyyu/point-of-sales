<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level as LevelModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class Level extends Controller
{
    public function index(): Collection
    {
        return LevelModel::all();
    }

    public function store(Request $request): JsonResponse
    {
        return Response::json(LevelModel::create($request->all()), 201);
    }

    public function show(LevelModel $level): array|Collection|Level|Model|null
    {
        return LevelModel::find($level);
    }

    public function update(Request $request, LevelModel $level): array|Collection|Level|Model|null
    {
        $level->update($request->all());
        return LevelModel::find($level);
    }

    public function destroy(LevelModel $level): JsonResponse
    {
        $level->delete();
        return Response::json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }
}