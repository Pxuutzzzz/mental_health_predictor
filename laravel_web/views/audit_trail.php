<?php
require_once __DIR__ . '/../app/Database.php';
require_once __DIR__ . '/../app/Security/AuditLogger.php';

$title = 'Audit Trail - Riwayat Aktivitas';
$db = Database::getInstance()->getConnection();
$logger = new AuditLogger($db);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$userId = $_SESSION['user_id'];

// Get filters from query string
$filterType = $_GET['filter_type'] ?? '';
$filterDate = $_GET['filter_date'] ?? '';

// Build filter array
$filters = ['user_id' => $userId];
if ($filterType) {
    $filters['event_type'] = $filterType;
}
if ($filterDate) {
    $filters['date_from'] = $filterDate . ' 00:00:00';
    $filters['date_to'] = $filterDate . ' 23:59:59';
}

// Get audit logs
$logs = $logger->getLogs($filters, 50, 0);

ob_start();
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2>
                <i class="bi bi-clock-history me-2"></i>
                Riwayat Aktivitas Anda
            </h2>
            <p class="text-muted">
                Semua aktivitas Anda dicatat untuk keamanan dan transparansi. 
                Log ini sesuai dengan persyaratan HIPAA/GDPR.
            </p>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-shield-check me-2"></i>
                        Total Aktivitas
                    </h6>
                    <h3 class="mb-0"><?php echo count($logs); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        Periode
                    </h6>
                    <h6 class="mb-0">
                        <?php 
                        if ($filterDate) {
                            echo date('d M Y', strtotime($filterDate));
                        } else {
                            echo 'Semua Waktu';
                        }
                        ?>
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-funnel me-2"></i>
                        Filter Aktif
                    </h6>
                    <h6 class="mb-0">
                        <?php 
                        if ($filterType) {
                            echo $filterType;
                        } else {
                            echo 'Semua Jenis';
                        }
                        ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="audit-trail">
                
                <div class="col-md-4">
                    <label for="filter_type" class="form-label">Jenis Aktivitas</label>
                    <select class="form-select" id="filter_type" name="filter_type">
                        <option value="">Semua Jenis</option>
                        <option value="LOGIN" <?php echo $filterType === 'LOGIN' ? 'selected' : ''; ?>>Login</option>
                        <option value="LOGOUT" <?php echo $filterType === 'LOGOUT' ? 'selected' : ''; ?>>Logout</option>
                        <option value="PREDICTION" <?php echo $filterType === 'PREDICTION' ? 'selected' : ''; ?>>Prediksi</option>
                        <option value="DATA_ACCESS" <?php echo $filterType === 'DATA_ACCESS' ? 'selected' : ''; ?>>Akses Data</option>
                        <option value="DATA_EXPORT" <?php echo $filterType === 'DATA_EXPORT' ? 'selected' : ''; ?>>Ekspor Data</option>
                        <option value="CONSENT_GIVEN" <?php echo $filterType === 'CONSENT_GIVEN' ? 'selected' : ''; ?>>Persetujuan</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="filter_date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="filter_date" name="filter_date" 
                           value="<?php echo htmlspecialchars($filterDate); ?>">
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>
                    <a href="?page=audit-trail" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Log Aktivitas
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($logs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Tidak ada log aktivitas ditemukan.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 180px;">Waktu</th>
                                <th style="width: 150px;">Jenis Aktivitas</th>
                                <th>Deskripsi</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 140px;">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <?php
                                // Determine icon and color based on event type
                                $iconMap = [
                                    'LOGIN' => ['icon' => 'box-arrow-in-right', 'color' => 'success'],
                                    'LOGOUT' => ['icon' => 'box-arrow-left', 'color' => 'secondary'],
                                    'LOGIN_FAILED' => ['icon' => 'x-circle', 'color' => 'danger'],
                                    'REGISTER' => ['icon' => 'person-plus', 'color' => 'info'],
                                    'PREDICTION' => ['icon' => 'stars', 'color' => 'primary'],
                                    'DATA_ACCESS' => ['icon' => 'eye', 'color' => 'warning'],
                                    'DATA_EXPORT' => ['icon' => 'download', 'color' => 'info'],
                                    'CONSENT_GIVEN' => ['icon' => 'check-circle', 'color' => 'success'],
                                ];
                                
                                $eventInfo = $iconMap[$log['event_type']] ?? ['icon' => 'circle', 'color' => 'secondary'];
                                ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d M Y, H:i', strtotime($log['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $eventInfo['color']; ?>">
                                            <i class="bi bi-<?php echo $eventInfo['icon']; ?> me-1"></i>
                                            <?php echo $log['event_type']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($log['action']); ?>
                                        <?php if (!empty($log['details']) && $log['details'] !== '{}'): ?>
                                            <button class="btn btn-sm btn-link p-0 ms-2" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#details-<?php echo $log['id']; ?>">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                            <div class="collapse mt-2" id="details-<?php echo $log['id']; ?>">
                                                <div class="alert alert-light mb-0">
                                                    <small>
                                                        <pre class="mb-0"><?php echo htmlspecialchars($log['details']); ?></pre>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($log['status'] === 'SUCCESS'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Berhasil
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Gagal
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted font-monospace">
                                            <?php echo htmlspecialchars($log['ip_address']); ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Information Box -->
    <div class="alert alert-info mt-4">
        <h6>
            <i class="bi bi-info-circle-fill me-2"></i>
            Tentang Audit Trail
        </h6>
        <ul class="mb-0">
            <li>Log aktivitas disimpan untuk keamanan dan kepatuhan HIPAA/GDPR</li>
            <li>Semua akses ke data kesehatan mental Anda dicatat</li>
            <li>Log disimpan selama 7 tahun sesuai regulasi</li>
            <li>Hanya Anda dan administrator terotorisasi yang dapat melihat log ini</li>
        </ul>
    </div>

    <!-- Export Button -->
    <div class="text-center mt-4 mb-4">
        <a href="#" class="btn btn-outline-primary" onclick="exportLogs(); return false;">
            <i class="bi bi-download me-2"></i>
            Ekspor Log (CSV)
        </a>
    </div>
</div>

<script>
function exportLogs() {
    // In production, this would call a backend endpoint
    alert('Fitur ekspor akan mendownload file CSV dengan semua log aktivitas Anda.\n\nImplementasi: Tambahkan endpoint di index.php untuk generate CSV.');
    
    // Example implementation:
    // window.location.href = '?page=export-audit&format=csv';
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
