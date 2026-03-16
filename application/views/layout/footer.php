</div>
<!-- /page content -->

<!-- footer content -->
<footer>
    <div class="pull-right">
        CRUD Data Karyawan &copy;
        <?= date('Y') ?>
    </div>
    <div class="clearfix"></div>
</footer>
<!-- /footer content -->
</div>
</div>

<!-- Bootstrap 3 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
<!-- NProgress -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Gentelella Custom JS -->
<script src="<?= base_url('assets/js/gentelella.min.js') ?>"></script>

<!-- Fallback JS for Profile Dropdown -->
<script>
$(function() {
    // Fallback: force show dropdown on profile click if not working
    $('.user-profile.dropdown-toggle').on('click', function(e) {
        var $parent = $(this).closest('.dropdown');
        if (!$parent.hasClass('open')) {
            $parent.addClass('open');
        } else {
            $parent.removeClass('open');
        }
        e.preventDefault();
        return false;
    });
    // Close dropdown if click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-profile.dropdown-toggle, .dropdown-menu').length) {
            $('.dropdown').removeClass('open');
        }
    });
});
</script>

<!-- Page specific JS -->
<?php if (isset($page_js)): ?>
    <script src="<?= base_url($page_js) ?>"></script>
<?php endif; ?>

</body>

</html>