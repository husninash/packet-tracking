-- ============================================================================
-- Database: sigap_unhan
-- Sistem Informasi Gerbang Paket - Universitas Pertahanan Republik Indonesia
-- ============================================================================
-- File ini berisi struktur database lengkap dengan data sample
-- Untuk menggunakan file ini:
--   1. Buka phpMyAdmin (http://localhost/phpmyadmin)
--   2. Klik Import
--   3. Pilih file sigap_unhan.sql ini
--   4. Klik Go
-- ============================================================================

CREATE DATABASE IF NOT EXISTS sigap_unhan;
USE sigap_unhan;

-- ============================================================================
-- TABEL USERS (Admin & Petugas)
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'petugas') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- TABEL PROGRAM STUDI
-- ============================================================================
CREATE TABLE IF NOT EXISTS program_studi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- TABEL PACKAGES (Data Paket)
-- ============================================================================
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_penerima VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    prodi_id INT NOT NULL,
    tanggal_datang DATE NOT NULL,
    petugas_penerima VARCHAR(100) NOT NULL,
    foto_paket VARCHAR(255),
    catatan TEXT,
    status ENUM('Belum Diambil', 'Sudah Diambil') DEFAULT 'Belum Diambil',
    tanggal_diambil DATETIME NULL,
    foto_serah_terima VARCHAR(255) NULL,
    petugas_serah_terima INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(id),
    FOREIGN KEY (petugas_serah_terima) REFERENCES users(id),
    INDEX idx_nim (nim),
    INDEX idx_nama_penerima (nama_penerima),
    INDEX idx_status (status),
    INDEX idx_tanggal_datang (tanggal_datang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- TABEL ACTIVITY LOG (Pencatatan Aktivitas Sistem)
-- ============================================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- INISIALISASI DATA
-- ============================================================================

-- Insert Default Users
-- Password default untuk semua user: password
-- Hash bcrypt untuk: password = $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@unhan.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('petugas1', 'petugas1@unhan.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', 'petugas'),
('petugas2', 'petugas2@unhan.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Aminah', 'petugas');

-- Insert Program Studi
INSERT INTO program_studi (nama_prodi) VALUES
('Kedokteran'),
('Farmasi'),
('Biologi'),
('Matematika'),
('Fisika'),
('Kimia'),
('Informatika'),
('Teknik Mesin'),
('Teknik Elektro'),
('Teknik Sipil'),
('RSDA'),
('Sejarah Militer'),
('Ilmu Keolaragaan');

-- Insert Sample Packages
INSERT INTO packages (nama_penerima, nim, prodi_id, tanggal_datang, petugas_penerima, catatan, status, tanggal_diambil, petugas_serah_terima) VALUES
('Reva Ayu', '12345', 1, '2025-10-13', 'Budi Santoso', 'Paket berukuran sedang', 'Sudah Diambil', '2025-12-09 20:26:00', 2),
('Annisa Putri', '12346', 2, '2025-10-12', 'Siti Aminah', 'Paket dari Jakarta', 'Belum Diambil', NULL, NULL),
('Ahmad Fauzi', '12347', 3, '2025-10-13', 'Budi Santoso', 'Paket elektronik', 'Belum Diambil', NULL, NULL),
('Dimas Pratama', '12348', 1, '2025-10-11', 'Siti Aminah', 'Paket buku', 'Sudah Diambil', '2025-12-08 15:30:00', 3);

-- ============================================================================
-- Setup selesai! Database siap digunakan.
-- ============================================================================
