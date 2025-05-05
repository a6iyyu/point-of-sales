<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori as KategoriModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class Kategori extends Controller
{
    public function index(): Collection
    {
        return KategoriModel::all();
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|string|min:4|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100'
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);
        $kategori = KategoriModel::create($request->all());
        return Response::json(['success' => true, 'kategori' => $kategori], 201);
    }

    public function show(KategoriModel $kategori): KategoriModel
    {
        return $kategori;
    }

    public function update(Request $request, KategoriModel $kategori): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kategori_kode' => "nullable|string|min:4|unique:m_kategori,kategori_kode,{$kategori->kategori_id},kategori_id",
            'kategori_nama' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);

        $kategori->update([
            'kategori_kode' => $request->kategori_kode ?: $kategori->kategori_kode,
            'kategori_nama' => $request->kategori_nama ?: $kategori->kategori_nama,
        ]);

        return Response::json(['success' => true, 'kategori' => $kategori], 200);
    }

    public function destroy(KategoriModel $kategori): JsonResponse
    {
        $kategori->delete();
        return Response::json(['success' => true, 'message' => 'Data Berhasil Dihapus!'], 200);
    }
}