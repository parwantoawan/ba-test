<!-- Positions List page content -->
<div class="">


    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-sitemap"></i> Daftar Posisi</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- Toolbar -->
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-4 col-sm-6">
                            <?php if (($user['role'] ?? '') === 'administrator'): ?>
                                <a href="<?= base_url('positions/add') ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Tambah Posisi
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
                                    placeholder="Cari posisi...">
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
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th class="sortable" data-sort="name">Nama Posisi <i class="fa fa-sort"></i></th>
                                    <th>Dibuat</th>
                                    <th>Diperbarui</th>
                                    <?php if (($user['role'] ?? '') === 'administrator'): ?>
                                        <th width="120">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="posTableBody">
                                <tr>
                                    <td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat
                                        data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

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

<script>
    var currentPage = 1, perPage = 10, searchQuery = '', sortBy = 'id', sortDir = 'ASC';
    var userRole = '<?= $user['role'] ?? '' ?>';

    $(document).ready(function () {
        loadPositions();
        $('#perPage').on('change', function () { perPage = parseInt($(this).val()); currentPage = 1; loadPositions(); });
        $('#btnSearch').on('click', function () { searchQuery = $('#searchInput').val().trim(); currentPage = 1; loadPositions(); });
        $('#searchInput').on('keypress', function (e) { if (e.which === 13) { searchQuery = $(this).val().trim(); currentPage = 1; loadPositions(); } });
        $(document).on('click', '.sortable', function () { var c = $(this).data('sort'); if (sortBy === c) { sortDir = sortDir === 'ASC' ? 'DESC' : 'ASC'; } else { sortBy = c; sortDir = 'ASC'; } loadPositions(); });
    });

    function loadPositions() {
        var offset = (currentPage - 1) * perPage;
        $.ajax({
            url: BASE_URL + 'api/positions/list',
            type: 'GET', dataType: 'json',
            data: { limit: perPage, offset: offset, search: searchQuery, sort_by: sortBy, sort_dir: sortDir },
            beforeSend: function () { $('#posTableBody').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat...</td></tr>'); },
            success: function (r) {
                if (r.status && r.data.length > 0) {
                    var html = '';
                    $.each(r.data, function (i, pos) {
                        html += '<tr>';
                        html += '<td>' + (offset + i + 1) + '</td>';
                        html += '<td>' + escapeHtml(pos.name) + '</td>';
                        html += '<td>' + formatDateTime(pos.created_at) + '</td>';
                        html += '<td>' + formatDateTime(pos.updated_at) + '</td>';
                        if (userRole === 'administrator') {
                            html += '<td>';
                            html += '<a href="' + BASE_URL + 'positions/edit/' + pos.id + '" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></a> ';
                            html += '<button class="btn btn-danger btn-xs btn-delete" data-id="' + pos.id + '" data-nama="' + escapeHtml(pos.name) + '" title="Hapus"><i class="fa fa-trash"></i></button>';
                            html += '</td>';
                        }
                        html += '</tr>';
                    });
                    $('#posTableBody').html(html);
                    var start = offset + 1, end = Math.min(offset + perPage, r.total);
                    $('#tableInfo').text('Menampilkan ' + start + ' - ' + end + ' dari ' + r.total + ' data');
                    buildPagination(r.total);
                } else {
                    $('#posTableBody').html('<tr><td colspan="5" class="text-center text-muted">Tidak ada data posisi.</td></tr>');
                    $('#tableInfo').text(''); $('#pagination').html('');
                }
            },
            error: function () { $('#posTableBody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>'); }
        });
    }

    function buildPagination(total) {
        var tp = Math.ceil(total / perPage), html = '';
        if (tp <= 1) { $('#pagination').html(''); return; }
        html += '<li class="' + (currentPage === 1 ? 'disabled' : '') + '"><a href="javascript:;" onclick="goToPage(' + (currentPage - 1) + ')">&laquo;</a></li>';
        for (var i = 1; i <= tp; i++) {
            if (i === 1 || i === tp || (i >= currentPage - 2 && i <= currentPage + 2)) html += '<li class="' + (i === currentPage ? 'active' : '') + '"><a href="javascript:;" onclick="goToPage(' + i + ')">' + i + '</a></li>';
            else if (i === currentPage - 3 || i === currentPage + 3) html += '<li class="disabled"><a>...</a></li>';
        }
        html += '<li class="' + (currentPage === tp ? 'disabled' : '') + '"><a href="javascript:;" onclick="goToPage(' + (currentPage + 1) + ')">&raquo;</a></li>';
        $('#pagination').html(html);
    }
    function goToPage(p) { var tp = Math.ceil(parseInt($('#tableInfo').text().split('dari ')[1]) / perPage); if (p < 1 || p > tp) return; currentPage = p; loadPositions(); }

    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id'), nama = $(this).data('nama');
        Swal.fire({
            title: 'Konfirmasi Hapus', html: 'Hapus posisi <strong>' + nama + '</strong>?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE_URL + 'api/positions/delete/' + id, type: 'POST', dataType: 'json',
                    success: function (r) { if (r.status) { Swal.fire('Berhasil!', r.message, 'success'); loadPositions(); } else { Swal.fire('Gagal!', r.message, 'error'); } },
                    error: function () { Swal.fire('Error!', 'Terjadi kesalahan.', 'error'); }
                });
            }
        });
    });

    function escapeHtml(t) { if (!t) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; }
    function formatDateTime(s) { if (!s) return '-'; var d = new Date(s); if (isNaN(d.getTime())) return s; return String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0') + '/' + d.getFullYear() + ' ' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0'); }
</script>