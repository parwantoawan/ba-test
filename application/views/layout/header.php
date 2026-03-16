<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= isset($title) ? $title . ' | ' : '' ?>CRUD Data Karyawan
    </title>

    <!-- Bootstrap 3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
    <!-- Animate CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <!-- Gentelella Custom CSS -->
    <link href="<?= base_url('assets/css/gentelella.min.css') ?>" rel="stylesheet">
    <!-- Custom App CSS -->
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">

    <!-- jQuery & Global Vars (Load before views so inline scripts work) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        var BASE_URL = '<?= base_url() ?>';
        var USER_ROLE = '<?= $user['role'] ?? '' ?>';
    </script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <!-- Sidebar -->
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?= base_url('dashboard') ?>" class="site_title">
                            <i class="fa fa-building"></i> <span>Data Karyawan</span>
                        </a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username'] ?? 'User') ?>&amp;background=fff&amp;color=2a3f54&amp;size=64"
                                alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Selamat Datang,</span>
                            <h2>
                                <?= htmlspecialchars($user['username'] ?? 'User') ?>
                            </h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Menu Utama</h3>
                            <ul class="nav side-menu">
                                <li class="<?= ($title ?? '') === 'Dashboard' ? 'active' : '' ?>">
                                    <a href="<?= base_url('dashboard') ?>">
                                        <i class="fa fa-home"></i> Dashboard
                                    </a>
                                </li>
                                <li
                                    class="<?= in_array($title ?? '', ['Data Karyawan', 'Tambah Karyawan', 'Edit Karyawan']) ? 'active' : '' ?>">
                                    <a href="<?= base_url('employees') ?>">
                                        <i class="fa fa-users"></i> Data Karyawan
                                    </a>
                                </li>
                                <li
                                    class="<?= in_array($title ?? '', ['Data Jabatan', 'Tambah Jabatan', 'Edit Jabatan']) ? 'active' : '' ?>">
                                    <a href="<?= base_url('jabatan') ?>">
                                        <i class="fa fa-briefcase"></i> Data Jabatan
                                    </a>
                                </li>
                                <li
                                    class="<?= in_array($title ?? '', ['Data Posisi', 'Tambah Posisi', 'Edit Posisi']) ? 'active' : '' ?>">
                                    <a href="<?= base_url('positions') ?>">
                                        <i class="fa fa-sitemap"></i> Data Posisi
                                    </a>
                                </li>
                                <li class="<?= ($title ?? '') === 'Riwayat Jabatan Karyawan' ? 'active' : '' ?>">
                                    <a href="<?= base_url('employees/history') ?>">
                                        <i class="fa fa-history"></i> Riwayat Jabatan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle" style="float: left; margin: 0; padding-top: 13px; width: auto;">
                            <a id="menu_toggle" style="padding: 0 15px;"><i class="fa fa-bars"></i></a>
                        </div>
                        <div
                            style="float: left; padding: 15px 10px 0 0; font-size: 18px; font-weight: 500; color: #73879C; line-height: 1.2;">
                            <?= isset($title) ? htmlspecialchars($title) : '' ?>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username'] ?? 'User') ?>&amp;background=fff&amp;color=2a3f54&amp;size=32"
                                        alt="">
                                    <?= htmlspecialchars($user['username'] ?? 'User') ?>
                                    <span class="fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li>
                                        <a href="javascript:;">
                                            <span class="badge bg-green pull-right">
                                                <?= htmlspecialchars($user['role'] ?? '') ?>
                                            </span>
                                            <span>Role</span>
                                        </a>
                                    </li>
                                    <li><a href="<?= base_url('logout') ?>"><i class="fa fa-sign-out pull-right"></i>
                                            Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">