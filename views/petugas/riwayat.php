<?php
/**
 * Riwayat Pengambilan Paket - Petugas
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_PETUGAS);

$packageModel = new Package();
$packages = $packageModel->getAllPackages(['status' => STATUS_SUDAH_DIAMBIL]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengambilan - SIGAP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>📦 SIGAP</h2>
            <p>Dashboard Petugas</p>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php">📊 Data Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">➕ Tambah Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="riwayat.php" class="active">🕓 Riwayat Pengambilan</a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">🔓 Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="page-header">
                <h1>Riwayat Pengambilan</h1>
                <p>Paket yang sudah diambil</p>
            </div>

            <div class="card">
                <div class="search-filter-bar">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                    </div>
                </div>

                <div class="table-container">
                    <table id="packageTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penerima</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Tanggal Datang</th>
                                <th>Tanggal Diambil</th>
                                <th>Bukti Serah Terima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($packages as $pkg): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($pkg['nama_penerima']) ?></td>
                                    <td><?= htmlspecialchars($pkg['nim']) ?></td>
                                    <td><?= htmlspecialchars($pkg['nama_prodi']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($pkg['tanggal_datang'])) ?></td>
                                    <td><?= $pkg['tanggal_diambil'] ? date('d-m-Y H:i', strtotime($pkg['tanggal_diambil'])) : '-' ?></td>
                                    <td>
                                        <?php if (!empty($pkg['foto_serah_terima'])): ?>
                                            <a href="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" target="_blank">
                                                <img src="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" alt="Bukti" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer;">
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
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
