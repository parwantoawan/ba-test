<?php
/**
 * Database Initialization Script
 * 
 * This script runs on application startup to ensure tables exist
 * and seed data is populated. Uses the sqlsrv driver directly.
 */

$maxRetries = 30;
$retryDelay = 2;

$serverName = "database,1433";
$connectionOptions = array(
    "Database" => "master",
    "Uid" => "sa",
    "PWD" => "StrongPassword123!",
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30,
    "ReturnDatesAsStrings" => true
);

echo "=== Database Initialization Script ===\n";
echo "Waiting for SQL Server to be ready...\n";

$conn = null;
for ($i = 1; $i <= $maxRetries; $i++) {
    $conn = @sqlsrv_connect($serverName, $connectionOptions);
    if ($conn) {
        echo "Connected to SQL Server on attempt $i\n";
        break;
    }
    echo "Attempt $i/$maxRetries - SQL Server not ready, waiting {$retryDelay}s...\n";
    sleep($retryDelay);
}

if (!$conn) {
    $errors = sqlsrv_errors();
    echo "Failed to connect to SQL Server after $maxRetries attempts.\n";
    if ($errors) {
        foreach ($errors as $error) {
            echo "Error: " . $error['message'] . "\n";
        }
    }
    exit(1);
}

echo "Connection successful!\n";

// Helper to run SQL
function run_sql($conn, $sql, $label)
{
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        echo "Error ($label): ";
        print_r(sqlsrv_errors());
        return false;
    }
    echo "$label: OK\n";
    sqlsrv_free_stmt($stmt);
    return true;
}

// ==============================
// CREATE TABLES
// ==============================
echo "\n--- Creating tables ---\n";

