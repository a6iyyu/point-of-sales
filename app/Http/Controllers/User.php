<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Level as LevelModel;
use App\Models\User as UserModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class User extends Controller
{
    public function index(): View
    {
        $page = (object) ['title' => 'Daftar pengguna yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'User']
        ];

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => LevelModel::all(), 'active_menu' => 'user']);
    }

    public function list(Request $request): JsonResponse
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');
        if ($request->level_id) $users->where('level_id', $request->level_id);
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modal_action(\''.url('/user/' . $user->user_id . '/show-ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modal_action(\''.url('/user/' . $user->user_id . '/edit-ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .=  '<button onclick="modal_action(\''.url('/user/' . $user->user_id . '/delete-ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        $page = (object) ['title' => 'Tambah pengguna.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Pengguna',
            'list' => ['Home', 'User', 'Add']
        ];

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => LevelModel::all(), 'active_menu' => 'user']);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id): View
    {
        $user = UserModel::with('level')->find($id);
        $page = (object) ['title' => 'Detail Pengguna'];
        $breadcrumb = (object) [
            'title' => 'Detail Pengguna',
            'list' => ['Home', 'User', 'Detail'],
        ];

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'active_menu' => 'user']);
    }

    public function edit(string $id): View
    {
        $page = (object) ['title' => 'Edit pengguna'];
        $breadcrumb = (object) [
            'title' => 'Edit Pengguna',
            'list' => ['Home', 'User', 'Edit']
        ];

        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => UserModel::find($id),
            'level' => LevelModel::all(),
            'active_menu' => 'user',
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer',
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!UserModel::find($id)) return redirect('/user')->with('error', 'Data pengguna tidak ditemukan.');
        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data pengguna berhasil dihapus.');
        } catch (QueryException $exception) {
            return redirect('/user')->with('error', 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create_ajax(): View
    {
        return view('user.create-ajax')->with('level', LevelModel::select('level_id', 'level_nama')->get());
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:6',
            ]);
    
            if ($validator->fails()) {
                return Response::json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal.',
                    'message_field' => $validator->errors(),
                ]);
            }

            UserModel::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id,
            ]);

            return Response::json(['status'  => true, 'message' => 'Data user berhasil disimpan']);
        }
        return redirect('/user');
    }

    public function edit_ajax(string $id): View
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        $user = UserModel::find($id);
        return view('user.edit-ajax', ['level' => $level, 'user' => $user]);
    }

    public function update_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama'     => 'required|max:100',
                'password' => 'nullable|min:6|max:20',
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);

            if (UserModel::find($id)) {
                if (!$request->filled('password')) $request->request->remove('password');

                UserModel::find($id)->update([
                    'username' => $request->username,
                    'nama' => $request->nama,
                    'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
                    'level_id' => $request->level_id,
                ]);

                return Response::json(['status' => true, 'message' => 'Data berhasil diperbarui.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }

        return redirect('/user');
    }

    public function confirm_ajax(string $id): View
    {
        return view('user.confirm-ajax', ['user' => UserModel::find($id)]);
    }

    public function delete_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            if (UserModel::find($id)) {
                UserModel::find($id)->delete();
                return Response::json(['status' => true, 'message' => 'Data berhasil dihapus.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }

        return redirect('/user');
    }
}