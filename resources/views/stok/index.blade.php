
@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modal_action('{{ url('/stok/import') }}')" class="btn btn-sm btn-info mt-1">Impor Stok</button>
                <a href="{{ url('/stok/export-excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel mr-2"></i>Ekspor Stok</a>
                <a href="{{ url('/stok/export-pdf') }}" target="_blank" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf mr-2"></i>Ekspor Stok</a>
                <button onclick="modal_action('{{ url('stok/create-ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah AJAX</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('sucess'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Supplier</th>
                        <th>Barang</th>
                        <th>Tanggal</th>
                        <th>Jumlah Stok</th>
                        <th>Sisa Stok</th>
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

        var dataStok;
        $(document).ready(() => {
            dataStok = $('#table_stok').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('stok/list') }}",
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
                    data: "supplier.supplier_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "barang.barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "stok_tanggal",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "stok_jumlah",
                    className: "",
                    orderable: true,
                    searchable: true
                },{
                    data: "stok_sisa",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "aksi",
                    className: "text-center",
                    width: "80px",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush