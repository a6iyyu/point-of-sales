<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang as BarangModel;
use App\Models\Stok as StokModel;
use App\Models\Supplier as SupplierModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
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

class Stok extends Controller
{
    public function index(): View
    {
        $page = (object) ['title' => 'Daftar stok yang terdaftar dalam sistem'];
        $breadcrumb = (object) [
            'title' => 'Stok',
            'list' => ['Home', 'Stok']
        ];

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'active_menu' => 'stok']);
    }

    public function list(): JsonResponse
    {
        $stok = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah', 'stok_sisa')
            ->with('supplier')
            ->with('user')
            ->with('barang');

        return datatables()->of($stok)
            ->addIndexColumn()
            ->addColumn('aksi', fn($stok) => '<button onclick="modal_action(\'' . url("/stok/$stok->stok_id/delete-ajax") . '\')" class="btn btn-danger btn-sm">Hapus</button>')
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax(): View
    {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->orderBy('supplier_nama', 'asc')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->orderBy('barang_nama', 'asc')->get();
        return view('stok.create-ajax', compact('supplier', 'barang'));
    }

    public function store_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'supplier_id' => 'required|integer',
                'user_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_jumlah' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi gagal.', 'message_field' => $validator->errors()]);

            StokModel::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id,
                'barang_id' => $request->barang_id,
                'stok_jumlah' => $request->stok_jumlah,
                'stok_sisa' => $request->stok_jumlah,
                'stok_tanggal' => now('Asia/Jakarta'),
            ]);

            return Response::json(['status' => true, 'message' => 'Stok berhasil ditambahkan']);
        }
        return redirect('/stok');
    }

    public function confirm_ajax(string $id): View
    {
        return view('stok.confirm-ajax', ['stok' => StokModel::find($id)]);
    }

    public function delete_ajax(Request $request, string $id): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $stok = StokModel::find($id);
                if ($stok) {
                    $stok->delete();
                    return Response::json(['status' => true,  'message' => 'Data berhasil dihapus']);
                } else {
                    return Response::json(['status' => false, 'message' => 'Data tidak ditemukan']);
                }
            } catch (Exception $exception) {
                if ($exception->getCode() == '23000') return Response::json(['status' => false,  'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain.']);
                return Response::json(['status' => false, 'message' => 'Terjadi kesalahan saat menghapus data: ' . $exception->getMessage()]);
            }
        }
        return redirect('/stok');
    }

    public function import(): View
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request): JsonResponse|Redirector|RedirectResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ]);

            if ($validator->fails()) return Response::json(['status' => false, 'message' => 'Validasi Gagal', 'message_field' => $validator->errors()]);

            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $data = $reader->load($request->file('file_stok')->getRealPath())->getActiveSheet()->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                $insert = [];

                foreach ($data as $rows => $value) {
                    if ($rows > 1) {
                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'user_id' => $value['C'],
                            'stok_jumlah' => $value['D'],
                            'stok_sisa' => $value['D'],
                            'stok_tanggal' => now()->setTimezone('Asia/Jakarta'),
                            'created_at' => now()->setTimezone('Asia/Jakarta'),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    foreach ($insert as $row) StokModel::create($row);
                    return Response::json(['status' => true, 'message' => 'Data berhasil diimpor.']);
                }
            } else {
                return Response::json(['status' => false, 'message' => 'Tidak ada data yang diimpor.']);
            }
        }
        return redirect("/stok");
    }

    public function export_excel()
    {
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah', 'stok_sisa')
            ->orderBy('supplier_id', 'asc')
            ->with('supplier')
            ->with('user')
            ->with('barang')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pengguna');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Barang');
        $sheet->setCellValue('E1', 'Jumlah Stok');
        $sheet->setCellValue('F1', 'Sisa Stok');
        $sheet->setCellValue('G1', 'Tanggal Stok');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        $number = 1;
        $rows = 2;
        foreach ($stok as $list) {
            $sheet->setCellValue("A{$rows}", $number++);
            $sheet->setCellValue("B{$rows}", $list->user->nama);
            $sheet->setCellValue("C{$rows}", $list->supplier->supplier_nama);
            $sheet->setCellValue("D{$rows}", $list->barang->barang_nama);
            $sheet->setCellValue("E{$rows}", $list->stok_jumlah);
            $sheet->setCellValue("F{$rows}", $list->stok_sisa);
            $sheet->setCellValue("G{$rows}", $list->stok_tanggal);
            $rows++;
        }

        foreach (range('A', 'G') as $id) $sheet->getColumnDimension($id)->setAutoSize(true);
        $sheet->setTitle('Data Stok');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx' . '"');
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
        $stok = StokModel::select('supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah', 'stok_sisa')
            ->orderBy('stok_tanggal', 'asc')
            ->with('supplier')
            ->with('user')
            ->with('barang')
            ->get();

        $pdf = Pdf::loadView('stok.export-pdf', ['stok' => $stok]);
        $pdf->setPaper('A4', 'potrait'); 
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Stok_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}