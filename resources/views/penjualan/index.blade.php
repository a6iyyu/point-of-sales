@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('/penjualan/export-excel') }}" class="btn btn-sm btn-primary mt-1">
                    <i class="fa fa-file-excel mr-2"></i> Ekspor Penjualan
                </a>
                <a href="{{ url('/penjualan/export-pdf') }}" target="_blank" class="btn btn-sm btn-warning mt-1">
                    <i class="fa fa-file-pdf mr-2"></i> Ekspor Penjualan
                </a>
                <button onclick="modal_action('{{ url('penjualan/create-ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Penjualan
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('sucess'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kasir</th>
                        <th>Pembeli</th>
                        <th>Kode Penjualan</th>
                        <th>Status Transaksi</th>
                        <th>Tanggal Penjualan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div
        id="my-modal"
        class="modal fade animate shake"
        tabindex="-1"
        role="dialog"
        data-backdrop="static"
        data-keyboard="false"
        data-width="75%"
        aria-hidden="true"
    ></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        const modal_action = (url = '') => $('#my-modal').load(url, () => $('#my-modal').modal('show'));

        var dataPenjualan;
        $(document).ready(() => {
            dataPenjualan = $('#table_penjualan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('penjualan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }, {
                    data: "user.nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "pembeli",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "penjualan_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "status",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "penjualan_tanggal",
                    className: "",
                    orderable: false,
                    searchable: false
                }, {
                    data: "aksi",
                    className: "text-center",
                    width: "150px",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush