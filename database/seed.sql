-- =============================================
-- CRUD Data Karyawan - Seed Data
-- SQL Server 2022
-- =============================================

-- Seed dummy employees (only if table is empty)
IF NOT EXISTS (SELECT TOP 1 1 FROM employees)
BEGIN
    SET IDENTITY_INSERT employees OFF;

    INSERT INTO employees (nip, nama, jenis_kelamin, jabatan, tanggal_aktif_jabatan, tanggal_masuk, status_karyawan, is_active)
    VALUES
        ('NIP001', 'Lukman Hakim', 'Laki-laki', 'Manager IT', '2020-01-15', '2018-06-01', 'Tetap', 'active'),
        ('NIP002', 'Saiful Anwar', 'Laki-laki', 'Staff Keuangan', '2021-03-10', '2019-08-15', 'Tetap', 'active'),
        ('NIP003', 'Sinta Mei', 'Perempuan', 'Staff HRD', '2022-05-20', '2020-02-01', 'Kontrak', 'active'),
        ('NIP004', 'Tubagus', 'Laki-laki', 'Staff Marketing', '2021-07-01', '2019-11-10', 'Tetap', 'active'),
        ('NIP005', 'Nana M', 'Perempuan', 'Staff Administrasi', '2023-01-05', '2021-04-20', 'Kontrak', 'active');
END
GO

-- Seed dummy users (only if table is empty)
-- Passwords are hashed using PHP password_hash('password', PASSWORD_DEFAULT)
-- admin123 => $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (placeholder, will be set by PHP)
-- user123  => (placeholder, will be set by PHP)
IF NOT EXISTS (SELECT TOP 1 1 FROM users)
BEGIN
    SET IDENTITY_INSERT users OFF;

    -- Note: These passwords will be re-hashed by the application init script
    INSERT INTO users (username, password, role, is_active)
    VALUES
        ('admin', '$2y$10$placeholder_admin_hash', 'administrator', 'active'),
        ('user', '$2y$10$placeholder_user_hash', 'user', 'active');
END
GO

-- Seed dummy jabatan / job positions (only if table is empty)
IF NOT EXISTS (SELECT TOP 1 1 FROM jabatan)
BEGIN
    SET IDENTITY_INSERT jabatan OFF;

    INSERT INTO jabatan (nama_jabatan, deskripsi, is_active)
    VALUES
        ('Manager IT', 'Mengelola divisi IT', 'active'),
        ('Staff Keuangan', 'Mengelola keuangan perusahaan', 'active'),
        ('Staff HRD', 'Mengelola sumber daya manusia', 'active'),
        ('Staff Marketing', 'Mengelola pemasaran produk', 'active'),
        ('Staff Administrasi', 'Mengelola administrasi kantor', 'active');
END
GO