// 1. employees
run_sql($conn, "
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
", "Employees table");

// 2. users
run_sql($conn, "
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
", "Users table");

// 3. jabatan
run_sql($conn, "
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='jabatan' AND xtype='U')
BEGIN
    CREATE TABLE jabatan (
        id INT IDENTITY(1,1) PRIMARY KEY,
        nama_jabatan VARCHAR(100) NOT NULL UNIQUE,
        deskripsi VARCHAR(255) NULL,
        is_active VARCHAR(10) NOT NULL DEFAULT 'active'
    );
END
", "Jabatan table");

// 4. positions (normalized position reference)
run_sql($conn, "
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='positions' AND xtype='U')
BEGIN
    CREATE TABLE positions (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at DATETIME DEFAULT GETDATE(),
        updated_at DATETIME DEFAULT GETDATE()
    );
END
", "Positions table");

// 5. employee_position_history
run_sql($conn, "
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='employee_position_history' AND xtype='U')
BEGIN
    CREATE TABLE employee_position_history (
        id INT IDENTITY(1,1) PRIMARY KEY,
        employee_id INT NOT NULL,
        position_id INT NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NULL,
        created_at DATETIME DEFAULT GETDATE(),
        FOREIGN KEY (employee_id) REFERENCES employees(id),
        FOREIGN KEY (position_id) REFERENCES positions(id)
    );
END
", "Employee Position History table");

// ==============================
// SEED DATA
// ==============================
echo "\n--- Seeding data ---\n";

// Seed employees
$stmt = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM employees");
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($row['cnt'] == 0) {
    $employees = [
        ['NIP001', 'Lukman Hakim', 'Laki-laki', 'Manager IT', '2020-01-15', '2018-06-01', 'Tetap', 'active'],
        ['NIP002', 'Saiful Anwar', 'Laki-laki', 'Staff Keuangan', '2021-03-10', '2019-08-15', 'Tetap', 'active'],
        ['NIP003', 'Sinta Mei', 'Perempuan', 'Staff HRD', '2022-05-20', '2020-02-01', 'Kontrak', 'active'],
        ['NIP004', 'Tubagus', 'Laki-laki', 'Staff Marketing', '2021-07-01', '2019-11-10', 'Tetap', 'active'],
        ['NIP005', 'Nana M', 'Perempuan', 'Staff Administrasi', '2023-01-05', '2021-04-20', 'Kontrak', 'active'],
    ];
    $insertSql = "INSERT INTO employees (nip, nama, jenis_kelamin, jabatan, tanggal_aktif_jabatan, tanggal_masuk, status_karyawan, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    foreach ($employees as $emp) {
        $stmt = sqlsrv_query($conn, $insertSql, $emp);
        if ($stmt === false) {
            echo "Error inserting employee {$emp[1]}\n";
        } else {
            echo "Inserted employee: {$emp[1]}\n";
            sqlsrv_free_stmt($stmt);
        }
    }
} else {
    echo "Employees already seeded ({$row['cnt']} records).\n";
}

// Seed users
$stmt = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM users");
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($row['cnt'] == 0) {
    $users = [
        ['admin', password_hash('admin123', PASSWORD_DEFAULT), 'administrator', 'active'],
        ['user', password_hash('user123', PASSWORD_DEFAULT), 'user', 'active'],
    ];
    $insertSql = "INSERT INTO users (username, password, role, is_active) VALUES (?, ?, ?, ?)";
    foreach ($users as $usr) {
        $stmt = sqlsrv_query($conn, $insertSql, $usr);
        if ($stmt === false) {
            echo "Error inserting user {$usr[0]}\n";
        } else {
            echo "Inserted user: {$usr[0]}\n";
            sqlsrv_free_stmt($stmt);
        }
    }
} else {
    echo "Users already seeded ({$row['cnt']} records).\n";
}

// Seed jabatan
$stmt = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM jabatan");
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($row['cnt'] == 0) {
    $jabatanList = [
        ['Manager IT', 'Mengelola divisi IT', 'active'],
        ['Staff Keuangan', 'Mengelola keuangan perusahaan', 'active'],
        ['Staff HRD', 'Mengelola sumber daya manusia', 'active'],
        ['Staff Marketing', 'Mengelola pemasaran produk', 'active'],
        ['Staff Administrasi', 'Mengelola administrasi kantor', 'active'],
    ];
    $insertSql = "INSERT INTO jabatan (nama_jabatan, deskripsi, is_active) VALUES (?, ?, ?)";
    foreach ($jabatanList as $jab) {
        $stmt = sqlsrv_query($conn, $insertSql, $jab);
        if ($stmt === false) {
            echo "Error inserting jabatan {$jab[0]}\n";
        } else {
            echo "Inserted jabatan: {$jab[0]}\n";
            sqlsrv_free_stmt($stmt);
        }
    }
} else {
    echo "Jabatan already seeded ({$row['cnt']} records).\n";
}

// Seed positions
$stmt = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM positions");
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($row['cnt'] == 0) {
    $positions = [
        'Manager IT',
        'Staff Keuangan',
        'Staff HRD',
        'Staff Marketing',
        'Staff Administrasi',
        'Supervisor HR',
        'Asisten Manager Keuangan',
        'Sekretaris',
        'Manager Produksi',
        'Admin Produksi',
    ];
    $insertSql = "INSERT INTO positions (name, created_at, updated_at) VALUES (?, GETDATE(), GETDATE())";
    foreach ($positions as $pos) {
        $params = [$pos];
        $stmt = sqlsrv_query($conn, $insertSql, $params);
        if ($stmt === false) {
            echo "Error inserting position $pos\n";
        } else {
            echo "Inserted position: $pos\n";
            sqlsrv_free_stmt($stmt);
        }
    }
} else {
    echo "Positions already seeded ({$row['cnt']} records).\n";
}

// Seed employee_position_history for existing employees
$stmt = sqlsrv_query($conn, "SELECT COUNT(*) AS cnt FROM employee_position_history");
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt);

if ($row['cnt'] == 0) {
    echo "\n--- Generating position history for existing employees ---\n";
    // Get all employees
    $stmt = sqlsrv_query($conn, "SELECT id, jabatan, tanggal_aktif_jabatan FROM employees");
    $empList = [];
    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $empList[] = $r;
    }
    sqlsrv_free_stmt($stmt);

    foreach ($empList as $emp) {
        // Find matching position_id
        $params = [$emp['jabatan']];
        $stmt = sqlsrv_query($conn, "SELECT id FROM positions WHERE name = ?", $params);
        $posRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($stmt);

        if ($posRow) {
            $histParams = [$emp['id'], $posRow['id'], $emp['tanggal_aktif_jabatan']];
            $stmt = sqlsrv_query(
                $conn,
                "INSERT INTO employee_position_history (employee_id, position_id, start_date, end_date, created_at) VALUES (?, ?, ?, NULL, GETDATE())",
                $histParams
            );
            if ($stmt === false) {
                echo "Error creating history for employee ID {$emp['id']}\n";
            } else {
                echo "Created history for employee ID {$emp['id']} → position ID {$posRow['id']}\n";
                sqlsrv_free_stmt($stmt);
            }
        } else {
            echo "Warning: No matching position found for '{$emp['jabatan']}'\n";
        }
    }
} else {
    echo "Position history already seeded ({$row['cnt']} records).\n";
}

sqlsrv_close($conn);
echo "\n=== Database initialization complete! ===\n";
