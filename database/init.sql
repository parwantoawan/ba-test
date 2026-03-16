-- =============================================
-- CRUD Data Karyawan - Database Initialization
-- SQL Server 2022
-- =============================================

-- Create employees table if not exists
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='employees' AND xtype='U')
BEGIN
    CREATE TABLE employees (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nip VARCHAR(20) NOT NULL UNIQUE,
        nama VARCHAR(100) NOT NULL,
        jenis_kelamin VARCHAR(20) NOT NULL,
        jabatan VARCHAR(100) NOT NULL,
        tanggal_aktif_jabatan DATE NOT NULL,
        tanggal_masuk DATE NOT NULL,
        status_karyawan VARCHAR(20) NOT NULL,
        is_active VARCHAR(10) NOT NULL DEFAULT 'active'
    );
END
GO

-- Create users table if not exists
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='users' AND xtype='U')
BEGIN
    CREATE TABLE users (
        id INT IDENTITY(1,1) PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) NOT NULL,
        is_active VARCHAR(10) NOT NULL DEFAULT 'active'
    );
END
GO

-- Create jabatan (job positions) table if not exists
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='jabatan' AND xtype='U')
BEGIN
    CREATE TABLE jabatan (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nama_jabatan VARCHAR(100) NOT NULL UNIQUE,
        deskripsi VARCHAR(255) NULL,
        is_active VARCHAR(10) NOT NULL DEFAULT 'active'
    );
END
GO
