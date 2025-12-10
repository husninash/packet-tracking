<?php
/**
 * Dashboard Admin
 * Halaman utama untuk admin dengan statistik lengkap
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$packageModel = new Package();
$userModel = new User();

$stats = $packageModel->getStatistics();
$topPetugas = $packageModel->getTopActivePetugas();
$recentLogs = $userModel->getActivityLogs(10);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIGAP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>🛡️ SIGAP</h2>
            <p>Dashboard Admin</p>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php" class="active">
                    📊 Statistik Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="data-paket.php">
                    📦 Semua Data Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">
                    ➕ Tambah Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manajemen-petugas.php">
                    👥 Manajemen Petugas
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="log-aktivitas.php">
                    🧾 Log Aktivitas
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
                <h1>Statistik Paket</h1>
                <p>Overview dan analisis data paket</p>
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

            <!-- Charts & Activity Grid -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; margin-top: 2rem;">
                <!-- Aktivitas Petugas -->
                <div class="card">
                    <div class="card-header">Aktivitas Petugas</div>
                    <div style="padding: 0;">
                        <?php if (empty($topPetugas)): ?>
                            <p style="padding: 2rem; text-align: center; color: var(--gray-500);">
                                Belum ada data aktivitas
                            </p>
                        <?php else: ?>
                            <?php foreach ($topPetugas as $index => $petugas): ?>
                                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                            <?= $index + 1 ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 600;"><?= htmlspecialchars($petugas['petugas_penerima']) ?></div>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">
                                            <?= $petugas['total_paket'] ?>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--gray-500);">paket</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Log Aktivitas Terbaru -->
                <div class="card">
                    <div class="card-header flex-between">
                        <span>Log Aktivitas</span>
                        <a href="log-aktivitas.php" style="font-size: 0.875rem; color: var(--primary-color);">
                            Lihat Semua →
                        </a>
                    </div>
                    <div style="padding: 0;">
                        <?php if (empty($recentLogs)): ?>
                            <p style="padding: 2rem; text-align: center; color: var(--gray-500);">
                                Belum ada log aktivitas
                            </p>
                        <?php else: ?>
                            <?php foreach ($recentLogs as $log): ?>
                                <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-200);">
                                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                                        <div style="width: 8px; height: 8px; background: var(--success-color); border-radius: 50%; margin-top: 0.5rem;"></div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 500; margin-bottom: 0.25rem;">
                                                <?= htmlspecialchars($log['description']) ?>
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                <?= htmlspecialchars($log['full_name'] ?? 'System') ?> • 
                                                <?= date('d M Y, H:i', strtotime($log['created_at'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card" style="margin-top: 2rem;">
                <div class="card-header">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    <a href="data-paket.php" class="btn btn-primary" style="padding: 1.5rem; flex-direction: column; text-align: center;">
                        <span style="font-size: 2rem; margin-bottom: 0.5rem;">📦</span>
                        <span>Lihat Semua Paket</span>
                    </a>
                    <a href="manajemen-petugas.php" class="btn btn-info" style="padding: 1.5rem; flex-direction: column; text-align: center;">
                        <span style="font-size: 2rem; margin-bottom: 0.5rem;">👥</span>
                        <span>Kelola Petugas</span>
                    </a>
                    <a href="log-aktivitas.php" class="btn btn-warning" style="padding: 1.5rem; flex-direction: column; text-align: center;">
                        <span style="font-size: 2rem; margin-bottom: 0.5rem;">🧾</span>
                        <span>Log Aktivitas</span>
                    </a>
                    <a href="../../index.php" target="_blank" class="btn btn-success" style="padding: 1.5rem; flex-direction: column; text-align: center;">
                        <span style="font-size: 2rem; margin-bottom: 0.5rem;">🌐</span>
                        <span>Lihat Halaman Public</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
