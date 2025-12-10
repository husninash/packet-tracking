<?php
/**
 * User Model
 * Model untuk mengelola data user (Admin & Petugas)
 */

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Login user
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $user = $this->db->fetchOne($sql, [$username, $username]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Log activity
            $this->logActivity($user['id'], 'Login', 'User berhasil login ke sistem');
            
            return true;
        }

        return false;
    }

    /**
     * Logout user
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'Logout', 'User logout dari sistem');
        }
        
        session_destroy();
        return true;
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $sql = "SELECT id, username, email, full_name, role, created_at FROM users WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Get all users
     */
    public function getAllUsers($role = null) {
        if ($role) {
            $sql = "SELECT id, username, email, full_name, role, created_at FROM users WHERE role = ? ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, [$role]);
        } else {
            $sql = "SELECT id, username, email, full_name, role, created_at FROM users ORDER BY created_at DESC";
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Create new user
     */
    public function createUser($data) {
        $sql = "INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)";
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        try {
            $userId = $this->db->insert($sql, [
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['full_name'],
                $data['role']
            ]);
            
            if ($userId && isset($_SESSION['user_id'])) {
                $this->logActivity($_SESSION['user_id'], 'Create User', 'Membuat user baru: ' . $data['username']);
            }
            
            return $userId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update user
     */
    public function updateUser($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, full_name = ?, role = ? WHERE id = ?";
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $params = [
                $data['username'],
                $data['email'],
                $hashedPassword,
                $data['full_name'],
                $data['role'],
                $id
            ];
        } else {
            $sql = "UPDATE users SET username = ?, email = ?, full_name = ?, role = ? WHERE id = ?";
            $params = [
                $data['username'],
                $data['email'],
                $data['full_name'],
                $data['role'],
                $id
            ];
        }

        try {
            $result = $this->db->execute($sql, $params);
            
            if ($result && isset($_SESSION['user_id'])) {
                $this->logActivity($_SESSION['user_id'], 'Update User', 'Mengupdate user: ' . $data['username']);
            }
            
            return $result > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id) {
        $user = $this->getUserById($id);
        
        $sql = "DELETE FROM users WHERE id = ?";
        $result = $this->db->execute($sql, [$id]);
        
        if ($result && isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'Delete User', 'Menghapus user: ' . $user['username']);
        }
        
        return $result > 0;
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM users WHERE username = ? AND id != ?";
            $result = $this->db->fetchOne($sql, [$username, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
            $result = $this->db->fetchOne($sql, [$username]);
        }
        
        return $result['count'] > 0;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?";
            $result = $this->db->fetchOne($sql, [$email, $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
            $result = $this->db->fetchOne($sql, [$email]);
        }
        
        return $result['count'] > 0;
    }

    /**
     * Log activity
     */
    private function logActivity($userId, $action, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)";
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        $this->db->insert($sql, [$userId, $action, $description, $ipAddress]);
    }

    /**
     * Get activity logs
     */
    public function getActivityLogs($limit = 50) {
        $sql = "SELECT al.*, u.full_name, u.username 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.created_at DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
}
