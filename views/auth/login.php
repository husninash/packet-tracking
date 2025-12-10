<?php
/**
 * Login Page
 * Halaman login untuk Admin & Petugas
 */

require_once __DIR__ . '/../../config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (hasRole(ROLE_ADMIN)) {
        redirect('views/admin/dashboard.php');
    } else {
        redirect('views/petugas/dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGAP Unhan RI</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 1rem;">
                    🛡️
                </div>
                <h2>SIGAP - Unhan RI</h2>
                <p>Sistem Informasi Gerbang Paket</p>
            </div>

            <div style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <p style="font-size: 0.875rem; color: var(--gray-700); margin: 0;">
                    <strong>Login Petugas</strong>
                </p>
            </div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label class="form-label required">Username / Email</label>
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        placeholder="Masukkan username"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label class="form-label required">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                    Login
                </button>
            </form>

            <div class="login-footer">
                <p style="margin-bottom: 0.5rem;">Login sebagai role lain?</p>
                <a href="#" onclick="toggleRoleInfo(); return false;">Login sebagai Admin</a>
            </div>

            <div id="roleInfo" style="display: none; margin-top: 1rem; padding: 1rem; background: var(--gray-50); border-radius: var(--radius-md);">
                <p style="font-size: 0.875rem; color: var(--gray-700); margin: 0;">
                    <strong>Akun Demo:</strong><br>
                    Admin: <code>admin</code> / <code>password</code><br>
                    Petugas: <code>petugas1</code> / <code>password</code>
                </p>
            </div>

            <div class="login-footer" style="border-top: none; margin-top: 1.5rem;">
                <a href="../../index.php">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        function toggleRoleInfo() {
            const roleInfo = document.getElementById('roleInfo');
            roleInfo.style.display = roleInfo.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
