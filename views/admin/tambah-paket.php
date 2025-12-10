<?php
/**
 * Tambah Paket - Admin
 * Form untuk menambah paket baru oleh admin
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$prodiModel = new ProgramStudi();
$allProdi = $prodiModel->getAllProdi();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageController = new PackageController();
    $result = $packageController->create();
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
        redirect('views/admin/data-paket.php');
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
    <title>Tambah Paket - SIGAP Admin</title>
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
                <a href="dashboard.php">
                    📊 Statistik Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="data-paket.php">
                    📦 Semua Data Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php" class="active">
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
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>

                        <!-- Program Studi -->
                        <div class="form-group">
                            <label class="form-label required">Program Studi</label>
                            <select name="prodi_id" class="form-control" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php foreach ($allProdi as $prodi): ?>
                                    <option value="<?= $prodi['id'] ?>">
                                        <?= htmlspecialchars($prodi['nama_prodi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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

                        <!-- Pengirim -->
                        <div class="form-group">
                            <label class="form-label required">Pengirim</label>
                            <input 
                                type="text" 
                                name="petugas_penerima" 
                                class="form-control" 
                                placeholder="Nama pengirim (JNE, J&T, dll)"
                                required
                            >
                        </div>

                        <!-- Foto Paket -->
                        <div class="form-group">
                            <label class="form-label">Foto Paket (Opsional)</label>
                            <input 
                                type="file" 
                                name="foto_paket" 
                                class="form-control" 
                                accept="image/jpeg,image/png,image/gif"
                            >
                            <small class="form-text">Format: JPG, PNG, GIF. Max 5MB</small>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Keterangan</label>
                        <textarea 
                            name="catatan" 
                            class="form-control" 
                            rows="3" 
                            placeholder="Catatan tambahan tentang paket (opsional)"
                        ></textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="data-paket.php" class="btn btn-secondary">
                            ← Kembali
                        </a>
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
        // Preview foto paket
        document.querySelector('input[name="foto_paket"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB!');
                    this.value = '';
                    return;
                }
                
                // Validasi tipe
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file harus JPG, PNG, atau GIF!');
                    this.value = '';
                    return;
                }
            }
        });

        // Form validation
        document.getElementById('formTambahPaket').addEventListener('submit', function(e) {
            const nama = this.querySelector('[name="nama_penerima"]').value.trim();
            const nim = this.querySelector('[name="nim"]').value.trim();
            
            if (nama.length < 3) {
                e.preventDefault();
                alert('Nama penerima minimal 3 karakter!');
                return;
            }
            
            if (nim.length < 5) {
                e.preventDefault();
                alert('NIM minimal 5 karakter!');
                return;
            }
        });
    </script>
</body>
</html>
