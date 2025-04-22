<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang as BarangModel;
use App\Models\DetailPenjualan as DetailPenjualanModel;
use App\Models\Penjualan as PenjualanModel;
use App\Models\Stok as StokModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Penjualan extends Controller
{
    public function index(): View
    {
        $page = (object) ['title' => 'Kasir Transaksi Penjualan'];
        $breadcrumb = (object) [
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Transaksi Penjualan']
        ];

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'active_menu' => 'penjualan', 'penjualan' => PenjualanModel::all()]);
    }

    public function list(): JsonResponse
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'status', 'penjualan_tanggal')->with('user');
        return datatables()->of($penjualan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn  = '<button onclick="modal_action(\'' . url("/penjualan/$penjualan->penjualan_id/show-ajax") . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $user = auth()->user();
                if (in_array($user->level->level_kode, ['ADM', 'MNG'])) {
                    if ($penjualan->status == 'berhasil') $btn .= '<button onclick="modal_action(\'' . url("/penjualan/$penjualan->penjualan_id/delete-ajax") . '\')" class="btn btn-danger btn-sm">Batalkan</button>';
                }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax(): View
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_jual')
            ->with('stok')
            ->orderBy('barang_nama', 'asc')
            ->get();

        return view('penjualan.create-ajax')->with(compact('barang'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:100',
                'barang.*' => 'required|integer',
                'harga.*' => 'required|numeric',
                'jumlah.*' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) return response()->json(['status' => false, 'message' => 'Validasi gagal, pastikan semua telah terisi', 'message_field' => $validator->errors()]);

            foreach ($request->barang as $key => $value) {
                $total_stok = StokModel::where('barang_id', $value)->sum('stok_sisa');
                if ($request->jumlah[$key] > $total_stok) return response()->json(['status' => false, 'message' => 'Stok barang tidak cukup : ' . BarangModel::find($value)->barang_nama]);
            }

            $penjualan = PenjualanModel::create([
                'user_id' => $request->user_id,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => now()->setTimezone('Asia/Jakarta')->format('sidmY') . Str::random(4),
                'penjualan_tanggal' => now()->setTimezone('Asia/Jakarta'),
            ]);

            foreach ($request->barang as $key => $value) {
                DetailPenjualanModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $value,
                    'harga' => $request->harga[$key],
                    'jumlah' => $request->jumlah[$key],
                ]);

                $semua_stok = StokModel::where('barang_id', $value)
                    ->where('stok_sisa', '>', 0)
                    ->orderBy('stok_id', 'asc')
                    ->get();

                if ($semua_stok->count() > 0) {
                    $jumlah_dibeli = $request->jumlah[$key];
                    foreach ($semua_stok as $stok_per_row) {
                        if ($jumlah_dibeli <= 0) break;
                        if ($stok_per_row->stok_sisa >= $jumlah_dibeli) {
                            $stok_per_row->update(['stok_sisa' => $stok_per_row->stok_sisa - $jumlah_dibeli]);
                            $jumlah_dibeli = 0;
                        } else {
                            $jumlah_dibeli -= $stok_per_row->stok_sisa;
                            $stok_per_row->update(['stok_sisa' => 0]);
                        }
                    }
                }
            }
            return response()->json(['status' => true, 'message' => 'Barang berhasil ditambahkan']);
        }
    }

    public function show_ajax(string $id): JsonResponse|View
    {
        $penjualan = PenjualanModel::with(['user', 'detail'])->find($id);
        if (!$penjualan) return Response::json(['message' => 'Data tidak ditemukan'], 404);

        $harga = [];
        $jumlah = [];
        foreach ($penjualan->detail as $key => $value) {
            $harga[] = $value->harga;
            $jumlah[] = $value->jumlah;
        }

        $total = array_map([$this, 'total_belanja'], $harga, $jumlah);
        $total = array_sum($total);
        $penjualan->total = $total;
        return view('penjualan.show-ajax', ['penjualan' => $penjualan]);
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with(['user', 'detail'])->find($id);
        if (!$penjualan) return Response::json(['message' => 'Data tidak ditemukan'], 404);
        return view('penjualan.confirm-ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            $penjualan->status = 'dibatalkan';
            $penjualan->save();

            $detail_semua = DetailPenjualanModel::where('penjualan_id', $id)->orderBy('detail_id', 'desc')->get();
            foreach ($detail_semua as $value) {
                $semua_stok = StokModel::where('barang_id', $value->barang_id)->orderBy('stok_id', 'desc')->get();
                $jumlah_kembali = $value->jumlah;
                foreach ($semua_stok as $stok_per_row) {
                    if ($jumlah_kembali <= 0) break;
                    $dapat_diisi = $stok_per_row->stok_jumlah - $stok_per_row->stok_sisa;

                    if ($dapat_diisi >= $jumlah_kembali) {
                        $stok_per_row->update(['stok_sisa' => $stok_per_row->stok_sisa + $jumlah_kembali]);
                        $jumlah_kembali = 0;
                    } else {
                        $stok_per_row->update(['stok_sisa' => $stok_per_row->stok_sisa + $dapat_diisi]);
                        $jumlah_kembali -= $dapat_diisi;
                    }
                }
            }

            return response()->json(['url' => url('/penjualan'), 'status' => true, 'message' => 'Transaksi berhasil dibatalkan, dan stok dikembalikan']);
        }
    }

    public function export_excel()
    {
        $semua_penjualan = PenjualanModel::with('detail')->with('user')->orderBy('penjualan_tanggal', 'asc')->get();
        $penjualan = [];

        foreach ($semua_penjualan as $data) {
            foreach ($data->detail as $item) {
                $penjualan[] = [
                    'nama' => $data->user->nama,
                    'pembeli' => $data->pembeli,
                    'penjualan_kode' => $data->penjualan_kode,
                    'tgl_penjualan' => $data->penjualan_tanggal,
                    'barang' => $item->barang->barang_nama,
                    'harga' => $item->harga,
                    'jumlah' => $item->jumlah,
                    'total' => $item->jumlah * $item->harga,
                ];
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Stok');
        $sheet->fromArray(['Nama', 'Pembeli', 'Kode Penjualan', 'Tanggal', 'Barang', 'Harga', 'Jumlah', 'Total'], null, 'A1');
        $row = 2;
        $start_merge = $row;
        foreach ($penjualan as $index => $item) {
            $sheet->setCellValue("A{$row}", $item['nama']);
            $sheet->setCellValue("B{$row}", $item['pembeli']);
            $sheet->setCellValue("C{$row}", $item['penjualan_kode']);
            $sheet->setCellValue("D{$row}", $item['tgl_penjualan']);
            $sheet->setCellValue("E{$row}", $item['barang']);
            $sheet->setCellValue("F{$row}", $item['harga']);
            $sheet->setCellValue("G{$row}", $item['jumlah']);
            $sheet->setCellValue("H{$row}", $item['total']);
            $next = $penjualan[$index + 1]['penjualan_kode'] ?? null;
            if ($item['penjualan_kode'] !== $next) {
                if ($start_merge !== $row) {
                    $sheet->mergeCells("A{$start_merge}:A{$row}");
                    $sheet->mergeCells("B{$start_merge}:B{$row}");
                    $sheet->mergeCells("C{$start_merge}:C{$row}");
                    $sheet->getStyle("A{$start_merge}:C{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle("A{$start_merge}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $start_merge = $row + 1;
            }

            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . 'Data_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx' . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(): Response
    {
        $penjualan_semua = PenjualanModel::with('detail')->with('user')->orderBy('penjualan_tanggal', 'asc')->get();

        $rekap = [];
        foreach ($penjualan_semua as $data) {
            foreach ($data->detail as $item) {
                $rekap[] = [
                    'nama' => $data->user->nama,
                    'pembeli' => $data->pembeli,
                    'penjualan_kode' => $data->penjualan_kode,
                    'tgl_penjualan' => $data->penjualan_tanggal,
                    'barang' => $item->barang->barang_nama,
                    'harga' => $item->harga,
                    'jumlah' => $item->jumlah,
                    'total' => $item->jumlah * $item->harga,
                ];
            }
        }

        $pdf = Pdf::loadView('penjualan.export-pdf', ['rekap' => $rekap]);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(["isRemoteEnabled"], true);
        $pdf->render();
        return $pdf->stream('Data_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    private static function total_belanja($harga, $jumlah): float|int
    {
        return $harga * $jumlah;
    }
}