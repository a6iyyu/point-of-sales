<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Level as LevelModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\Facades\DataTables;

class Level extends Controller
{
    public function index(): View
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
                $btn = '<button onclick="modal_action(\''.url('/level/' . $level->level_id . '/show-ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modal_action(\''.url('/level/' . $level->level_id . '/edit-ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .=  '<button onclick="modal_action(\''.url('/level/' . $level->level_id . '/delete-ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'level_kode' => "required|max:3|unique:level_kode,level_id",
            'level_nama' => "required|string|unique:level_nama,level_id",
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
            'level_kode' => "required|max:3|unique:level_kode,$id,level_id",
            'level_nama' => "required|string|unique:level_nama,$id,level_id",
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

    public function create_ajax(): View
    {
        return view('level.create-ajax', ['level' => LevelModel::all()]);
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:3|regex:/^[A-Z]+$/',
                'level_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            ]);
    
            if ($validator->fails()) {
                return Response::json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal.',
                    'message_field' => $validator->errors(),
                ]);
            }

            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama,
            ]);

            return Response::json(['status'  => true, 'message' => 'Data level berhasil disimpan']);
        }
        return redirect('/level');
    }

    public function edit_ajax(string $id): View
    {
        return view('level.edit-ajax', ['level' => LevelModel::find($id)]);
    }

    public function update_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:3|regex:/^[A-Z]+$/|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);

            if (LevelModel::find($id)) {
                LevelModel::find($id)->update([
                    'level_kode' => $request->level_kode,
                    'level_nama' => $request->level_nama,
                ]);

                return Response::json(['status' => true, 'message' => 'Data berhasil diperbarui.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
            }
        }
        return redirect('/level');
    }

    public function confirm_ajax(string $id): View
    {
        return view('level.confirm-ajax', ['level' => LevelModel::find($id)]);
    }

    public function delete_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if (!$request->ajax() && !$request->wantsJson()) return redirect('/level');
        $user = LevelModel::find($id);
        if (!$user) return Response::json(['status' => false, 'message' => 'Data tidak ditemukan.']);
    
        try {
            $user->delete();
            return Response::json(['status' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (QueryException $exception) {
            return Response::json(['status' => false, 'message' => 'Data tidak bisa dihapus karena dipakai di tabel lain.']);
        }
    }

    public function import(): View
    {
        return view('level.import');
    }

    public function import_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024'],
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal.', 'message_field' => $validator->errors()]);
            
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $data = $reader->load($request->file('file_level')->getRealPath())->getActiveSheet()->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $rows => $value) {
                    if ($rows > 1) {
                        $insert[] = [
                            'level_kode' => $value['A'],
                            'level_nama' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) LevelModel::insertOrIgnore($insert);
                return Response::json(['status' => true, 'message' => 'Data berhasil diimpor.']);
            } else {
                return Response::json(['status' => false, 'message' => 'Tidak ada data yang diimpor.']);
            }
        }

        return redirect('/level');
    }

    public function export_excel()
    {
        $level = LevelModel::select('level_kode', 'level_nama')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Level');
        $sheet->setCellValue('C1', 'Nama Level');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($level as $key => $value) {
            $sheet->setCellValue("A{$baris}", $no);
            $sheet->setCellValue("B{$baris}", $value->level_kode);
            $sheet->setCellValue("C{$baris}", $value->level_nama);
            $baris++;
            $no++;
        }

        foreach(range('A', 'C') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        $sheet->setTitle('Data Level');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . 'Data Level ' . date('Y-m-d_H-i-s') . '.xlsx' . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit; 
    }

    public function export_pdf(): HttpResponse
    {
        $level = LevelModel::select('level_kode', 'level_nama')->orderBy('level_kode')->get();

        $pdf = Pdf::loadView('level.export-pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Level ' . date('Y-m-d H:i:s') . '.pdf');
    }
}