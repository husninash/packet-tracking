<?php
/**
 * Database Configuration
 * Konfigurasi koneksi database untuk sistem SIGAP
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sigap_unhan');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;
    private $error;

    /**
     * Constructor - Membuat koneksi ke database
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * Membuat koneksi mysqli
     */
    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->conn->connect_error) {
            $this->error = "Connection failed: " . $this->conn->connect_error;
            die($this->error);
        }
        
        $this->conn->set_charset("utf8mb4");
    }

    /**
     * Mendapatkan koneksi database
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Menutup koneksi database
     */
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Execute query dengan prepared statement
     */
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = '';
            $values = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        return $stmt;
    }

    /**
     * Mendapatkan semua hasil query
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }

    /**
     * Mendapatkan satu hasil query
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    /**
     * Insert data dan return last insert id
     */
    public function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $insertId = $this->conn->insert_id;
        $stmt->close();
        return $insertId;
    }

    /**
     * Update atau delete data dan return affected rows
     */
    public function execute($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
}
