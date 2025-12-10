<?php
/**
 * ProgramStudi Model
 * Model untuk mengelola data program studi
 */

class ProgramStudi {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Get all program studi
     */
    public function getAllProdi() {
        $sql = "SELECT * FROM program_studi ORDER BY nama_prodi ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get prodi by ID
     */
    public function getProdiById($id) {
        $sql = "SELECT * FROM program_studi WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Create new prodi
     */
    public function createProdi($nama_prodi) {
        $sql = "INSERT INTO program_studi (nama_prodi) VALUES (?)";
        
        try {
            return $this->db->insert($sql, [$nama_prodi]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update prodi
     */
    public function updateProdi($id, $nama_prodi) {
        $sql = "UPDATE program_studi SET nama_prodi = ? WHERE id = ?";
        
        try {
            return $this->db->execute($sql, [$nama_prodi, $id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete prodi
     */
    public function deleteProdi($id) {
        // Check if prodi is used in packages
        $sql = "SELECT COUNT(*) as count FROM packages WHERE prodi_id = ?";
        $result = $this->db->fetchOne($sql, [$id]);
        
        if ($result['count'] > 0) {
            return ['success' => false, 'message' => 'Program studi tidak dapat dihapus karena masih digunakan'];
        }

        $sql = "DELETE FROM program_studi WHERE id = ?";
        $deleted = $this->db->execute($sql, [$id]) > 0;
        
        if ($deleted) {
            return ['success' => true, 'message' => 'Program studi berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Gagal menghapus program studi'];
        }
    }
}
