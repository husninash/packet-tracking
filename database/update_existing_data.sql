-- Update existing data untuk menambahkan info serah terima
-- Jalankan query ini di phpMyAdmin jika sudah punya data lama

USE sigap_unhan;

-- Update paket Reva Ayu yang sudah diambil
UPDATE packages 
SET 
    status = 'Sudah Diambil',
    tanggal_diambil = '2025-12-09 20:26:00',
    petugas_serah_terima = 2
WHERE nama_penerima = 'Reva Ayu' AND nim = '12345';

-- Update paket Annisa Putri ke belum diambil
UPDATE packages 
SET 
    status = 'Belum Diambil',
    tanggal_diambil = NULL,
    petugas_serah_terima = NULL,
    foto_serah_terima = NULL
WHERE nama_penerima = 'Annisa Putri' AND nim = '12346';

-- Update paket Dimas Pratama yang sudah diambil
UPDATE packages 
SET 
    status = 'Sudah Diambil',
    tanggal_diambil = '2025-12-08 15:30:00',
    petugas_serah_terima = 3
WHERE nama_penerima = 'Dimas Pratama' AND nim = '12348';

-- Reset foto_serah_terima untuk paket yang belum ada fotonya
-- (karena foto lama mungkin rusak/tidak valid)
UPDATE packages 
SET foto_serah_terima = NULL 
WHERE foto_serah_terima IS NOT NULL 
AND foto_serah_terima NOT LIKE 'handover/%';
