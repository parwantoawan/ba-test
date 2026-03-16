<!-- Employee Form page content -->
<div class="">


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-<?= isset($employee_id) && $employee_id ? 'pencil' : 'plus' ?>"></i>
                        <?= $title ?>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form id="employeeForm" class="form-horizontal form-label-left">
                        <input type="hidden" id="employee_id" value="<?= $employee_id ?? '' ?>">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="nip">NIP <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="nip" name="nip" class="form-control" maxlength="20" required
                                    placeholder="Masukkan NIP">
                                <span class="help-block text-danger" id="error_nip"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="nama">Nama <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="nama" name="nama" class="form-control" maxlength="100" required
                                    placeholder="Masukkan Nama Lengkap">
                                <span class="help-block text-danger" id="error_nama"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="jenis_kelamin">Jenis Kelamin <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <span class="help-block text-danger" id="error_jenis_kelamin"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="jabatan">Jabatan <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select id="jabatan" name="jabatan" class="form-control" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php if (isset($position_list)): ?>
                                        <?php foreach ($position_list as $pos): ?>
                                            <option value="<?= htmlspecialchars($pos['name']) ?>">
                                                <?= htmlspecialchars($pos['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php elseif (isset($jabatan_list)): ?>
                                        <?php foreach ($jabatan_list as $jab): ?>
                                            <option value="<?= htmlspecialchars($jab['nama_jabatan']) ?>">
                                                <?= htmlspecialchars($jab['nama_jabatan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="help-block text-danger" id="error_jabatan"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="tanggal_aktif_jabatan">Tgl Aktif Jabatan
                                <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="date" id="tanggal_aktif_jabatan" name="tanggal_aktif_jabatan"
                                    class="form-control" required>
                                <span class="help-block text-danger" id="error_tanggal_aktif_jabatan"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="tanggal_masuk">Tgl Masuk <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control"
                                    required>
                                <span class="help-block text-danger" id="error_tanggal_masuk"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="status_karyawan">Status Karyawan <span
                                    class="required">*</span></label>
                            <div class="col-md-6 col-sm-6">
                                <select id="status_karyawan" name="status_karyawan" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Tetap">Tetap</option>
                                    <option value="Kontrak">Kontrak</option>
                                </select>
                                <span class="help-block text-danger" id="error_status_karyawan"></span>
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
                                <a href="<?= base_url('employees') ?>" class="btn btn-default">
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
        var employeeId = $('#employee_id').val();

        // If editing, load existing data
        if (employeeId) {
            loadEmployeeData(employeeId);
        }

        // Form submit
        $('#employeeForm').on('submit', function (e) {
            e.preventDefault();

            // Clear errors
            $('.help-block').text('');

            // Client-side validation
            var valid = true;
            var fields = ['nip', 'nama', 'jenis_kelamin', 'jabatan', 'tanggal_aktif_jabatan', 'tanggal_masuk', 'status_karyawan', 'is_active'];

            $.each(fields, function (i, field) {
                var val = $('#' + field).val();
                if (!val || val.trim() === '') {
                    $('#error_' + field).text('Field ini wajib diisi.');
                    valid = false;
                }
            });

            // NIP max length
            if ($('#nip').val().length > 20) {
                $('#error_nip').text('NIP maksimal 20 karakter.');
                valid = false;
            }

            // Nama max length
            if ($('#nama').val().length > 100) {
                $('#error_nama').text('Nama maksimal 100 karakter.');
                valid = false;
            }

            if (!valid) return;

            var url = employeeId
                ? BASE_URL + 'api/employees/update/' + employeeId
                : BASE_URL + 'api/employees/create';

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
                            window.location.href = BASE_URL + 'employees';
                        });
                    } else {
                        // Show validation errors
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

    function loadEmployeeData(id) {
        $.ajax({
            url: BASE_URL + 'api/employees/get/' + id,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    var emp = response.data;
                    $('#nip').val(emp.nip);
                    $('#nama').val(emp.nama);
                    $('#jenis_kelamin').val(emp.jenis_kelamin);
                    $('#jabatan').val(emp.jabatan);
                    // Format date for input[type=date]
                    $('#tanggal_aktif_jabatan').val(formatDateForInput(emp.tanggal_aktif_jabatan));
                    $('#tanggal_masuk').val(formatDateForInput(emp.tanggal_masuk));
                    $('#status_karyawan').val(emp.status_karyawan);
                    $('#is_active').val(emp.is_active);
                } else {
                    Swal.fire('Error!', 'Data karyawan tidak ditemukan.', 'error').then(function () {
                        window.location.href = BASE_URL + 'employees';
                    });
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal memuat data karyawan.', 'error');
            }
        });
    }

    function formatDateForInput(dateStr) {
        if (!dateStr) return '';
        var date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        var yyyy = date.getFullYear();
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        var dd = String(date.getDate()).padStart(2, '0');
        return yyyy + '-' + mm + '-' + dd;
    }
</script>