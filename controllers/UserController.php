<?php
/**
 * UserController
 * Controller untuk mengelola user (Admin only)
 */

require_once __DIR__ . '/../config/config.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Get all users
     */
    public function getAll($role = null) {
        requireRole(ROLE_ADMIN);
        return $this->userModel->getAllUsers($role);
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        requireRole(ROLE_ADMIN);
        return $this->userModel->getUserById($id);
    }

    /**
     * Create user
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireRole(ROLE_ADMIN);

            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? ''
            ];

            // Validate
            if (empty($data['username']) || empty($data['email']) || 
                empty($data['password']) || empty($data['full_name']) || 
                empty($data['role'])) {
                return ['success' => false, 'message' => 'Semua field harus diisi'];
            }

            // Check if username exists
            if ($this->userModel->usernameExists($data['username'])) {
                return ['success' => false, 'message' => 'Username sudah digunakan'];
            }

            // Check if email exists
            if ($this->userModel->emailExists($data['email'])) {
                return ['success' => false, 'message' => 'Email sudah digunakan'];
            }

            $result = $this->userModel->createUser($data);

            if ($result) {
                return ['success' => true, 'message' => 'User berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan user'];
            }
        }
    }

    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireRole(ROLE_ADMIN);

            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'role' => $_POST['role'] ?? ''
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            // Validate
            if (empty($data['username']) || empty($data['email']) || 
                empty($data['full_name']) || empty($data['role'])) {
                return ['success' => false, 'message' => 'Semua field harus diisi'];
            }

            // Check if username exists (exclude current user)
            if ($this->userModel->usernameExists($data['username'], $id)) {
                return ['success' => false, 'message' => 'Username sudah digunakan'];
            }

            // Check if email exists (exclude current user)
            if ($this->userModel->emailExists($data['email'], $id)) {
                return ['success' => false, 'message' => 'Email sudah digunakan'];
            }

            $result = $this->userModel->updateUser($id, $data);

            if ($result) {
                return ['success' => true, 'message' => 'User berhasil diupdate'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate user'];
            }
        }
    }

    /**
     * Delete user
     */
    public function delete($id) {
        requireRole(ROLE_ADMIN);

        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            return ['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri'];
        }

        $result = $this->userModel->deleteUser($id);

        if ($result) {
            return ['success' => true, 'message' => 'User berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Gagal menghapus user'];
        }
    }

    /**
     * Get activity logs
     */
    public function getActivityLogs($limit = 50) {
        requireRole(ROLE_ADMIN);
        return $this->userModel->getActivityLogs($limit);
    }
}

// Handle AJAX requests
if (isset($_POST['action']) || isset($_GET['action'])) {
    $controller = new UserController();
    $action = $_POST['action'] ?? $_GET['action'];
    
    if ($action === 'create') {
        $result = $controller->create();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
    
    if ($action === 'update') {
        $id = $_POST['id'] ?? 0;
        $result = $controller->update($id);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
    
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $result = $controller->delete($id);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}
