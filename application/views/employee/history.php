<!-- Employee Position History page content -->
<div class="">


    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-history"></i> Riwayat Jabatan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- Employee Selector -->
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Karyawan</label>
                                <select id="employeeSelect" class="form-control">
                                    <option value="">-- Pilih Karyawan --</option>
                                    <?php if (isset($employees)): ?>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= $emp['id'] ?>" <?= (isset($employee_id) && $employee_id == $emp['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($emp['nip'] . ' - ' . $emp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Info Card (shown after selection) -->
                    <div id="employeeInfo" style="display:none; margin-bottom: 20px;">
                        <div class="well" style="background: #f5f5f5; border: 1px solid #e5e5e5; padding: 15px;">
                            <div class="row">
                                <div class="col-md-3"><strong>NIP:</strong> <span id="infoNip">-</span></div>
                                <div class="col-md-3"><strong>Nama:</strong> <span id="infoNama">-</span></div>
                                <div class="col-md-3"><strong>Jabatan Saat Ini:</strong> <span id="infoJabatan">-</span>
                                </div>
                                <div class="col-md-3"><strong>Status:</strong> <span id="infoStatus">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- History Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th>Posisi / Jabatan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Pilih karyawan untuk melihat riwayat jabatan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var preselected = $('#employeeSelect').val();
        if (preselected) loadHistory(preselected);

        $('#employeeSelect').on('change', function () {
            var empId = $(this).val();
            if (empId) {
                loadHistory(empId);
            } else {
                $('#employeeInfo').hide();
                $('#historyTableBody').html('<tr><td colspan="5" class="text-center text-muted">Pilih karyawan untuk melihat riwayat jabatan.</td></tr>');
            }
        });
    });

    function loadHistory(empId) {
        $.ajax({
            url: BASE_URL + 'api/employees/' + empId + '/history',
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $('#historyTableBody').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat riwayat...</td></tr>');
            },
            success: function (response) {
                if (response.status) {
                    var emp = response.data.employee;
                    var history = response.data.history;

                    // Show employee info
                    $('#infoNip').text(emp.nip);
                    $('#infoNama').text(emp.nama);
                    $('#infoJabatan').text(emp.jabatan);
                    var statusHtml = emp.is_active === 'active'
                        ? '<span class="label label-success">Aktif</span>'
                        : '<span class="label label-danger">Tidak Aktif</span>';
                    $('#infoStatus').html(statusHtml);
                    $('#employeeInfo').show();

                    if (history.length > 0) {
                        var html = '';
                        $.each(history, function (index, h) {
                            var statusLabel = !h.end_date
                                ? '<span class="label label-primary"><i class="fa fa-check"></i> Current Position</span>'
                                : '<span class="label label-default">Selesai</span>';
                            html += '<tr' + (!h.end_date ? ' style="background-color:#eaf7f0;"' : '') + '>';
                            html += '<td>' + (index + 1) + '</td>';
                            html += '<td><strong>' + escapeHtml(h.position_name || '-') + '</strong></td>';
                            html += '<td>' + formatDate(h.start_date) + '</td>';
                            html += '<td>' + (h.end_date ? formatDate(h.end_date) : '-') + '</td>';
                            html += '<td>' + statusLabel + '</td>';
                            html += '</tr>';
                        });
                        $('#historyTableBody').html(html);
                    } else {
                        $('#historyTableBody').html('<tr><td colspan="5" class="text-center text-muted">Belum ada riwayat jabatan.</td></tr>');
                    }
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function () {
                $('#historyTableBody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>');
            }
        });
    }

    function escapeHtml(t) { if (!t) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; }
    function formatDate(s) { if (!s) return '-'; var d = new Date(s); if (isNaN(d.getTime())) return s; return String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear(); }
</script>