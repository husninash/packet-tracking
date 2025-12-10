<?php
/**
 * Dashboard Petugas
 * Halaman utama untuk petugas paket
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_PETUGAS);

$packageModel = new Package();
$prodiModel = new ProgramStudi();

$stats = $packageModel->getStatistics();
$packages = $packageModel->getAllPackages();
$allProdi = $prodiModel->getAllProdi();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - SIGAP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>📦 SIGAP</h2>
            <p>Dashboard Petugas</p>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="active">
                    📊 Data Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">
                    ➕ Tambah Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="riwayat.php">
                    🕓 Riwayat Pengambilan
                </a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">
                    🔓 Logout
                </a>
            </li>
        </ul>
        <div style="padding: 1rem; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); font-size: 0.75rem;">
            <p>Login sebagai:</p>
            <p style="font-weight: 600; color: white;"><?= htmlspecialchars($_SESSION['full_name']) ?></p>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Data Paket</h1>
                <p>Kelola dan perbarui data paket yang masuk</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-icon blue">📦</div>
                    <div class="stat-card-title">Total Paket</div>
                    <div class="stat-card-value"><?= $stats['total_bulan_ini'] ?></div>
                    <div class="stat-card-subtitle">Bulan ini</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon green">✅</div>
                    <div class="stat-card-title">Sudah Diambil</div>
                    <div class="stat-card-value"><?= $stats['sudah_diambil'] ?></div>
                    <div class="stat-card-subtitle"><?= $stats['persentase_sudah_diambil'] ?>% dari total</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-icon red">⏳</div>
                    <div class="stat-card-title">Belum Diambil</div>
                    <div class="stat-card-value"><?= $stats['belum_diambil'] ?></div>
                    <div class="stat-card-subtitle"><?= $stats['persentase_belum_diambil'] ?>% dari total</div>
                </div>
            </div>

            <!-- Data Paket Card -->
            <div class="card">
                <div class="card-header flex-between">
                    <div>
                        <h2 style="margin: 0;">Data Paket</h2>
                        <p style="font-size: 0.875rem; color: var(--gray-500); margin: 0.25rem 0 0 0;">
                            Daftar semua paket yang terdaftar
                        </p>
                    </div>
                    <a href="tambah-paket.php" class="btn btn-primary">
                        ➕ Tambah Paket Baru
                    </a>
                </div>

                <!-- Search & Filter -->
                <div class="search-filter-bar">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input 
                            type="text" 
                            id="searchInput" 
                            class="form-control" 
                            placeholder="Cari nama penerima, NIM, atau prodi..."
                        >
                    </div>
                    <select id="filterStatus" class="form-control filter-select">
                        <option value="">Semua Status</option>
                        <option value="Belum Diambil">Belum Diambil</option>
                        <option value="Sudah Diambil">Sudah Diambil</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table id="packageTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Penerima</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($packages)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem;">
                                        Belum ada data paket
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
                                                📦
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($package['nama_penerima']) ?></strong></td>
                                        <td><?= htmlspecialchars($package['nim']) ?></td>
                                        <td><?= htmlspecialchars($package['nama_prodi']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($package['tanggal_datang'])) ?></td>
                                        <td>
                                            <?php if ($package['status'] === STATUS_SUDAH_DIAMBIL): ?>
                                                <span class="badge badge-success">🟢 Sudah Diambil</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">🔴 Belum Diambil</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <?php if ($package['status'] === STATUS_BELUM_DIAMBIL): ?>
                                                    <button 
                                                        class="btn btn-sm btn-success" 
                                                        onclick="markAsPickedUp(<?= $package['id'] ?>)"
                                                        title="Tandai Sudah Diambil"
                                                    >
                                                        ✅
                                                    </button>
                                                <?php endif; ?>
                                                <a 
                                                    href="edit-paket.php?id=<?= $package['id'] ?>" 
                                                    class="btn btn-sm btn-info"
                                                    title="Edit"
                                                >
                                                    ✏️
                                                </a>
                                                <button 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="deletePackage(<?= $package['id'] ?>)"
                                                    title="Hapus"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Serah Terima -->
    <div id="modalSerahTerima" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Upload Foto Serah Terima</h3>
                <button class="modal-close" onclick="closeModal('modalSerahTerima')">&times;</button>
            </div>
            <form id="formSerahTerima" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="packageIdSerahTerima" name="package_id">
                    
                    <div class="alert alert-info">
                        📸 Silakan upload foto kegiatan serah terima paket sebagai bukti
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Foto Serah Terima</label>
                        <label class="form-file-upload">
                            <input 
                                type="file" 
                                id="fotoSerahTerima" 
                                name="foto_serah_terima" 
                                accept="image/*"
                                required
                            >
                            <span>📷 Klik untuk upload foto</span>
                        </label>
                    </div>

                    <div id="previewContainer" style="display: none; margin-top: 1rem;">
                        <img id="previewImage" style="max-width: 100%; border-radius: var(--radius-md);">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalSerahTerima')">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitSerahTerima()">
                        ✅ Simpan & Tandai Sudah Diambil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        // Preview foto serah terima
        document.getElementById('fotoSerahTerima').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
