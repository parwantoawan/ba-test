<!-- Position Form page content -->
<div class="">


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-<?= isset($position_id) && $position_id ? 'pencil' : 'plus' ?>"></i>
                        <?= $title ?>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="positionForm" class="form-horizontal form-label-left">
                        <input type="hidden" id="position_id" value="<?= $position_id ?? '' ?>">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="name">Nama Posisi <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="name" name="name" class="form-control" maxlength="100" required
                                    placeholder="Masukkan Nama Posisi">
                                <span class="help-block text-danger" id="error_name"></span>
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-md-offset-3">
                                <a href="<?= base_url('positions') ?>" class="btn btn-default">
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
        var posId = $('#position_id').val();
        if (posId) {
            $.ajax({
                url: BASE_URL + 'api/positions/get/' + posId, type: 'GET', dataType: 'json',
                success: function (r) { if (r.status) { $('#name').val(r.data.name); } else { Swal.fire('Error!', 'Posisi tidak ditemukan.', 'error').then(function () { window.location.href = BASE_URL + 'positions'; }); } },
                error: function () { Swal.fire('Error!', 'Gagal memuat data.', 'error'); }
            });
        }

        $('#positionForm').on('submit', function (e) {
            e.preventDefault();
            $('.help-block').text('');
            var name = $('#name').val().trim();
            if (!name) { $('#error_name').text('Nama posisi wajib diisi.'); return; }
            if (name.length > 100) { $('#error_name').text('Nama posisi maksimal 100 karakter.'); return; }

            var url = posId ? BASE_URL + 'api/positions/update/' + posId : BASE_URL + 'api/positions/create';
            $('#btnSave').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: url, type: 'POST', dataType: 'json', data: $(this).serialize(),
                success: function (r) {
                    if (r.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: r.message, timer: 1500, showConfirmButton: false }).then(function () { window.location.href = BASE_URL + 'positions'; });
                    } else {
                        if (r.errors) $.each(r.errors, function (f, m) { $('#error_' + f).text(m); });
                        Swal.fire('Gagal!', r.message, 'error');
                        $('#btnSave').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
                    }
                },
                error: function () { Swal.fire('Error!', 'Terjadi kesalahan.', 'error'); $('#btnSave').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan'); }
            });
        });
    });
</script>