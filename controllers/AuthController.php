<?php
/**
 * AuthController
 * Controller untuk menangani autentikasi
 */

require_once __DIR__ . '/../config/config.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Handle login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => 'Username dan password harus diisi'];
            }

            if ($this->userModel->login($username, $password)) {
                $role = $_SESSION['role'];
                
                if ($role === ROLE_ADMIN) {
                    return ['success' => true, 'redirect' => 'views/admin/dashboard.php'];
                } else {
                    return ['success' => true, 'redirect' => 'views/petugas/dashboard.php'];
                }
            } else {
                return ['success' => false, 'message' => 'Username atau password salah'];
            }
        }
    }

    /**
     * Handle logout
     */
    public function logout() {
        $this->userModel->logout();
        return ['success' => true, 'redirect' => 'index.php'];
    }
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    $controller = new AuthController();
    
    if ($_POST['action'] === 'login') {
        $result = $controller->login();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $controller = new AuthController();
    $result = $controller->logout();
    redirect('index.php');
}
