# SIGAP - Sistem Informasi Gerbang Paket
### Universitas Pertahanan Republik Indonesia

## 📦 Deskripsi
SIGAP adalah sistem informasi berbasis web untuk monitoring dan manajemen paket yang masuk di pos penjagaan Universitas Pertahanan RI. Sistem ini memudahkan petugas untuk mencatat paket masuk dan memudahkan mahasiswa untuk mengecek status paket mereka.

## ✨ Fitur Utama

### Halaman Public
- ✅ Papan pengumuman paket yang belum diambil
- ✅ Pencarian paket berdasarkan nama atau NIM
- ✅ Filter berdasarkan program studi
- ✅ Informasi lengkap status paket

### Dashboard Petugas
- ✅ Tambah data paket baru dengan foto
- ✅ Tandai paket sudah diambil dengan upload bukti foto serah terima
- ✅ Edit dan hapus data paket
- ✅ Riwayat pengambilan paket
- ✅ Statistik paket

### Dashboard Admin
- ✅ Statistik lengkap paket (total, sudah diambil, belum diambil)
- ✅ Aktivitas petugas
- ✅ Manajemen user (Admin & Petugas)
- ✅ Log aktivitas sistem
- ✅ Export data ke CSV

## 🛠️ Teknologi
- **Backend:** PHP 7.4+ dengan OOP (Object-Oriented Programming)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla JS)
- **Design:** Custom CSS dengan tema hijau militer

## 📋 Instalasi

### 1. Persiapan
- Install XAMPP (PHP 7.4+ dan MySQL)
- Clone atau download project ini ke folder `c:\xampp\htdocs\SistemPaketUnhan`

### 2. Setup Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Klik tab "Import"
3. Pilih file `database/sigap_unhan.sql`
4. Klik "Go"
5. Database `sigap_unhan` akan otomatis terbuat beserta semua tabel dan data sample

### 3. Konfigurasi
Edit file `config/database.php` jika diperlukan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sigap_unhan');
```

### 4. Jalankan Aplikasi
1. Start Apache dan MySQL di XAMPP Control Panel
2. Buka browser: `http://localhost/SistemPaketUnhan`

## 👤 Akun Default

### Admin
- Username: `admin`
- Password: `password`

### Petugas
- Username: `petugas1`
- Password: `password`

## 📁 Struktur Folder
```
SistemPaketUnhan/
├── assets/
│   ├── css/
│   │   └── style.css          # Styling dengan tema hijau militer
│   └── js/
│       └── main.js            # JavaScript untuk interaktivitas
├── config/
│   ├── config.php             # Konfigurasi umum
│   └── database.php           # Koneksi database
├── controllers/
│   ├── AuthController.php     # Controller autentikasi
│   ├── PackageController.php  # Controller paket
│   └── UserController.php     # Controller user
├── database/
│   └── sigap_unhan.sql        # SQL database
├── models/
│   ├── Package.php            # Model paket (OOP)
│   ├── ProgramStudi.php       # Model program studi (OOP)
│   └── User.php               # Model user (OOP)
├── uploads/
│   ├── packages/              # Folder foto paket
│   └── handover/              # Folder foto serah terima
├── views/
│   ├── admin/                 # Halaman admin
│   │   ├── dashboard.php
│   │   ├── data-paket.php
│   │   ├── manajemen-petugas.php
│   │   └── log-aktivitas.php
│   ├── auth/
│   │   └── login.php          # Halaman login
│   └── petugas/               # Halaman petugas
│       ├── dashboard.php
│       ├── tambah-paket.php
│       └── riwayat.php
└── index.php                  # Halaman beranda public
```

## 🎨 Tema & Design
- **Warna Utama:** Hijau Militer (#2E4D3E)
- **Font:** Poppins
- **Style:** Modern, clean, dengan shadow dan rounded corners
- **Responsive:** Ya (mobile-friendly)

## 🔒 Keamanan
- ✅ Password di-hash menggunakan `password_hash()` PHP
- ✅ Prepared statements untuk mencegah SQL Injection
- ✅ Session-based authentication
- ✅ Role-based access control (Admin & Petugas)
- ✅ File upload validation (type & size)
- ✅ XSS protection dengan `htmlspecialchars()`

## 📸 Fitur Upload Foto
- **Foto Paket:** Saat menambah paket baru (opsional)
- **Foto Serah Terima:** WAJIB saat menandai paket sudah diambil
- **Format:** JPG, JPEG, PNG, GIF
- **Maksimal:** 5MB per file

## 🚀 Penggunaan

### Untuk Mahasiswa/Umum:
1. Buka halaman beranda
2. Lihat papan pengumuman paket
3. Gunakan fitur "Cek Paket Saya" untuk mencari paket

### Untuk Petugas:
1. Login sebagai petugas
2. Tambah paket baru yang masuk
3. Tandai paket sudah diambil dengan upload foto bukti serah terima

### Untuk Admin:
1. Login sebagai admin
2. Pantau statistik dan aktivitas
3. Kelola akun petugas
4. Export data untuk laporan

## 📝 Catatan Penting
- Paket yang tidak diambil dalam 7 hari akan dikembalikan (informasi)
- Pengambilan paket harus dengan menunjukkan KTM
- Bukti foto serah terima wajib diupload untuk dokumentasi

## 🐛 Troubleshooting
- **Error database:** Pastikan MySQL running dan database sudah di-import
- **Upload error:** Pastikan folder `uploads/` memiliki permission write (777)
- **Session error:** Pastikan `session_start()` berjalan (cek php.ini)

## 👨‍💻 Developer
Sistem ini dibuat dengan pendekatan OOP (Object-Oriented Programming) untuk kemudahan maintenance dan pengembangan.

## 📄 License
© 2025 Universitas Pertahanan Republik Indonesia
