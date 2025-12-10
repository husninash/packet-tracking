# 📡 API ENDPOINTS - SIGAP

Dokumentasi endpoint untuk pengembangan lebih lanjut.

## Authentication

### Login
```
POST /controllers/AuthController.php
Body: {
    action: "login",
    username: "admin",
    password: "password"
}
Response: {
    success: true/false,
    message: "...",
    redirect: "views/admin/dashboard.php"
}
```

### Logout
```
GET /controllers/AuthController.php?action=logout
Response: Redirect to index.php
```

## Package Management

### Get All Packages
```
GET /controllers/PackageController.php?action=getAll
Optional params:
  - status: "Belum Diambil" | "Sudah Diambil"
  - search: "nama atau nim"
  - prodi_id: integer
  - tanggal_dari: "Y-m-d"
  - tanggal_sampai: "Y-m-d"

Response: Array of packages
```

### Create Package
```
POST /controllers/PackageController.php
Body (FormData): {
    action: "create",
    nama_penerima: "...",
    nim: "...",
    prodi_id: integer,
    tanggal_datang: "Y-m-d",
    petugas_penerima: "...",
    catatan: "...",
    foto_paket: File (optional)
}
Response: {
    success: true/false,
    message: "...",
    id: integer (if success)
}
```

### Update Package
```
POST /controllers/PackageController.php
Body (FormData): {
    action: "update",
    id: integer,
    nama_penerima: "...",
    nim: "...",
    prodi_id: integer,
    tanggal_datang: "Y-m-d",
    petugas_penerima: "...",
    catatan: "...",
    foto_paket: File (optional)
}
Response: {
    success: true/false,
    message: "..."
}
```

### Mark as Picked Up
```
POST /controllers/PackageController.php
Body (FormData): {
    action: "mark_picked_up",
    id: integer,
    foto_serah_terima: File (required)
}
Response: {
    success: true/false,
    message: "..."
}
```

### Delete Package
```
GET /controllers/PackageController.php?action=delete&id={id}
Response: {
    success: true/false,
    message: "..."
}
```

## User Management (Admin Only)

### Get All Users
```
GET /controllers/UserController.php?action=getAll
Optional params:
  - role: "admin" | "petugas"

Response: Array of users
```

### Create User
```
POST /controllers/UserController.php
Body: {
    action: "create",
    username: "...",
    email: "...",
    password: "...",
    full_name: "...",
    role: "admin" | "petugas"
}
Response: {
    success: true/false,
    message: "..."
}
```

### Update User
```
POST /controllers/UserController.php
Body: {
    action: "update",
    id: integer,
    username: "...",
    email: "...",
    password: "..." (optional),
    full_name: "...",
    role: "admin" | "petugas"
}
Response: {
    success: true/false,
    message: "..."
}
```

### Delete User
```
GET /controllers/UserController.php?action=delete&id={id}
Response: {
    success: true/false,
    message: "..."
}
```

## Status Codes
- `success: true` - Operasi berhasil
- `success: false` - Operasi gagal, cek `message` untuk detail

## Error Responses
```json
{
    "success": false,
    "message": "Error description"
}
```

## File Upload
- Max size: 5MB
- Allowed types: JPG, JPEG, PNG, GIF
- Upload path:
  - Packages: `uploads/packages/`
  - Handover: `uploads/handover/`

## Session Variables
```php
$_SESSION['user_id']      // ID user
$_SESSION['username']     // Username
$_SESSION['full_name']    // Nama lengkap
$_SESSION['role']         // "admin" atau "petugas"
```

## Helper Functions (config.php)
```php
redirect($url)           // Redirect ke URL
isLoggedIn()            // Cek sudah login
hasRole($role)          // Cek role user
requireLogin()          // Require login (redirect jika belum)
requireRole($role)      // Require role tertentu
```

## Database Models

### Package Model Methods
```php
getAllPackages($filters)    // Get all with filters
getPackageById($id)         // Get by ID
createPackage($data)        // Create new
updatePackage($id, $data)   // Update
deletePackage($id)          // Delete
markAsPickedUp($id, $foto)  // Mark as picked up
getStatistics()             // Get statistics
getPackagesPerWeek()        // Get weekly stats
getTopActivePetugas()       // Get top active petugas
uploadFoto($file, $type)    // Upload photo
```

### User Model Methods
```php
login($username, $pass)     // Login
logout()                    // Logout
getUserById($id)            // Get by ID
getAllUsers($role)          // Get all
createUser($data)           // Create
updateUser($id, $data)      // Update
deleteUser($id)             // Delete
usernameExists($username)   // Check username
emailExists($email)         // Check email
getActivityLogs($limit)     // Get logs
```

## Example Usage (JavaScript)

### Login
```javascript
const formData = new FormData();
formData.append('action', 'login');
formData.append('username', 'admin');
formData.append('password', 'password');

const response = await fetch('controllers/AuthController.php', {
    method: 'POST',
    body: formData
});

const result = await response.json();
if (result.success) {
    window.location.href = result.redirect;
}
```

### Create Package
```javascript
const formData = new FormData(document.getElementById('formTambahPaket'));
formData.append('action', 'create');

const response = await fetch('controllers/PackageController.php', {
    method: 'POST',
    body: formData
});

const result = await response.json();
if (result.success) {
    showAlert(result.message, 'success');
}
```

### Mark as Picked Up
```javascript
const formData = new FormData();
formData.append('action', 'mark_picked_up');
formData.append('id', packageId);
formData.append('foto_serah_terima', fileInput.files[0]);

const response = await fetch('controllers/PackageController.php', {
    method: 'POST',
    body: formData
});

const result = await response.json();
```

## Security Notes
- Semua endpoint yang mengubah data memerlukan login
- Admin endpoints memerlukan role admin
- CSRF protection bisa ditambahkan dengan token
- Rate limiting bisa ditambahkan untuk mencegah abuse

---
Dokumentasi ini untuk pengembangan lebih lanjut atau integrasi dengan sistem lain.
