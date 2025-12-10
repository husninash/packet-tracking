# 🚀 PANDUAN INSTALASI CEPAT - SIGAP

## Langkah 1: Persiapan
1. Pastikan XAMPP sudah terinstall
2. Project sudah ada di: `c:\xampp\htdocs\SistemPaketUnhan`

## Langkah 2: Setup Database
1. Start Apache & MySQL di XAMPP Control Panel
2. Buka phpMyAdmin: http://localhost/phpmyadmin
3. Klik tab "Import"
4. Pilih file: `database/sigap_unhan.sql`
5. Klik "Go"
6. Database `sigap_unhan` akan otomatis terbuat dengan semua tabel dan data sample

## Langkah 3: Cek Konfigurasi
File `config/database.php` sudah dikonfigurasi:
```
DB_HOST: localhost
DB_USER: root
DB_PASS: (kosong)
DB_NAME: sigap_unhan
```

## Langkah 4: Jalankan Aplikasi
Buka browser: http://localhost/SistemPaketUnhan

## 🎯 URL Penting
- **Halaman Public:** http://localhost/SistemPaketUnhan/
- **Login:** http://localhost/SistemPaketUnhan/views/auth/login.php

## 🔑 Akun Login

### Admin
- Username: `admin`
- Password: `password`
- Dashboard: views/admin/dashboard.php

### Petugas 1
- Username: `petugas1`
- Password: `password`
- Dashboard: views/petugas/dashboard.php

### Petugas 2
- Username: `petugas2`
- Password: `password`

## ✅ Testing Fitur

### Test 1: Halaman Public
1. Buka http://localhost/SistemPaketUnhan/
2. Cek papan pengumuman paket
3. Klik "Cek Paket Saya"
4. Input: "Reva Ayu" atau "12345"

### Test 2: Login Petugas
1. Klik "Login" di navbar
2. Username: `petugas1`, Password: `password`
3. Klik menu "Tambah Paket"
4. Isi form dan upload foto
5. Klik "Tandai Sudah Diambil" → Upload foto serah terima

### Test 3: Login Admin
1. Logout dari petugas
2. Login dengan username: `admin`, password: `password`
3. Lihat statistik di dashboard
4. Buka "Manajemen Petugas" → Tambah user baru
5. Cek "Log Aktivitas"

## 🐛 Troubleshooting

### Error: Cannot connect to database
- Cek MySQL running di XAMPP
- Cek database `sigap_unhan` sudah di-import
- Cek kredensial di `config/database.php`

### Error: Upload failed
- Pastikan folder `uploads/packages` dan `uploads/handover` ada
- Set permission folder: 777 (Windows biasanya otomatis)

### Error: Session error
- Restart Apache di XAMPP
- Clear browser cache

### Error: 404 Not Found
- Cek path project: harus di `c:\xampp\htdocs\SistemPaketUnhan`
- Cek Apache running
- Gunakan URL lengkap: http://localhost/SistemPaketUnhan/

## 📸 Fitur Foto Serah Terima
Ketika klik "Tandai Sudah Diambil":
1. Modal akan muncul
2. Upload foto bukti serah terima (WAJIB)
3. Format: JPG, PNG, GIF (Max 5MB)
4. Foto tersimpan di `uploads/handover/`

## 🎨 Tema Hijau Militer
- Warna utama: #2E4D3E (Hijau Militer)
- Font: Poppins
- Style modern dengan shadow & rounded corners

## 📊 Struktur Database
- **users**: Admin & Petugas
- **packages**: Data paket
- **program_studi**: Program studi
- **activity_logs**: Log aktivitas sistem

## ⚙️ Konfigurasi Tambahan (Opsional)

### Ubah Base URL (jika beda port/folder)
Edit `config/config.php`:
```php
define('BASE_URL', 'http://localhost/SistemPaketUnhan/');
```

### Tambah Program Studi
Login sebagai admin, kemudian via phpMyAdmin:
```sql
INSERT INTO program_studi (nama_prodi) VALUES ('Nama Prodi Baru');
```

## 🔒 Keamanan
- Password di-hash dengan bcrypt
- SQL Injection prevention dengan prepared statements
- XSS protection dengan htmlspecialchars
- Session-based authentication
- Role-based access control

## 📝 Catatan
- Default password untuk semua user: `password`
- Ganti password di production!
- Backup database secara berkala

## ❓ Butuh Bantuan?
Cek file `README.md` untuk dokumentasi lengkap.

---
**SIGAP - Sistem Informasi Gerbang Paket**
Universitas Pertahanan Republik Indonesia © 2025
