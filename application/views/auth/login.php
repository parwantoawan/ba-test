<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | CRUD Data Karyawan</title>

    <!-- Bootstrap 3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">
    <!-- Gentelella Custom CSS -->
    <link href="<?= base_url('assets/css/gentelella.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form id="loginForm">
                        <h1><i class="fa fa-building"></i> Data Karyawan</h1>

                        <div id="loginAlert" class="alert alert-danger" style="display:none;">
                            <span id="loginAlertText"></span>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required autofocus />
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default btn-block submit" id="btnLogin">
                                <i class="fa fa-sign-in"></i> Log in
                            </button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div class="clearfix"></div>
                            <br />
                            <div>
                                <p class="text-muted text-center">
                                    <i class="fa fa-info-circle"></i> CRUD Data Karyawan &copy;
                                    <?= date('Y') ?>
                                </p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap 3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                var username = $('#username').val().trim();
                var password = $('#password').val().trim();

                // Client-side validation
                if (!username || !password) {
                    $('#loginAlertText').text('Username dan password wajib diisi.');
                    $('#loginAlert').fadeIn();
                    return;
                }

                $('#loginAlert').fadeOut();
                $('#btnLogin').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    url: '<?= base_url('login') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        username: username,
                        password: password
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function () {
                                window.location.href = response.redirect;
                            });
                        } else {
                            $('#loginAlertText').text(response.message);
                            $('#loginAlert').fadeIn();
                            $('#btnLogin').prop('disabled', false).html('<i class="fa fa-sign-in"></i> Log in');
                        }
                    },
                    error: function () {
                        $('#loginAlertText').text('Terjadi kesalahan. Silakan coba lagi.');
                        $('#loginAlert').fadeIn();
                        $('#btnLogin').prop('disabled', false).html('<i class="fa fa-sign-in"></i> Log in');
                    }
                });
            });
        });
    </script>
</body>

</html>