<!-- Dashboard page content -->
<div class="">


    <?php $isAdmin = (($user['role'] ?? '') === 'administrator'); ?>

    <!-- Common Stats tiles -->
    <div class="row tile_count" id="statsContainer">
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-users"></i> Karyawan Aktif</span>
            <div class="count text-success" id="statActive">-</div>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-male"></i> Laki-laki</span>
            <div class="count" id="statLaki">-</div>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-female"></i> Perempuan</span>
            <div class="count" id="statPerempuan">-</div>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-id-badge"></i> Permanen</span>
            <div class="count" id="statTetap">-</div>
        </div>
        <div class="col-md-2 col-sm-4 tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Kontrak</span>
            <div class="count" id="statKontrak">-</div>
        </div>
        <?php if ($isAdmin): ?>
            <div class="col-md-2 col-sm-4 tile_stats_count">
                <span class="count_top"><i class="fa fa-sitemap"></i> Total Jabatan</span>
                <div class="count" id="statJabatan">-</div>
            </div>
        <?php endif; ?>
    </div>

    <div class="clearfix"></div>

    <?php if ($isAdmin): ?>
        <!-- ADMIN ONLY: Latest Position Changes -->
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-exchange"></i> Perubahan Jabatan Terbaru</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama Karyawan</th>
                                        <th>Posisi</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="latestChangesBody">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fa fa-spinner fa-spin"></i> Memuat...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-users"></i> Data Karyawan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>Kelola data karyawan perusahaan termasuk NIP, jabatan, status, dan informasi lainnya.</p>
                    <a href="<?= base_url('employees') ?>" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-right"></i> Lihat Data Karyawan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-briefcase"></i> Data Jabatan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>Kelola data jabatan/posisi yang tersedia di perusahaan.</p>
                    <a href="<?= base_url('jabatan') ?>" class="btn btn-info btn-sm">
                        <i class="fa fa-arrow-right"></i> Lihat Data Jabatan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-sitemap"></i> Data Posisi</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p>Kelola data posisi/jabatan referensi untuk riwayat jabatan karyawan.</p>
                        <a href="<?= base_url('positions') ?>" class="btn btn-warning btn-sm">
                            <i class="fa fa-arrow-right"></i> Lihat Data Posisi
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-history"></i> Riwayat Jabatan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <p>Lihat riwayat perubahan jabatan karyawan dari awal hingga posisi terbaru.</p>
                        <a href="<?= base_url('employees/history') ?>" class="btn btn-success btn-sm">
                            <i class="fa fa-arrow-right"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-info-circle"></i> Informasi Sistem</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr>
                            <td width="200"><strong>Nama Aplikasi</strong></td>
                            <td>CRUD Data Karyawan</td>
                        </tr>
                        <tr>
                            <td><strong>Framework</strong></td>
                            <td>CodeIgniter 3</td>
                        </tr>
                        <tr>
                            <td><strong>Database</strong></td>
                            <td>Microsoft SQL Server 2022</td>
                        </tr>
                        <tr>
                            <td><strong>Template</strong></td>
                            <td>Gentelella Admin Template</td>
                        </tr>
                        <tr>
                            <td><strong>Login Sebagai</strong></td>
                            <td>
                                <?= htmlspecialchars($user['username'] ?? '') ?>
                                (<?= htmlspecialchars($user['role'] ?? '') ?>)
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;

    $(document).ready(function () {
        loadDashboardStats();
    });

    function loadDashboardStats() {
        $.ajax({
            url: BASE_URL + 'api/dashboard/stats',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    var data = response.data;
                    $('#statActive').text(data.employees.active);
                    $('#statLaki').text(data.employees.laki);
                    $('#statPerempuan').text(data.employees.perempuan);
                    $('#statTetap').text(data.employees.tetap);
                    $('#statKontrak').text(data.employees.kontrak);

                    if (isAdmin) {
                        $('#statJabatan').text(data.positions || 0);

                        // Render latest position changes
                        if (data.latest_changes && data.latest_changes.length > 0) {
                            var html = '';
                            $.each(data.latest_changes, function (i, c) {
                                var statusLabel = !c.end_date
                                    ? '<span class="label label-primary">Current Position</span>'
                                    : '<span class="label label-default">Selesai</span>';
                                html += '<tr>';
                                html += '<td>' + escapeHtml(c.nip || '-') + '</td>';
                                html += '<td>' + escapeHtml(c.employee_name || '-') + '</td>';
                                html += '<td>' + escapeHtml(c.position_name || '-') + '</td>';
                                html += '<td>' + formatDate(c.start_date) + '</td>';
                                html += '<td>' + (c.end_date ? formatDate(c.end_date) : '-') + '</td>';
                                html += '<td>' + statusLabel + '</td>';
                                html += '</tr>';
                            });
                            $('#latestChangesBody').html(html);
                        } else {
                            $('#latestChangesBody').html('<tr><td colspan="6" class="text-center text-muted">Belum ada data perubahan jabatan.</td></tr>');
                        }
                    }
                }
            },
            error: function () {
                console.log('Gagal memuat statistik dashboard');
            }
        });
    }

    function escapeHtml(t) { if (!t) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; }
    function formatDate(s) { if (!s) return '-'; var d = new Date(s); if (isNaN(d.getTime())) return s; return String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear(); }
</script>