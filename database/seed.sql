-- =============================================
-- CRUD Data Karyawan - Seed Data
-- SQL Server 2022
-- =============================================


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

-- Seed dummy employees (only if table is empty)
IF NOT EXISTS (SELECT TOP 1 1 FROM employees)
BEGIN
    DECLARE @idManagerIT INT, @idKeuangan INT, @idHRD INT, @idMarketing INT, @idAdministrasi INT;
    SELECT @idManagerIT = id FROM jabatan WHERE nama_jabatan = 'Manager IT';
    SELECT @idKeuangan = id FROM jabatan WHERE nama_jabatan = 'Staff Keuangan';
    SELECT @idHRD = id FROM jabatan WHERE nama_jabatan = 'Staff HRD';
    SELECT @idMarketing = id FROM jabatan WHERE nama_jabatan = 'Staff Marketing';
    SELECT @idAdministrasi = id FROM jabatan WHERE nama_jabatan = 'Staff Administrasi';

    SET IDENTITY_INSERT employees OFF;
    INSERT INTO employees (nip, nama, jenis_kelamin, jabatan_id, tanggal_aktif_jabatan, tanggal_masuk, status_karyawan, is_active)
    VALUES
        ('NIP001', 'Lukman Hakim', 'Laki - Laki', @idManagerIT, '2020-01-15', '2018-06-01', 'Permanen', 'active'),
        ('NIP002', 'Saiful Anwar', 'Laki - Laki', @idKeuangan, '2021-03-10', '2019-08-15', 'Permanen', 'active'),
        ('NIP003', 'Sinta Mei', 'Perempuan', @idHRD, '2022-05-20', '2020-02-01', 'Kontrak', 'active'),
        ('NIP004', 'Tubagus', 'Laki - Laki', @idMarketing, '2021-07-01', '2019-11-10', 'Permanen', 'active'),
        ('NIP005', 'Nana M', 'Perempuan', @idAdministrasi, '2023-01-05', '2021-04-20', 'Kontrak', 'active');
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
