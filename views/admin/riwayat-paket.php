<?php
/**
 * Riwayat Paket - Admin
 * Halaman untuk melihat riwayat paket yang sudo diambil dengan filter lengkap
 */

require_once __DIR__ . '/../../config/config.php';

requireRole(ROLE_ADMIN);

$packageModel = new Package();
$userModel = new User();

// Get initial data - all picked up packages
$packages = $packageModel->getAllPackages(['status' => STATUS_SUDAH_DIAMBIL]);
$allPetugas = $userModel->getAllUsers(ROLE_PETUGAS);

// Calculate statistics
$totalPackages = count($packages);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Paket - SIGAP</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>🛡️ SIGAP</h2>
            <p>Dashboard Admin</p>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="dashboard.php">📊 Statistik Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="data-paket.php">📦 Semua Data Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="tambah-paket.php">➕ Tambah Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="riwayat-paket.php" class="active">🕓 Riwayat Paket</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="manajemen-petugas.php">👥 Manajemen Petugas</a>
            </li>
            <li class="sidebar-menu-item">
                <a href="log-aktivitas.php">🧾 Log Aktivitas</a>
            </li>
            <li class="sidebar-menu-item logout">
                <a href="../../controllers/AuthController.php?action=logout">🔓 Logout</a>
            </li>
        </ul>
    </aside>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="page-header">
                <h1>Riwayat Paket</h1>
                <p>Paket yang sudah diambil dengan filter lengkap</p>
            </div>

            <!-- Statistics Summary -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--success);">✅</div>
                    <div class="stat-info">
                        <div class="stat-value" id="totalCount"><?= $totalPackages ?></div>
                        <div class="stat-label">Total Paket Diambil</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <!-- Filter Section -->
                <div class="card-header">
                    <h3>Filter Riwayat</h3>
                </div>
                <div class="card-body">
                    <div class="filter-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" id="filterDariTanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" id="filterSampaiTanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Petugas Serah Terima</label>
                            <select id="filterPetugas" class="form-control">
                                <option value="">Semua Petugas</option>
                                <?php foreach ($allPetugas as $petugas): ?>
                                    <option value="<?= $petugas['id'] ?>"><?= htmlspecialchars($petugas['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            🔍 Terapkan Filter
                        </button>
                        <button class="btn btn-secondary" onclick="resetFilters()">
                            ↺ Reset
                        </button>
                        <button class="btn btn-success" onclick="exportToCSV()" style="margin-left: auto;">
                            📥 Export CSV
                        </button>
                    </div>
                </div>

                <!-- Search Box -->
                <div class="search-filter-bar" style="margin-top: 1rem;">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama penerima...">
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container" id="tableContainer">
                    <table id="packageTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto Paket</th>
                                <th>Nama Penerima</th>
                                <th>Tanggal Datang</th>
                                <th>Tanggal Diambil</th>
                                <th>Petugas</th>
                                <th>Bukti Serah Terima</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php if (empty($packages)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                        Tidak ada data riwayat paket
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($packages as $pkg): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if ($pkg['foto_paket']): ?>
                                                <a href="<?= UPLOAD_URL . $pkg['foto_paket'] ?>" target="_blank">
                                                    <img src="<?= UPLOAD_URL . $pkg['foto_paket'] ?>" alt="Foto Paket" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer; border-radius: 4px;">
                                                </a>
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background: var(--gray-200); display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                    📦
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($pkg['nama_penerima']) ?></strong></td>
                                        <td><?= date('d-m-Y', strtotime($pkg['tanggal_datang'])) ?></td>
                                        <td>
                                            <?php if ($pkg['tanggal_diambil']): ?>
                                                <strong><?= date('d-m-Y', strtotime($pkg['tanggal_diambil'])) ?></strong><br>
                                                <small style="color: var(--gray-500);"><?= date('H:i', strtotime($pkg['tanggal_diambil'])) ?> WIB</small>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            // Get petugas name from users
                                            $petugasName = 'N/A';
                                            if ($pkg['petugas_serah_terima']) {
                                                foreach ($allPetugas as $p) {
                                                    if ($p['id'] == $pkg['petugas_serah_terima']) {
                                                        $petugasName = $p['full_name'];
                                                        break;
                                                    }
                                                }
                                            }
                                            echo htmlspecialchars($petugasName);
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($pkg['foto_serah_terima'])): ?>
                                                <a href="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" target="_blank" title="Lihat bukti serah terima">
                                                    <img src="<?= UPLOAD_URL . $pkg['foto_serah_terima'] ?>" alt="Bukti" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer; border-radius: 4px; border: 2px solid var(--success);">
                                                </a>
                                            <?php else: ?>
                                                <span style="color: var(--gray-400);">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script>
        // Global data untuk filter
        let allPackages = <?= json_encode($packages) ?>;
        let filteredPackages = [...allPackages];

        // Apply filters
        function applyFilters() {
            const dariTanggal = document.getElementById('filterDariTanggal').value;
            const sampaiTanggal = document.getElementById('filterSampaiTanggal').value;
            const petugasId = document.getElementById('filterPetugas').value;

            // Validate date range
            if (dariTanggal && sampaiTanggal && new Date(dariTanggal) > new Date(sampaiTanggal)) {
                showAlert('Tanggal "Dari" tidak boleh lebih besar dari "Sampai"', 'warning');
                return;
            }

            // Filter packages
            filteredPackages = allPackages.filter(pkg => {
                // Filter by date range (tanggal diambil)
                if (dariTanggal && pkg.tanggal_diambil) {
                    const tanggalDiambil = pkg.tanggal_diambil.split(' ')[0]; // Get date part only
                    if (tanggalDiambil < dariTanggal) return false;
                }
                if (sampaiTanggal && pkg.tanggal_diambil) {
                    const tanggalDiambil = pkg.tanggal_diambil.split(' ')[0];
                    if (tanggalDiambil > sampaiTanggal) return false;
                }

                // Filter by petugas
                if (petugasId && pkg.petugas_serah_terima != petugasId) return false;

                return true;
            });

            updateTable();
            updateStats();
            showAlert(`Filter diterapkan: ${filteredPackages.length} paket ditemukan`, 'success');
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('filterDariTanggal').value = '';
            document.getElementById('filterSampaiTanggal').value = '';
            document.getElementById('filterPetugas').value = '';
            document.getElementById('searchInput').value = '';

            filteredPackages = [...allPackages];
            updateTable();
            updateStats();
            showAlert('Filter direset', 'info');
        }

        // Update table with filtered data
        function updateTable() {
            const tbody = document.getElementById('tableBody');
            const searchValue = document.getElementById('searchInput').value.toLowerCase();

            // Apply search filter
            let displayPackages = filteredPackages;
            if (searchValue) {
                displayPackages = filteredPackages.filter(pkg => 
                    pkg.nama_penerima.toLowerCase().includes(searchValue)
                );
            }

            if (displayPackages.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                            Tidak ada data yang sesuai dengan filter
                        </td>
                    </tr>
                `;
                return;
            }

            const petugasList = <?= json_encode($allPetugas) ?>;

            tbody.innerHTML = displayPackages.map((pkg, index) => {
                const petugasName = pkg.petugas_serah_terima ? 
                    (petugasList.find(p => p.id == pkg.petugas_serah_terima)?.full_name || 'N/A') : 'N/A';

                const fotoPacket = pkg.foto_paket ? 
                    `<a href="<?= UPLOAD_URL ?>${pkg.foto_paket}" target="_blank">
                        <img src="<?= UPLOAD_URL ?>${pkg.foto_paket}" alt="Foto Paket" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer; border-radius: 4px;">
                    </a>` :
                    `<div style="width: 50px; height: 50px; background: var(--gray-200); display: flex; align-items: center; justify-content: center; border-radius: 4px;">📦</div>`;

                const tanggalDiambil = pkg.tanggal_diambil ? 
                    `<strong>${formatDate(pkg.tanggal_diambil.split(' ')[0])}</strong><br><small style="color: var(--gray-500);">${pkg.tanggal_diambil.split(' ')[1]} WIB</small>` : '-';

                const buktiSerahTerima = pkg.foto_serah_terima ?
                    `<a href="<?= UPLOAD_URL ?>${pkg.foto_serah_terima}" target="_blank" title="Lihat bukti serah terima">
                        <img src="<?= UPLOAD_URL ?>${pkg.foto_serah_terima}" alt="Bukti" style="max-width: 50px; max-height: 50px; object-fit: cover; cursor: pointer; border-radius: 4px; border: 2px solid var(--success);">
                    </a>` : '<span style="color: var(--gray-400);">-</span>';

                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${fotoPacket}</td>
                        <td><strong>${escapeHtml(pkg.nama_penerima)}</strong></td>
                        <td>${formatDate(pkg.tanggal_datang)}</td>
                        <td>${tanggalDiambil}</td>
                        <td>${escapeHtml(petugasName)}</td>
                        <td>${buktiSerahTerima}</td>
                    </tr>
                `;
            }).join('');
        }

        // Update statistics
        function updateStats() {
            document.getElementById('totalCount').textContent = filteredPackages.length;
        }

        // Export to CSV
        function exportToCSV() {
            const headers = ['No', 'Nama Penerima', 'Tanggal Datang', 'Tanggal Diambil', 'Petugas', 'Catatan'];
            const petugasList = <?= json_encode($allPetugas) ?>;

            const rows = filteredPackages.map((pkg, index) => {
                const petugasName = pkg.petugas_serah_terima ? 
                    (petugasList.find(p => p.id == pkg.petugas_serah_terima)?.full_name || 'N/A') : 'N/A';

                return [
                    index + 1,
                    pkg.nama_penerima,
                    formatDate(pkg.tanggal_datang),
                    pkg.tanggal_diambil ? formatDate(pkg.tanggal_diambil.split(' ')[0]) + ' ' + pkg.tanggal_diambil.split(' ')[1] : '-',
                    petugasName,
                    pkg.catatan || '-'
                ];
            });

            let csvContent = headers.join(',') + '\n';
            rows.forEach(row => {
                csvContent += row.map(cell => `"${cell}"`).join(',') + '\n';
            });

            // Download
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `riwayat-paket-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showAlert('Data berhasil di-export ke CSV', 'success');
        }

        // Helper function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            updateTable();
        });

        // Set default date range (last 30 days)
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(today.getDate() - 30);
        
        // Uncomment below to set default date range
        // document.getElementById('filterSampaiTanggal').value = today.toISOString().split('T')[0];
        // document.getElementById('filterDariTanggal').value = thirtyDaysAgo.toISOString().split('T')[0];
    </script>
</body>
</html>
