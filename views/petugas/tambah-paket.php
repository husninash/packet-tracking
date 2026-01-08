<?php
/**
 * Tambah Paket - Petugas
 * Form untuk menambah paket baru
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_PETUGAS);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageController = new PackageController();
    $result = $packageController->create();
    
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
    <title>Tambah Paket - SIGAP</title>
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
                <a href="dashboard.php">
                    📊 Data Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php" class="active">
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
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Tambah Paket Baru</h1>
                <p>Input data paket baru yang datang</p>
            </div>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    ❌ <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="card">
                <form method="POST" enctype="multipart/form-data" id="formTambahPaket">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                        <!-- Nama Penerima -->
                        <div class="form-group">
                            <label class="form-label required">Nama Penerima</label>
                            <input 
                                type="text" 
                                name="nama_penerima" 
                                class="form-control" 
                                placeholder="Masukkan nama lengkap penerima"
                                required
                            >
                        </div>

                        <!-- Tanggal Datang -->
                        <div class="form-group">
                            <label class="form-label required">Tanggal Datang</label>
                            <input 
                                type="date" 
                                name="tanggal_datang" 
                                class="form-control" 
                                value="<?= date('Y-m-d') ?>"
                                required
                            >
                        </div>

                        <!-- Petugas Penerima -->
                        <div class="form-group">
                            <label class="form-label required">Petugas Penerima</label>
                            <input 
                                type="text" 
                                name="petugas_penerima" 
                                class="form-control" 
                                placeholder="Nama petugas"
                                value="<?= htmlspecialchars($_SESSION['full_name']) ?>"
                                required
                            >
                        </div>

                        <!-- Foto Paket -->
                        <div class="form-group">
                            <label class="form-label">Foto Paket</label>
                            <label class="form-file-upload">
                                <input 
                                    type="file" 
                                    name="foto_paket" 
                                    id="fotoPaket"
                                    accept="image/*"
                                >
                                <span>📷 Upload Foto (Opsional)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Preview Foto -->
                    <div id="previewContainer" style="display: none; margin-top: 1rem;">
                        <label class="form-label">Preview Foto</label>
                        <img id="previewImage" style="max-width: 300px; border-radius: var(--radius-md); box-shadow: var(--shadow-md);">
                    </div>

                    <!-- Catatan -->
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea 
                            name="catatan" 
                            class="form-control" 
                            placeholder="Catatan tambahan (ukuran paket, kondisi, dll)"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                        <a href="dashboard.php" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="reset" class="btn btn-warning">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            💾 Simpan Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        // Preview foto
        document.getElementById('fotoPaket').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewContainer').style.display = 'none';
            }
        });
    </script>
</body>
</html>
