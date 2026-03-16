<!-- Jabatan Form page content -->
<div class="">


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-<?= isset($jabatan_id) && $jabatan_id ? 'pencil' : 'plus' ?>"></i>
                        <?= $title ?>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="jabatanForm" class="form-horizontal form-label-left">
                        <input type="hidden" id="jabatan_id" value="<?= $jabatan_id ?? '' ?>">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="nama_jabatan">Nama Jabatan <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="nama_jabatan" name="nama_jabatan" class="form-control"
                                    maxlength="100" required placeholder="Masukkan Nama Jabatan">
                                <span class="help-block text-danger" id="error_nama_jabatan"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="deskripsi">Deskripsi</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea id="deskripsi" name="deskripsi" class="form-control" maxlength="255" rows="3"
                                    placeholder="Masukkan Deskripsi"></textarea>
                                <span class="help-block text-danger" id="error_deskripsi"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="is_active">Status Aktif <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select id="is_active" name="is_active" class="form-control" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                                <span class="help-block text-danger" id="error_is_active"></span>
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-md-offset-3">
                                <a href="<?= base_url('jabatan') ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success" id="btnSave">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var jabatanId = $('#jabatan_id').val();

        if (jabatanId) {
            loadJabatanData(jabatanId);
        }

        $('#jabatanForm').on('submit', function (e) {
            e.preventDefault();

            $('.help-block').text('');

            var valid = true;
            var namaVal = $('#nama_jabatan').val().trim();

            if (!namaVal) {
                $('#error_nama_jabatan').text('Nama jabatan wajib diisi.');
                valid = false;
            }
            if (namaVal.length > 100) {
                $('#error_nama_jabatan').text('Nama jabatan maksimal 100 karakter.');
                valid = false;
            }
            if ($('#deskripsi').val().length > 255) {
                $('#error_deskripsi').text('Deskripsi maksimal 255 karakter.');
                valid = false;
            }

            if (!valid) return;

            var url = jabatanId
                ? BASE_URL + 'api/jabatan/update/' + jabatanId
                : BASE_URL + 'api/jabatan/create';

            $('#btnSave').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.href = BASE_URL + 'jabatan';
                        });
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function (field, msg) {
                                $('#error_' + field).text(msg);
                            });
                        }
                        Swal.fire('Gagal!', response.message, 'error');
                        $('#btnSave').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                    $('#btnSave').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
                }
            });
        });
    });

    function loadJabatanData(id) {
        $.ajax({
            url: BASE_URL + 'api/jabatan/get/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    var jab = response.data;
                    $('#nama_jabatan').val(jab.nama_jabatan);
                    $('#deskripsi').val(jab.deskripsi);
                    $('#is_active').val(jab.is_active);
                } else {
                    Swal.fire('Error!', 'Data jabatan tidak ditemukan.', 'error').then(function () {
                        window.location.href = BASE_URL + 'jabatan';
                    });
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal memuat data jabatan.', 'error');
            }
        });
    }
</script>