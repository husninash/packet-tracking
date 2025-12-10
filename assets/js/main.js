/**
 * SIGAP - Main JavaScript
 * Interaktivitas untuk semua halaman
 */

// ========== Global Functions ==========

/**
 * Show alert message
 */
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;margin-left:auto;">×</button>
    `;

    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

/**
 * Confirm action
 */
function confirmAction(message) {
    return confirm(message);
}

/**
 * Format date to Indonesian
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', options);
}

/**
 * Format datetime to Indonesian
 */
function formatDateTime(dateString) {
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', options);
}

// ========== Modal Functions ==========

/**
 * Open modal
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close modal
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

/**
 * Close modal when clicking outside
 */
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// ========== Form Validation ==========

/**
 * Validate form
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = 'var(--danger-color)';

            // Remove error styling on input
            field.addEventListener('input', function () {
                this.style.borderColor = '';
            }, { once: true });
        }
    });

    if (!isValid) {
        showAlert('Mohon lengkapi semua field yang wajib diisi', 'danger');
    }

    return isValid;
}

// ========== AJAX Helper ==========

/**
 * Send AJAX request
 */
async function sendRequest(url, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {}
        };

        if (data) {
            if (data instanceof FormData) {
                options.body = data;
            } else {
                options.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(data);
            }
        }

        const response = await fetch(url, options);
        const result = await response.json();

        return result;
    } catch (error) {
        console.error('Request error:', error);
        return { success: false, message: 'Terjadi kesalahan pada koneksi' };
    }
}

// ========== Search & Filter Functions ==========

/**
 * Search table
 */
function searchTable(searchInputId, tableId) {
    const input = document.getElementById(searchInputId);
    const table = document.getElementById(tableId);

    if (!input || !table) return;

    input.addEventListener('keyup', function () {
        const filter = this.value.toUpperCase();
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });
}

/**
 * Filter table by status
 */
function filterTableByStatus(selectId, tableId) {
    const select = document.getElementById(selectId);
    const table = document.getElementById(tableId);

    if (!select || !table) return;

    select.addEventListener('change', function () {
        const filter = this.value.toUpperCase();
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            if (filter === '') {
                rows[i].style.display = '';
            } else {
                const statusCell = rows[i].cells[rows[i].cells.length - 2]; // Status column
                if (statusCell) {
                    const textValue = statusCell.textContent || statusCell.innerText;
                    rows[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
                }
            }
        }
    });
}

// ========== File Upload Preview ==========

/**
 * Preview image before upload
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * File upload with validation
 */
function handleFileUpload(inputElement, maxSizeMB = 5) {
    const file = inputElement.files[0];

    if (!file) return false;

    // Check file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
        showAlert('Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan', 'danger');
        inputElement.value = '';
        return false;
    }

    // Check file size
    const maxSize = maxSizeMB * 1024 * 1024; // Convert to bytes
    if (file.size > maxSize) {
        showAlert(`Ukuran file maksimal ${maxSizeMB}MB`, 'danger');
        inputElement.value = '';
        return false;
    }

    return true;
}

// ========== Package Functions ==========

/**
 * Delete package
 */
async function deletePackage(id) {
    if (!confirmAction('Apakah Anda yakin ingin menghapus paket ini?')) {
        return;
    }

    // Determine the correct path based on current location
    const baseUrl = window.location.pathname.includes('/views/')
        ? '../../controllers/PackageController.php'
        : 'controllers/PackageController.php';

    const result = await sendRequest(`${baseUrl}?action=delete&id=${id}`, 'GET');

    if (result.success) {
        showAlert(result.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } else {
        showAlert(result.message, 'danger');
    }
}

/**
 * Mark package as picked up
 */
async function markAsPickedUp(id) {
    openModal('modalSerahTerima');
    document.getElementById('packageIdSerahTerima').value = id;
}

/**
 * Submit serah terima form
 */
async function submitSerahTerima() {
    const form = document.getElementById('formSerahTerima');
    const formData = new FormData(form);

    // Validate
    const fotoInput = document.getElementById('fotoSerahTerima');
    if (!fotoInput.files || fotoInput.files.length === 0) {
        showAlert('Foto serah terima harus diupload', 'danger');
        return;
    }

    if (!handleFileUpload(fotoInput)) {
        return;
    }

    formData.append('action', 'mark_picked_up');
    formData.append('id', document.getElementById('packageIdSerahTerima').value);

    // Determine the correct path based on current location
    const baseUrl = window.location.pathname.includes('/views/')
        ? '../../controllers/PackageController.php'
        : 'controllers/PackageController.php';

    const result = await sendRequest(baseUrl, 'POST', formData);

    if (result.success) {
        showAlert(result.message, 'success');
        closeModal('modalSerahTerima');
        setTimeout(() => location.reload(), 1500);
    } else {
        showAlert(result.message, 'danger');
    }
}

// ========== User Management Functions ==========

/**
 * Delete user
 */
async function deleteUser(id) {
    if (!confirmAction('Apakah Anda yakin ingin menghapus user ini?')) {
        return;
    }

    // Determine the correct path based on current location
    const baseUrl = window.location.pathname.includes('/views/')
        ? '../../controllers/UserController.php'
        : 'controllers/UserController.php';

    const result = await sendRequest(`${baseUrl}?action=delete&id=${id}`, 'GET');

    if (result.success) {
        showAlert(result.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } else {
        showAlert(result.message, 'danger');
    }
}

// ========== Login Form ==========

/**
 * Handle login form submission
 */
async function handleLogin(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    formData.append('action', 'login');

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Loading...';

    // Determine the correct path based on current location
    const baseUrl = window.location.pathname.includes('/views/')
        ? '../../controllers/AuthController.php'
        : 'controllers/AuthController.php';

    const result = await sendRequest(baseUrl, 'POST', formData);

    if (result.success) {
        showAlert('Login berhasil! Mengalihkan...', 'success');
        setTimeout(() => {
            window.location.href = baseUrl.replace('controllers/AuthController.php', '') + result.redirect;
        }, 1000);
    } else {
        showAlert(result.message, 'danger');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

// ========== Initialize Functions on Page Load ==========

document.addEventListener('DOMContentLoaded', function () {
    // Initialize search if exists
    if (document.getElementById('searchInput') && document.getElementById('packageTable')) {
        searchTable('searchInput', 'packageTable');
    }

    // Initialize filter if exists
    if (document.getElementById('filterStatus') && document.getElementById('packageTable')) {
        filterTableByStatus('filterStatus', 'packageTable');
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Initialize file upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            handleFileUpload(this);

            // Preview if preview element exists
            const previewId = this.getAttribute('data-preview');
            if (previewId) {
                previewImage(this, previewId);
            }
        });
    });
});

// ========== Sidebar Toggle for Mobile ==========

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

// ========== Print Function ==========

function printTable() {
    window.print();
}

// ========== Export to CSV ==========

function exportTableToCSV(tableId, filename = 'data.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];

        cols.forEach(col => {
            // Skip action buttons column
            if (!col.querySelector('.action-buttons')) {
                rowData.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
            }
        });

        csv.push(rowData.join(','));
    });

    // Download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);

    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// ========== Statistics Chart (if using Chart.js) ==========

/**
 * Initialize chart for statistics
 */
function initializeChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;

    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// ========== Auto-refresh for real-time updates (optional) ==========

/**
 * Auto refresh page data
 */
function startAutoRefresh(intervalSeconds = 60) {
    setInterval(() => {
        location.reload();
    }, intervalSeconds * 1000);
}

// Uncomment to enable auto-refresh every 60 seconds
// startAutoRefresh(60);
