<!-- Jabatan List page content -->
<div class="">


    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-briefcase"></i> Daftar Jabatan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- Toolbar -->
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-4 col-sm-6">
                            <?php if (($user['role'] ?? '') === 'administrator'): ?>
                                <a href="<?= base_url('jabatan/add') ?>" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus"></i> Tambah Jabatan
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
                                    placeholder="Cari nama jabatan...">
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
                        <table class="table table-striped table-bordered table-hover" id="jabatanTable">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th class="sortable" data-sort="nama_jabatan">Nama Jabatan <i
                                            class="fa fa-sort"></i></th>
                                    <th class="sortable" data-sort="deskripsi">Deskripsi <i class="fa fa-sort"></i></th>
                                    <th>Status</th>
                                    <?php if (($user['role'] ?? '') === 'administrator'): ?>
                                        <th width="120">Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="jabatanTableBody">
                                <tr>
                                    <td colspan="5" class="text-center">
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

<script>
    var currentPage = 1;
    var perPage = 10;
    var searchQuery = '';
    var sortBy = 'id';
    var sortDir = 'ASC';
    var userRole = '<?= $user['role'] ?? '' ?>';

    $(document).ready(function () {
        loadJabatan();

        $('#perPage').on('change', function () {
            perPage = parseInt($(this).val());
            currentPage = 1;
            loadJabatan();
        });

        $('#btnSearch').on('click', function () {
            searchQuery = $('#searchInput').val().trim();
            currentPage = 1;
            loadJabatan();
        });

        $('#searchInput').on('keypress', function (e) {
            if (e.which === 13) {
                searchQuery = $(this).val().trim();
                currentPage = 1;
                loadJabatan();
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
            loadJabatan();
        });
    });

    function loadJabatan() {
        var offset = (currentPage - 1) * perPage;

        $.ajax({
            url: BASE_URL + 'api/jabatan/list',
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
                $('#jabatanTableBody').html('<tr><td colspan="5" class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</td></tr>');
            },
            success: function (response) {
                if (response.status && response.data.length > 0) {
                    var html = '';
                    $.each(response.data, function (index, jab) {
                        var statusBadge = jab.is_active === 'active'
                            ? '<span class="label label-success">Aktif</span>'
                            : '<span class="label label-danger">Tidak Aktif</span>';

                        html += '<tr>';
                        html += '<td>' + (offset + index + 1) + '</td>';
                        html += '<td>' + escapeHtml(jab.nama_jabatan) + '</td>';
                        html += '<td>' + escapeHtml(jab.deskripsi || '-') + '</td>';
                        html += '<td>' + statusBadge + '</td>';

                        if (userRole === 'administrator') {
                            html += '<td>';
                            html += '<a href="' + BASE_URL + 'jabatan/edit/' + jab.id + '" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></a> ';
                            html += '<button class="btn btn-danger btn-xs btn-delete" data-id="' + jab.id + '" data-nama="' + escapeHtml(jab.nama_jabatan) + '" title="Hapus"><i class="fa fa-trash"></i></button>';
                            html += '</td>';
                        }

                        html += '</tr>';
                    });
                    $('#jabatanTableBody').html(html);

                    var start = offset + 1;
                    var end = Math.min(offset + perPage, response.total);
                    $('#tableInfo').text('Menampilkan ' + start + ' - ' + end + ' dari ' + response.total + ' data');

                    buildPagination(response.total);
                } else {
                    $('#jabatanTableBody').html('<tr><td colspan="5" class="text-center text-muted">Tidak ada data jabatan.</td></tr>');
                    $('#tableInfo').text('');
                    $('#pagination').html('');
                }
            },
            error: function () {
                $('#jabatanTableBody').html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>');
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
        loadJabatan();
    }

    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        var nama = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: 'Apakah Anda yakin ingin menghapus jabatan <strong>' + nama + '</strong>?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE_URL + 'api/jabatan/delete/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            loadJabatan();
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
</script>