<?php
/**
 * Log Aktivitas - Admin
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$userModel = new User();
$logs = $userModel->getActivityLogs(100);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - SIGAP</title>
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
                <a href="data-paket.php">📦 Semua Data Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">➕ Tambah Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manajemen-petugas.php">👥 Manajemen Petugas</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="log-aktivitas.php" class="active">🧾 Log Aktivitas</a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">🔓 Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="page-header">
                <h1>Log Aktivitas</h1>
                <p>Riwayat aktivitas sistem</p>
            </div>

            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($log['full_name'] ?? 'System') ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td><?= htmlspecialchars($log['description']) ?></td>
                                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                                    <td><?= date('d M Y, H:i:s', strtotime($log['created_at'])) ?></td>
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
