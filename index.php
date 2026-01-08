<?php
/**
 * Index Page - Halaman Beranda Public
 * SIGAP - Sistem Informasi Gerbang Paket Unhan RI
 */

require_once __DIR__ . '/config/config.php';

$packageModel = new Package();

// Get all packages for public view
$packages = $packageModel->getAllPackages(['status' => STATUS_BELUM_DIAMBIL]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP - Sistem Informasi Gerbang Paket Unhan RI</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <div class="navbar-brand-icon">
                    📦
                </div>
                <div>
                    <div style="font-size: 1.5rem; font-weight: 600;">SIGAP</div>
                    <div class="navbar-subtitle">Sistem Informasi Gerbang Paket</div>
                </div>
            </div>
            <ul class="navbar-menu">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="views/auth/login.php" class="btn-login">Login</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di SIGAP</h1>
            <p>Universitas Pertahanan Republik Indonesia</p>
            <p style="margin-top: 1rem; font-size: 1rem;">
                Silakan ambil paket Anda di pos penjagaan utama dengan menunjukkan KTM (Kartu Tanda Mahasiswa).
                <br>Paket yang tidak diambil dalam 7 hari akan dikembalikan.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container" style="padding: 2rem 1rem;">
        <!-- Info Alert -->
        <div class="alert alert-info" style="margin-bottom: 2rem;">
            <span>
                ℹ️ <strong>Informasi:</strong> Silakan ambil paket Anda di pos penjagaan utama dengan menunjukkan KTM. 
                Paket yang tidak diambil dalam 7 hari akan dikembalikan.
            </span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1>Papan Pengumuman Paket</h1>
            <p>Daftar paket yang belum diambil</p>
        </div>

        <!-- Search & Filter -->
        <div class="search-filter-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input 
                    type="text" 
                    id="searchInput" 
                    class="form-control" 
                    placeholder="Cari nama penerima..."
                >
            </div>
            <button class="btn btn-primary" onclick="openModal('modalCekPaket')">
                🔍 Cek Paket Saya
            </button>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table id="packageTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto Paket</th>
                        <th>Nama Penerima</th>
                        <th>Tanggal Datang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($packages)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                Tidak ada paket yang belum diambil
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($packages as $package): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php if ($package['foto_paket']): ?>
                                        <img src="<?= UPLOAD_URL . $package['foto_paket'] ?>" alt="Foto Paket">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: var(--gray-200); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-md);">
                                            📦
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= htmlspecialchars($package['nama_penerima']) ?></strong></td>
                                <td><?= date('d M Y', strtotime($package['tanggal_datang'])) ?></td>
                                <td>
                                    <span class="badge badge-danger">
                                        🔴 <?= htmlspecialchars($package['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- About Section -->
        <div id="tentang" class="card" style="margin-top: 3rem;">
            <div class="card-header">Tentang SIGAP</div>
            <div class="card-body">
                <p>
                    <strong>SIGAP (Sistem Informasi Gerbang Paket)</strong> adalah sistem monitoring paket 
                    di pos penjagaan Universitas Pertahanan Republik Indonesia. Sistem ini memudahkan 
                    mahasiswa, dosen, dan civitas akademika untuk mengetahui status paket yang diterima.
                </p>
                <br>
                <p>
                    Untuk informasi lebih lanjut, silakan hubungi pos penjagaan utama atau petugas paket.
                </p>
            </div>
        </div>
    </main>

    <!-- Modal Cek Paket -->
    <div id="modalCekPaket" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cek Paket Saya</h3>
                <button class="modal-close" onclick="closeModal('modalCekPaket')">&times;</button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 1.5rem; color: var(--gray-600);">
                    Masukkan nama lengkap atau NIM Anda untuk mencari paket
                </p>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap atau NIM</label>
                    <input 
                        type="text" 
                        id="cekPaketInput" 
                        class="form-control" 
                        placeholder="Contoh: Reva Ayu atau 12345"
                    >
                </div>
                <div id="hasilCekPaket" style="margin-top: 1.5rem;"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modalCekPaket')">Tutup</button>
                <button class="btn btn-primary" onclick="cekPaket()">Cari Paket</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: var(--gray-900); color: var(--white); text-align: center; padding: 2rem; margin-top: 3rem;">
        <p>&copy; <?= date('Y') ?> Universitas Pertahanan Republik Indonesia. All rights reserved.</p>
        <p style="font-size: 0.875rem; opacity: 0.8; margin-top: 0.5rem;">SIGAP - Sistem Informasi Gerbang Paket</p>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        async function cekPaket() {
            const input = document.getElementById('cekPaketInput').value.trim();
            const hasil = document.getElementById('hasilCekPaket');
            
            if (!input) {
                showAlert('Mohon masukkan nama atau NIM', 'warning');
                return;
            }
            
            hasil.innerHTML = '<div class="spinner"></div>';
            
            try {
                const response = await fetch(`controllers/PackageController.php?action=getAll&search=${encodeURIComponent(input)}`);
                const packages = await response.json();
                
                if (packages && packages.length > 0) {
                    let html = '<div class="alert alert-success">Paket ditemukan!</div>';
                    packages.forEach(pkg => {
                        const statusClass = pkg.status === 'Sudah Diambil' ? 'badge-success' : 'badge-danger';
                        const statusIcon = pkg.status === 'Sudah Diambil' ? '🟢' : '🔴';
                        
                        html += `
                            <div class="card" style="margin-top: 1rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div>
                                        <h4 style="margin-bottom: 0.5rem;">${pkg.nama_penerima}</h4>
                                        <p style="color: var(--gray-600); margin-bottom: 0.5rem;">
                                            NIM: ${pkg.nim} | ${pkg.nama_prodi}
                                        </p>
                                        <p style="font-size: 0.875rem; color: var(--gray-500);">
                                            Tanggal Datang: ${formatDate(pkg.tanggal_datang)}
                                        </p>
                                        ${pkg.catatan ? `<p style="margin-top: 0.5rem; font-size: 0.875rem;"><em>${pkg.catatan}</em></p>` : ''}
                                    </div>
                                    <span class="badge ${statusClass}">
                                        ${statusIcon} ${pkg.status}
                                    </span>
                                </div>
                            </div>
                        `;
                    });
                    hasil.innerHTML = html;
                } else {
                    hasil.innerHTML = '<div class="alert alert-warning">Tidak ada paket ditemukan untuk nama/NIM tersebut</div>';
                }
            } catch (error) {
                hasil.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mencari paket</div>';
            }
        }
        
        // Allow Enter key to trigger search
        document.getElementById('cekPaketInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                cekPaket();
            }
        });
    </script>
</body>
</html>
