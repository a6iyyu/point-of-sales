<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang as BarangModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Barang extends Controller
{
    public function index(): array|Collection
    {
        return BarangModel::with('kategori')->get();
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli'  => 'required|integer|min:0',
            'harga_jual'  => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);

        $data = $request->all();
        
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['gambar'] = $file->storeAs('images/barang', $filename, 'public');
        }

        $barang = BarangModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load('kategori'),
        ], 201);
    }

    public function show(BarangModel $barang): BarangModel
    {
        return $barang->load('kategori');
    }

    public function update(Request $request, BarangModel $barang): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'nullable|exists:m_kategori,kategori_id',
            'barang_kode' => "nullable|string|max:10|unique:m_barang,barang_kode,$barang->barang_id,barang_id",
            'barang_nama' => 'nullable|string|max:100',
            'harga_beli'  => 'nullable|integer|min:0',
            'harga_jual'  => 'nullable|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) return Response::json($validator->errors(), 422);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) Storage::disk('public')->delete($barang->gambar);   
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['gambar'] = $file->storeAs('images/barang', $filename, 'public');
        }

        $barang->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil diperbarui.',
            'data' => $barang->load('kategori'),
        ], 200);
    }

    public function destroy(BarangModel $barang): JsonResponse
    {
        if ($barang->gambar) Storage::disk('public')->delete($barang->gambar);
        $barang->delete();
        return response()->json(['status' => true, 'message' => 'Barang berhasil dihapus'], 200);
    }
}