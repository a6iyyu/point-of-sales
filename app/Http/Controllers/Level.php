<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Level as LevelModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class Level extends Controller
{
    public function index()
    {
        $page = (object) ['title' => 'Daftar level yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level'],
        ];

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => LevelModel::all(), 'active_menu' => 'level']);
    }

    public function list(Request $request): JsonResponse
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');
        if ($request->level_id) $level->where('level_id', $request->level_id);
        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btnsm">Detail</a> ';
                $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form method="POST" action="' . url('/level/' . $level->level_id) . '" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        $page = (object) ['title' => 'Tambah Level.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level', 'Add']
        ];

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'active_menu' => 'level']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|max:3',
            'level_nama' => 'required|string',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    public function show(string $id): View
    {
        $level = LevelModel::find($id);
        $page = (object) ['title' => 'Detail Level'];
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail'],
        ];

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'active_menu' => 'level']);
    }

    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit Level'];
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => LevelModel::find($id),
            'active_menu' => 'level',
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'level_kode' => 'required|max:3',
            'level_nama' => 'required|string',
        ]);

        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!LevelModel::find($id)) return redirect('/level')->with('error', 'Data level tidak ditemukan.');
        LevelModel::find($id)->delete();
        return redirect('/level')->with('success', 'Data level berhasil dihapus.');        
    }
}