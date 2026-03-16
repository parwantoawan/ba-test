<!-- Employee List page content -->
<div class="">


    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-users"></i> Daftar Karyawan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- Toolbar -->
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-4 col-sm-6">
                            <?php if (($user['role'] ?? '') === 'administrator'): ?>
                                <a href="<?= base_url('employees/add') ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Tambah Karyawan
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6 col-md-offset-1">
                            <div class="form-group">
                                <select id="perPage" class="form-control input-sm">
                                    <option value="5">5 per halaman</option>
                                    <option value="10" selected>10 per halaman</option>
                                    <option value="25">25 per halaman</option>
                                    <option value="50">50 per halaman</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="searchInput"
                                    placeholder="Cari NIP, nama, jabatan...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" type="button" id="btnSearch">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="employeeTable">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th class="sortable" data-sort="nip">NIP <i class="fa fa-sort"></i></th>
                                    <th class="sortable" data-sort="nama">Nama <i class="fa fa-sort"></i></th>
                                    <th>Jenis Kelamin</th>
                                    <th class="sortable" data-sort="jabatan">Jabatan <i class="fa fa-sort"></i></th>
                                    <th>Tgl Aktif Jabatan</th>
                                    <th class="sortable" data-sort="tanggal_masuk">Tgl Masuk <i class="fa fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="status_karyawan">Status <i class="fa fa-sort"></i>
                                    </th>
                                    <th>Aktif</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-md-6">
                            <p id="tableInfo" class="text-muted"></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <ul class="pagination" id="pagination"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-history"></i> Riwayat Jabatan - <span id="modalEmpName"></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Posisi / Jabatan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="modalHistoryBody">
                            <tr>
                                <td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="modalFullHistoryLink" class="btn btn-info btn-sm">
                    <i class="fa fa-external-link"></i> Lihat Halaman Penuh
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    var currentPage = 1;
    var perPage = 10;
    var searchQuery = '';
    var sortBy = 'id';
    var sortDir = 'ASC';
    var userRole = '<?= $user['role'] ?? '' ?>';

    $(document).ready(function () {
        loadEmployees();

        $('#perPage').on('change', function () {
            perPage = parseInt($(this).val());
            currentPage = 1;
            loadEmployees();
        });

        $('#btnSearch').on('click', function () {
            searchQuery = $('#searchInput').val().trim();
            currentPage = 1;
            loadEmployees();
        });

        $('#searchInput').on('keypress', function (e) {
            if (e.which === 13) {
                searchQuery = $(this).val().trim();
                currentPage = 1;
                loadEmployees();
            }
        });

        $(document).on('click', '.sortable', function () {
            var col = $(this).data('sort');
            if (sortBy === col) {
                sortDir = sortDir === 'ASC' ? 'DESC' : 'ASC';
            } else {
                sortBy = col;
                sortDir = 'ASC';
            }
            loadEmployees();
        });
    });

    function loadEmployees() {
        var offset = (currentPage - 1) * perPage;

        $.ajax({
            url: BASE_URL + 'api/employees/list',
            type: 'GET',
            dataType: 'json',
            data: {
                limit: perPage,
                offset: offset,
                search: searchQuery,
                sort_by: sortBy,
                sort_dir: sortDir
            },
            beforeSend: function () {
                $('#employeeTableBody').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</td></tr>');
            },
            success: function (response) {
                if (response.status && response.data.length > 0) {
                    var html = '';
                    $.each(response.data, function (index, emp) {
                        var statusBadge = emp.is_active === 'active'
                            ? '<span class="label label-success">Aktif</span>'
                            : '<span class="label label-danger">Tidak Aktif</span>';
                        var statusKaryawan = emp.status_karyawan === 'Tetap'
                            ? '<span class="label label-primary">' + emp.status_karyawan + '</span>'
                            : '<span class="label label-warning">' + emp.status_karyawan + '</span>';

                        html += '<tr>';
                        html += '<td>' + (offset + index + 1) + '</td>';
                        html += '<td>' + escapeHtml(emp.nip) + '</td>';
                        html += '<td>' + escapeHtml(emp.nama) + '</td>';
                        html += '<td>' + escapeHtml(emp.jenis_kelamin) + '</td>';
                        html += '<td>' + escapeHtml(emp.jabatan) + '</td>';
                        html += '<td>' + formatDate(emp.tanggal_aktif_jabatan) + '</td>';
                        html += '<td>' + formatDate(emp.tanggal_masuk) + '</td>';
                        html += '<td>' + statusKaryawan + '</td>';
                        html += '<td>' + statusBadge + '</td>';

                        // Action buttons - always show history, admin gets edit/delete
                        html += '<td>';
                        html += '<button class="btn btn-info btn-xs btn-history" data-id="' + emp.id + '" data-nama="' + escapeHtml(emp.nama) + '" title="Riwayat Jabatan"><i class="fa fa-history"></i></button> ';
                        if (userRole === 'administrator') {
                            html += '<a href="' + BASE_URL + 'employees/edit/' + emp.id + '" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></a> ';
                            html += '<button class="btn btn-danger btn-xs btn-delete" data-id="' + emp.id + '" data-nama="' + escapeHtml(emp.nama) + '" title="Hapus"><i class="fa fa-trash"></i></button>';
                        }
                        html += '</td>';

                        html += '</tr>';
                    });
                    $('#employeeTableBody').html(html);

                    var start = offset + 1;
                    var end = Math.min(offset + perPage, response.total);
                    $('#tableInfo').text('Menampilkan ' + start + ' - ' + end + ' dari ' + response.total + ' data');

                    buildPagination(response.total);
                } else {
                    $('#employeeTableBody').html('<tr><td colspan="10" class="text-center text-muted">Tidak ada data karyawan.</td></tr>');
                    $('#tableInfo').text('');
                    $('#pagination').html('');
                }
            },
            error: function () {
                $('#employeeTableBody').html('<tr><td colspan="10" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>');
            }
        });
    }

    function buildPagination(total) {
        var totalPages = Math.ceil(total / perPage);
        var html = '';

        if (totalPages <= 1) {
            $('#pagination').html('');
            return;
        }

        html += '<li class="' + (currentPage === 1 ? 'disabled' : '') + '"><a href="javascript:;" onclick="goToPage(' + (currentPage - 1) + ')">&laquo;</a></li>';

        for (var i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += '<li class="' + (i === currentPage ? 'active' : '') + '"><a href="javascript:;" onclick="goToPage(' + i + ')">' + i + '</a></li>';
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += '<li class="disabled"><a href="javascript:;">...</a></li>';
            }
        }

        html += '<li class="' + (currentPage === totalPages ? 'disabled' : '') + '"><a href="javascript:;" onclick="goToPage(' + (currentPage + 1) + ')">&raquo;</a></li>';

        $('#pagination').html(html);
    }

    function goToPage(page) {
        var totalPages = Math.ceil(parseInt($('#tableInfo').text().split('dari ')[1]) / perPage);
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        loadEmployees();
    }

    // View History (Modal)
    $(document).on('click', '.btn-history', function () {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        $('#modalEmpName').text(nama);
        $('#modalFullHistoryLink').attr('href', BASE_URL + 'employees/history/' + id);
        $('#modalHistoryBody').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>');
        $('#historyModal').modal('show');

        $.ajax({
            url: BASE_URL + 'api/employees/' + id + '/history',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status && response.data.history.length > 0) {
                    var html = '';
                    $.each(response.data.history, function (index, h) {
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
                    $('#modalHistoryBody').html(html);
                } else {
                    $('#modalHistoryBody').html('<tr><td colspan="5" class="text-center text-muted">Belum ada riwayat.</td></tr>');
                }
            },
            error: function () {
                $('#modalHistoryBody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>');
            }
        });
    });

    // Delete handler
    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: 'Apakah Anda yakin ingin menghapus karyawan <strong>' + nama + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE_URL + 'api/employees/delete/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            loadEmployees();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        var date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        var dd = String(date.getDate()).padStart(2, '0');
        var mm = String(date.getMonth() + 1).padStart(2, '0');
        var yyyy = date.getFullYear();
        return dd + '/' + mm + '/' + yyyy;
    }
</script>