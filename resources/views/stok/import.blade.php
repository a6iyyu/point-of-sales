<form action="{{ url('/stok/import-ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    {{-- @csrf --}}
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template-stok.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel mr-2"></i> Download
                    </a>
                    <small id="error-kategori_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="file_stok" class="font-weight-bold">Pilih File</label>
                    <div class="custom-file">
                        <input type="file" name="file_stok" id="file_stok" class="custom-file-input" required>
                        <label class="custom-file-label" for="file_stok">Pilih berkas...</label>
                    </div>
                    <small id="error-file_stok" class="error-text form-text text-danger mt-1"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>
<script>
    document.getElementById('file_stok').addEventListener('change', (e) => {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas...';
        e.target.nextElementSibling.innerText = fileName;
    });

    $(document).ready(() => {
        $("#form-import").validate({
            rules: {
                file_stok: {
                    required: true,
                    extension: "xlsx"
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data:  new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            dataStok.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, (prefix, val) => $('#error-' + prefix).text(val[0]));
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