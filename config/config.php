<?php
/**
 * General Configuration
 * Konfigurasi umum aplikasi SIGAP
 */

// Start session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Base URL
define('BASE_URL', 'http://localhost/SistemPaketUnhan/');

// Path
define('ROOT_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', ROOT_PATH . 'uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Create upload directories if not exist
if (!file_exists(UPLOAD_PATH . 'packages')) {
    mkdir(UPLOAD_PATH . 'packages', 0777, true);
}
if (!file_exists(UPLOAD_PATH . 'handover')) {
    mkdir(UPLOAD_PATH . 'handover', 0777, true);
}

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_PETUGAS', 'petugas');

// Package Status
define('STATUS_BELUM_DIAMBIL', 'Belum Diambil');
define('STATUS_SUDAH_DIAMBIL', 'Sudah Diambil');

// Helper function untuk redirect
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Helper function untuk cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper function untuk cek role
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Helper function untuk require login
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('views/auth/login.php');
    }
}

// Helper function untuk require role
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        redirect('index.php');
    }
}

// Autoload classes
spl_autoload_register(function ($class_name) {
    $directories = [
        ROOT_PATH . 'models/',
        ROOT_PATH . 'controllers/',
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Include database
require_once ROOT_PATH . 'config/database.php';
