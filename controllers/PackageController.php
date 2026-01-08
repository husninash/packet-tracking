<?php
/**
 * PackageController
 * Controller untuk menangani operasi CRUD paket
 */

require_once __DIR__ . '/../config/config.php';

class PackageController {
    private $packageModel;

    public function __construct() {
        $this->packageModel = new Package();
    }

    /**
     * Get all packages
     */
    public function getAll() {
        $filters = [];
        
        if (isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        
        if (isset($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }
        
        if (isset($_GET['prodi_id'])) {
            $filters['prodi_id'] = $_GET['prodi_id'];
        }

        if (isset($_GET['tanggal_dari'])) {
            $filters['tanggal_dari'] = $_GET['tanggal_dari'];
        }

        if (isset($_GET['tanggal_sampai'])) {
            $filters['tanggal_sampai'] = $_GET['tanggal_sampai'];
        }

        return $this->packageModel->getAllPackages($filters);
    }

    /**
     * Get package by ID
     */
    public function getById($id) {
        return $this->packageModel->getPackageById($id);
    }

    /**
     * Create package
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireLogin();

            $data = [
                'nama_penerima' => trim($_POST['nama_penerima'] ?? ''),
                'nim' => '', // Default empty - field hidden from UI
                'prodi_id' => 1, // Default to first prodi - field hidden from UI
                'tanggal_datang' => $_POST['tanggal_datang'] ?? '',
                'petugas_penerima' => trim($_POST['petugas_penerima'] ?? ''),
                'catatan' => trim($_POST['catatan'] ?? '')
            ];

            // Validate required fields (only nama, tanggal, petugas)
            if (empty($data['nama_penerima']) || 
                empty($data['tanggal_datang']) || 
                empty($data['petugas_penerima'])) {
                return ['success' => false, 'message' => 'Nama Penerima, Tanggal Datang, dan Petugas Penerima wajib diisi'];
            }

            // Handle foto upload
            if (isset($_FILES['foto_paket']) && $_FILES['foto_paket']['error'] === 0) {
                $uploadResult = $this->packageModel->uploadFoto($_FILES['foto_paket'], 'packages');
                
                if ($uploadResult['success']) {
                    $data['foto_paket'] = $uploadResult['filename'];
                } else {
                    return $uploadResult;
                }
            }

            $result = $this->packageModel->createPackage($data);

            if ($result) {
                return ['success' => true, 'message' => 'Paket berhasil ditambahkan', 'id' => $result];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan paket'];
            }
        }
    }

    /**
     * Update package
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireLogin();

            $data = [
                'nama_penerima' => $_POST['nama_penerima'] ?? '',
                'nim' => '', // Default empty - field hidden from UI
                'prodi_id' => 1, // Default to first prodi - field hidden from UI
                'tanggal_datang' => $_POST['tanggal_datang'] ?? '',
                'petugas_penerima' => $_POST['petugas_penerima'] ?? '',
                'catatan' => $_POST['catatan'] ?? ''
            ];

            // Validate required fields (only nama, tanggal, petugas)
            if (empty($data['nama_penerima']) || 
                empty($data['tanggal_datang']) || 
                empty($data['petugas_penerima'])) {
                return ['success' => false, 'message' => 'Nama Penerima, Tanggal Datang, dan Petugas Penerima wajib diisi'];
            }

            // Handle foto upload
            if (isset($_FILES['foto_paket']) && $_FILES['foto_paket']['error'] === 0) {
                $uploadResult = $this->packageModel->uploadFoto($_FILES['foto_paket'], 'packages');
                
                if ($uploadResult['success']) {
                    $data['foto_paket'] = $uploadResult['filename'];
                } else {
                    return $uploadResult;
                }
            }

            $result = $this->packageModel->updatePackage($id, $data);

            if ($result) {
                return ['success' => true, 'message' => 'Paket berhasil diupdate'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate paket'];
            }
        }
    }

    /**
     * Mark as picked up with photo
     */
    public function markAsPickedUp($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireLogin();

            // Handle foto serah terima upload
            if (!isset($_FILES['foto_serah_terima']) || $_FILES['foto_serah_terima']['error'] !== 0) {
                return ['success' => false, 'message' => 'Foto serah terima harus diupload'];
            }

            $uploadResult = $this->packageModel->uploadFoto($_FILES['foto_serah_terima'], 'handover');
            
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            $result = $this->packageModel->markAsPickedUp($id, $uploadResult['filename']);

            if ($result) {
                return ['success' => true, 'message' => 'Paket berhasil ditandai sudah diambil'];
            } else {
                return ['success' => false, 'message' => 'Gagal menandai paket'];
            }
        }
    }

    /**
     * Delete package
     */
    public function delete($id) {
        requireLogin();
        
        $result = $this->packageModel->deletePackage($id);

        if ($result) {
            return ['success' => true, 'message' => 'Paket berhasil dihapus'];
        } else {
            return ['success' => false, 'message' => 'Gagal menghapus paket'];
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        return $this->packageModel->getStatistics();
    }

    /**
     * Get packages per week
     */
    public function getPackagesPerWeek() {
        return $this->packageModel->getPackagesPerWeek();
    }

    /**
     * Get top active petugas
     */
    public function getTopActivePetugas() {
        return $this->packageModel->getTopActivePetugas();
    }
}

// Handle AJAX requests
if (isset($_POST['action']) || isset($_GET['action'])) {
    $controller = new PackageController();
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
    
    if ($action === 'mark_picked_up') {
        $id = $_POST['id'] ?? 0;
        $result = $controller->markAsPickedUp($id);
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
