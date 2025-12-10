<?php
/**
 * Manajemen Petugas - Admin
 * Halaman untuk mengelola user petugas
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$userModel = new User();
$users = $userModel->getAllUsers();

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $result = $userController->update($_POST['id']);
    } else {
        $result = $userController->create();
    }
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
        redirect('views/admin/manajemen-petugas.php');
    } else {
        $error_message = $result['message'];
    }
}

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Petugas - SIGAP</title>
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
                <a href="tambah-paket.php">
                    ➕ Tambah Paket
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manajemen-petugas.php" class="active">
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
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Manajemen Petugas</h1>
                <p>Kelola akun admin dan petugas</p>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    ✅ <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    ❌ <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header flex-between">
                    <span>Daftar User</span>
                    <button class="btn btn-primary" onclick="openModal('modalUser'); resetForm();">
                        ➕ Tambah User
                    </button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['role'] === ROLE_ADMIN ? 'badge-danger' : 'badge-info' ?>">
                                            <?= strtoupper($user['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button 
                                                class="btn btn-sm btn-info" 
                                                onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)"
                                                title="Edit"
                                            >
                                                ✏️
                                            </button>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="deleteUser(<?= $user['id'] ?>)"
                                                    title="Hapus"
                                                >
                                                    🗑️
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal User -->
    <div id="modalUser" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah User Baru</h3>
                <button class="modal-close" onclick="closeModal('modalUser')">&times;</button>
            </div>
            <form method="POST" id="formUser">
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId">

                    <div class="form-group">
                        <label class="form-label required">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username"
                            class="form-control" 
                            placeholder="Username"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-control" 
                            placeholder="email@unhan.ac.id"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            id="full_name"
                            class="form-control" 
                            placeholder="Nama lengkap"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" id="passwordLabel">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control" 
                            placeholder="Minimal 6 karakter"
                        >
                        <small style="color: var(--gray-500); font-size: 0.75rem;" id="passwordHelp">
                            Kosongkan jika tidak ingin mengubah password
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalUser')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function resetForm() {
            document.getElementById('formUser').reset();
            document.getElementById('userId').value = '';
            document.getElementById('modalTitle').textContent = 'Tambah User Baru';
            document.getElementById('passwordLabel').innerHTML = 'Password <span style="color: var(--danger-color);">*</span>';
            document.getElementById('password').required = true;
            document.getElementById('passwordHelp').style.display = 'none';
        }

        function editUser(user) {
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('email').value = user.email;
            document.getElementById('full_name').value = user.full_name;
            document.getElementById('role').value = user.role;
            document.getElementById('password').value = '';
            
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('passwordLabel').textContent = 'Password';
            document.getElementById('password').required = false;
            document.getElementById('passwordHelp').style.display = 'block';
            
            openModal('modalUser');
        }
    </script>
</body>
</html>
