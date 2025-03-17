@empty($barang)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/barang/' . $barang->barang_id . '/update-ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label>Kategori</label>
                        <div>
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
                                <option>- Pilih Kategori -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}" {{ old('kategori_id', $barang->kategori_id) == $item->kategori_id ? 'selected' : '' }}>
                                        {{ $item->kategori_nama }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label>Kode</label>
                        <input value="{{ $barang->barang_kode }}" type="text" name="barang_kode" id="barang_kode" class="form-control" required>
                        <small id="error-barang_kode" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{ $barang->barang_nama }}" type="text" name="barang_nama" id="barang_nama" class="form-control" required>
                        <small id="error-barang_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input value="{{ $barang->harga_jual }}" type="number" class="form-control" id="harga_beli" name="harga_beli" required />
                        <small id="error-harga_beli" class="error-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <input value="{{ $barang->harga_jual }}" type="number" class="form-control" id="harga_jual" name="harga_jual" required />
                        <small id="error-harga_jual" class="error-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(() => {
            $("#form-edit").validate({
                rules: {
                    barang_kode: {
                        required: true,
                        minlength: 3,
                        maxlength: 6,
                        pattern: /^[A-Z0-9]+$/
                    },
                    barang_nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100,
                        pattern: /^[a-zA-Z\s]+$/
                    },
                    harga_beli: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    harga_jual: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    kategori_id: {
                        required: true
                    }
                },
                submitHandler: (form) => {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: (response) => {
                            if (response.status) {
                                $('#my-modal').modal('hide');
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            } else {
                                $('.error-text').text('');
                                $.each(response.message_field, (prefix, val) => $('#error-' + prefix).text(val[0]));
                                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: (error, element) => {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: (element) => $(element).addClass('is-invalid'),
                unhighlight: (element) => $(element).removeClass('is-invalid'),
            });
        });
    </script>
@endempty