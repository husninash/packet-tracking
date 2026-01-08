<?php
/**
 * Data Paket - Admin
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$packageModel = new Package();
$packages = $packageModel->getAllPackages();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Paket - SIGAP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>🛡️ SIGAP</h2>
            <p>Dashboard Admin</p>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php">📊 Statistik Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="data-paket.php" class="active">📦 Semua Data Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">➕ Tambah Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="riwayat-paket.php">🕓 Riwayat Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manajemen-petugas.php">👥 Manajemen Petugas</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="log-aktivitas.php">🧾 Log Aktivitas</a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">🔓 Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="page-header">
                <h1>Semua Data Paket</h1>
                <p>Kelola dan pantau semua paket</p>
            </div>

            <div class="card">
                <div class="search-filter-bar">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                    </div>
                    <select id="filterStatus" class="form-control filter-select">
                        <option value="">Semua Status</option>
                        <option value="Belum Diambil">Belum Diambil</option>
                        <option value="Sudah Diambil">Sudah Diambil</option>
                    </select>
                    <button class="btn btn-success" onclick="exportTableToCSV('packageTable', 'data-paket.csv')">
                        📥 Export CSV
                    </button>
                </div>

                <div class="table-container">
                    <table id="packageTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto Paket</th>
                                <th>Nama Penerima</th>
                                <th>Tanggal Datang</th>
                                <th>Petugas</th>
                                <th>Status</th>
                                <th>Bukti Serah Terima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($packages as $pkg): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?php if ($pkg['foto_paket']): ?>
                                            <img src="<?= UPLOAD_URL . $pkg['foto_paket'] ?>" alt="Foto Paket">
                                        <?php else: ?>
                                            📦
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($pkg['nama_penerima']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($pkg['tanggal_datang'])) ?></td>
                                    <td><?= htmlspecialchars($pkg['petugas_penerima']) ?></td>
                                    <td>
                                        <?php if ($pkg['status'] === STATUS_SUDAH_DIAMBIL): ?>
                                            <span class="badge badge-success">🟢 Sudah Diambil</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">🔴 Belum Diambil</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        // Debug
                                        if (!empty($pkg['foto_serah_terima'])) {
                                            $fullUrl = UPLOAD_URL . $pkg['foto_serah_terima'];
                                            echo "<!-- DEBUG: foto_serah_terima = " . htmlspecialchars($pkg['foto_serah_terima']) . " -->";
                                            echo "<!-- DEBUG: UPLOAD_URL = " . UPLOAD_URL . " -->";
                                            echo "<!-- DEBUG: Full URL = " . $fullUrl . " -->";
                                        }
                                        ?>
                                        <?php if (!empty($pkg['foto_serah_terima'])): ?>
                                            <a href="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" target="_blank">
                                                <img src="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" alt="Bukti Serah Terima" style="cursor: pointer; max-width: 50px; max-height: 50px; object-fit: cover; border: 1px solid red;">
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--gray-400);">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="deletePackage(<?= $pkg['id'] ?>)">
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
