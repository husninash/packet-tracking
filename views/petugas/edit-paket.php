<?php
/**
 * Edit Paket - Petugas
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_PETUGAS);

$packageModel = new Package();
$prodiModel = new ProgramStudi();

$id = $_GET['id'] ?? 0;
$package = $packageModel->getPackageById($id);

if (!$package) {
    redirect('views/petugas/dashboard.php');
}

$allProdi = $prodiModel->getAllProdi();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageController = new PackageController();
    $result = $packageController->update($id);
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
        redirect('views/petugas/dashboard.php');
    } else {
        $error_message = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket - SIGAP</title>
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
                <a href="dashboard.php" class="active">📊 Data Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">➕ Tambah Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="riwayat.php">🕓 Riwayat Pengambilan</a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">🔓 Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="page-header">
                <h1>Edit Paket</h1>
                <p>Perbarui data paket</p>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">❌ <?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label required">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control" value="<?= htmlspecialchars($package['nama_penerima']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">NIM</label>
                            <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($package['nim']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Program Studi</label>
                            <select name="prodi_id" class="form-control" required>
                                <?php foreach ($allProdi as $prodi): ?>
                                    <option value="<?= $prodi['id'] ?>" <?= $prodi['id'] == $package['prodi_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($prodi['nama_prodi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Tanggal Datang</label>
                            <input type="date" name="tanggal_datang" class="form-control" value="<?= $package['tanggal_datang'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Petugas Penerima</label>
                            <input type="text" name="petugas_penerima" class="form-control" value="<?= htmlspecialchars($package['petugas_penerima']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Foto Paket Baru</label>
                            <label class="form-file-upload">
                                <input type="file" name="foto_paket" id="fotoPaket" accept="image/*">
                                <span>📷 Upload Foto Baru (Opsional)</span>
                            </label>
                        </div>
                    </div>

                    <?php if ($package['foto_paket']): ?>
                        <div style="margin-top: 1rem;">
                            <label class="form-label">Foto Saat Ini</label><br>
                            <img src="<?= UPLOAD_URL . $package['foto_paket'] ?>" style="max-width: 300px; border-radius: var(--radius-md);">
                        </div>
                    <?php endif; ?>

                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3"><?= htmlspecialchars($package['catatan'] ?? '') ?></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">💾 Update Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
