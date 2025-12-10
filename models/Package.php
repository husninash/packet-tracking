<?php
/**
 * Package Model
 * Model untuk mengelola data paket
 */

class Package {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get all packages dengan filter
     */
    public function getAllPackages($filters = []) {
        $sql = "SELECT p.*, ps.nama_prodi, u.full_name as petugas_nama
                FROM packages p 
                LEFT JOIN program_studi ps ON p.prodi_id = ps.id 
                LEFT JOIN users u ON p.petugas_serah_terima = u.id
                WHERE 1=1";
        
        $params = [];

        // Filter by status
        if (isset($filters['status']) && !empty($filters['status'])) {
            $sql .= " AND p.status = ?";
            $params[] = $filters['status'];
        }

        // Filter by search (nama atau NIM)
        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (p.nama_penerima LIKE ? OR p.nim LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Filter by prodi
        if (isset($filters['prodi_id']) && !empty($filters['prodi_id'])) {
            $sql .= " AND p.prodi_id = ?";
            $params[] = $filters['prodi_id'];
        }

        // Filter by date range
        if (isset($filters['tanggal_dari']) && !empty($filters['tanggal_dari'])) {
            $sql .= " AND p.tanggal_datang >= ?";
            $params[] = $filters['tanggal_dari'];
        }

        if (isset($filters['tanggal_sampai']) && !empty($filters['tanggal_sampai'])) {
            $sql .= " AND p.tanggal_datang <= ?";
            $params[] = $filters['tanggal_sampai'];
        }

        $sql .= " ORDER BY p.tanggal_datang DESC, p.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get package by ID
     */
    public function getPackageById($id) {
        $sql = "SELECT p.*, ps.nama_prodi, 
                u.full_name as petugas_nama
                FROM packages p 
                LEFT JOIN program_studi ps ON p.prodi_id = ps.id 
                LEFT JOIN users u ON p.petugas_serah_terima = u.id
                WHERE p.id = ?";
        
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Create new package
     */
    public function createPackage($data) {
        $sql = "INSERT INTO packages (nama_penerima, nim, prodi_id, tanggal_datang, petugas_penerima, foto_paket, catatan, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $packageId = $this->db->insert($sql, [
                $data['nama_penerima'],
                $data['nim'],
                $data['prodi_id'],
                $data['tanggal_datang'],
                $data['petugas_penerima'],
                $data['foto_paket'] ?? null,
                $data['catatan'] ?? null,
                STATUS_BELUM_DIAMBIL
            ]);
            
            if ($packageId && isset($_SESSION['user_id'])) {
                $this->logActivity($_SESSION['user_id'], 'Tambah Paket', 'Menambahkan paket untuk ' . $data['nama_penerima']);
            }
            
            return $packageId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update package
     */
    public function updatePackage($id, $data) {
        $sql = "UPDATE packages SET 
                nama_penerima = ?, 
                nim = ?, 
                prodi_id = ?, 
                tanggal_datang = ?, 
                petugas_penerima = ?, 
                catatan = ?";
        
        $params = [
            $data['nama_penerima'],
            $data['nim'],
            $data['prodi_id'],
            $data['tanggal_datang'],
            $data['petugas_penerima'],
            $data['catatan'] ?? null
        ];

        if (isset($data['foto_paket']) && !empty($data['foto_paket'])) {
            $sql .= ", foto_paket = ?";
            $params[] = $data['foto_paket'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        try {
            $result = $this->db->execute($sql, $params);
            
            if ($result && isset($_SESSION['user_id'])) {
                $this->logActivity($_SESSION['user_id'], 'Update Paket', 'Mengupdate paket #' . $id);
            }
            
            return $result > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Tandai paket sudah diambil dengan foto serah terima
     */
    public function markAsPickedUp($id, $fotoSerahTerima) {
        $sql = "UPDATE packages SET 
                status = ?, 
                tanggal_diambil = NOW(), 
                foto_serah_terima = ?,
                petugas_serah_terima = ?
                WHERE id = ?";
        
        try {
            $result = $this->db->execute($sql, [
                STATUS_SUDAH_DIAMBIL,
                $fotoSerahTerima,
                $_SESSION['user_id'],
                $id
            ]);
            
            if ($result && isset($_SESSION['user_id'])) {
                $package = $this->getPackageById($id);
                $this->logActivity($_SESSION['user_id'], 'Paket Diambil', 'Menandai paket sudah diambil - ' . $package['nama_penerima']);
            }
            
            return $result > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete package
     */
    public function deletePackage($id) {
        $package = $this->getPackageById($id);
        
        // Delete photos if exist
        if ($package['foto_paket'] && file_exists(UPLOAD_PATH . $package['foto_paket'])) {
            unlink(UPLOAD_PATH . $package['foto_paket']);
        }
        
        if ($package['foto_serah_terima'] && file_exists(UPLOAD_PATH . $package['foto_serah_terima'])) {
            unlink(UPLOAD_PATH . $package['foto_serah_terima']);
        }

        $sql = "DELETE FROM packages WHERE id = ?";
        $result = $this->db->execute($sql, [$id]);
        
        if ($result && isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'Hapus Paket', 'Menghapus paket #' . $id);
        }
        
        return $result > 0;
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        $stats = [];

        // Total paket bulan ini
        $sql = "SELECT COUNT(*) as total FROM packages WHERE MONTH(tanggal_datang) = MONTH(CURRENT_DATE()) AND YEAR(tanggal_datang) = YEAR(CURRENT_DATE())";
        $result = $this->db->fetchOne($sql);
        $stats['total_bulan_ini'] = $result['total'];

        // Sudah diambil
        $sql = "SELECT COUNT(*) as total FROM packages WHERE status = ?";
        $result = $this->db->fetchOne($sql, [STATUS_SUDAH_DIAMBIL]);
        $stats['sudah_diambil'] = $result['total'];

        // Belum diambil
        $result = $this->db->fetchOne($sql, [STATUS_BELUM_DIAMBIL]);
        $stats['belum_diambil'] = $result['total'];

        // Percentage
        $total = $stats['sudah_diambil'] + $stats['belum_diambil'];
        if ($total > 0) {
            $stats['persentase_sudah_diambil'] = round(($stats['sudah_diambil'] / $total) * 100);
            $stats['persentase_belum_diambil'] = round(($stats['belum_diambil'] / $total) * 100);
        } else {
            $stats['persentase_sudah_diambil'] = 0;
            $stats['persentase_belum_diambil'] = 0;
        }

        return $stats;
    }

    /**
     * Get packages per week (last 4 weeks)
     */
    public function getPackagesPerWeek() {
        $sql = "SELECT 
                WEEK(tanggal_datang) as minggu,
                status,
                COUNT(*) as total
                FROM packages 
                WHERE tanggal_datang >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
                GROUP BY WEEK(tanggal_datang), status
                ORDER BY minggu";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get top active petugas
     */
    public function getTopActivePetugas() {
        $sql = "SELECT 
                petugas_penerima,
                COUNT(*) as total_paket
                FROM packages 
                GROUP BY petugas_penerima
                ORDER BY total_paket DESC
                LIMIT 5";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Upload foto
     */
    public function uploadFoto($file, $type = 'packages') {
        $targetDir = UPLOAD_PATH . $type . '/';
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $targetFile = $targetDir . $fileName;

        // Check if file is actual image
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return ['success' => false, 'message' => 'File bukan gambar'];
        }

        // Check file size (max 5MB)
        if ($file['size'] > 5000000) {
            return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
        }

        // Allow certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) {
            return ['success' => false, 'message' => 'Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan'];
        }

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['success' => true, 'filename' => $type . '/' . $fileName];
        } else {
            return ['success' => false, 'message' => 'Gagal mengupload file'];
        }
    }

    /**
     * Log activity
     */
    private function logActivity($userId, $action, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)";
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        $this->db->insert($sql, [$userId, $action, $description, $ipAddress]);
    }
}
