-- Update Program Studi
-- Jalankan query ini di phpMyAdmin untuk update prodi yang sudah ada

USE sigap_unhan;

-- Nonaktifkan foreign key check sementara
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus prodi lama
DELETE FROM program_studi;

-- Reset auto increment
ALTER TABLE program_studi AUTO_INCREMENT = 1;

-- Insert prodi baru
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

-- Aktifkan kembali foreign key check
SET FOREIGN_KEY_CHECKS = 1;

-- Update prodi_id di packages ke Informatika (id=7) untuk semua paket yang ada
UPDATE packages SET prodi_id = 7 WHERE prodi_id NOT IN (1,2,3,4,5,6,7,8,9,10,11,12,13);
