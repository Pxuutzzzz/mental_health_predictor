<?php
require_once __DIR__ . '/../app/Database.php';

$title = 'Persetujuan Privasi';
$db = Database::getInstance()->getConnection();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

$userId = $_SESSION['user_id'];

// Check if consent already given
try {
    $stmt = $db->prepare("SELECT * FROM user_consents WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$userId]);
    $existingConsent = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table might not exist yet, set to null
    $existingConsent = null;
}

// Handle consent submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consentData = json_encode([
        'data_collection' => isset($_POST['data_collection']),
        'data_processing' => isset($_POST['data_processing']),
        'data_storage' => isset($_POST['data_storage']),
        'data_sharing' => isset($_POST['data_sharing']),
        'analytics' => isset($_POST['analytics']),
        'marketing' => isset($_POST['marketing'])
    ]);
    
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $db->prepare("
        INSERT INTO user_consents (user_id, consent_data, ip_address, user_agent, consent_version, created_at)
        VALUES (?, ?, ?, ?, '1.0', NOW())
    ");
    
    $stmt->execute([$userId, $consentData, $ipAddress, $userAgent]);
    
    // Log the consent
    require_once __DIR__ . '/../app/Security/AuditLogger.php';
    $logger = new AuditLogger($db);
    $logger->log(
        AuditLogger::EVENT_CONSENT_GIVEN,
        $userId,
        'User provided consent for data processing',
        json_decode($consentData, true),
        'SUCCESS'
    );
    
    $_SESSION['consent_given'] = true;
    header('Location: ?page=dashboard');
    exit;
}

ob_start();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3">Persetujuan Privasi & Pemrosesan Data</h2>
                        <p class="text-muted">Kami menghormati privasi Anda dan berkomitmen melindungi data pribadi Anda</p>
                    </div>

                    <?php if ($existingConsent): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Anda telah memberikan persetujuan pada <?php echo date('d F Y, H:i', strtotime($existingConsent['created_at'])); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <!-- Introduction -->
                        <div class="mb-4">
                            <h5 class="text-primary">Mengapa Kami Memerlukan Persetujuan Anda?</h5>
                            <p>
                                Sesuai dengan GDPR (General Data Protection Regulation) dan HIPAA (Health Insurance Portability and Accountability Act),
                                kami wajib mendapatkan persetujuan eksplisit Anda sebelum mengumpulkan dan memproses data kesehatan mental Anda.
                            </p>
                        </div>

                        <!-- Required Consents -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Persetujuan Wajib <span class="text-danger">*</span></h5>
                            
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="checkbox" name="data_collection" id="data_collection" required>
                                <label class="form-check-label" for="data_collection">
                                    <strong>Pengumpulan Data</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya menyetujui pengumpulan data pribadi saya termasuk nama, email, dan tanggal lahir untuk keperluan
                                        pendaftaran akun dan identifikasi pengguna.
                                    </p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="checkbox" name="data_processing" id="data_processing" required>
                                <label class="form-check-label" for="data_processing">
                                    <strong>Pemrosesan Data Kesehatan Mental</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya menyetujui pemrosesan data jawaban kuesioner kesehatan mental saya menggunakan model AI/Machine Learning
                                        untuk memberikan prediksi dan rekomendasi kondisi mental.
                                    </p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="checkbox" name="data_storage" id="data_storage" required>
                                <label class="form-check-label" for="data_storage">
                                    <strong>Penyimpanan Data Terenkripsi</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya memahami bahwa data saya akan disimpan dalam database terenkripsi dengan standar AES-256
                                        dan akan disimpan selama 2 tahun atau hingga saya menghapus akun.
                                    </p>
                                </label>
                            </div>
                        </div>

                        <!-- Optional Consents -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Persetujuan Opsional</h5>
                            
                            <div class="form-check mb-3 p-3 border rounded bg-light">
                                <input class="form-check-input" type="checkbox" name="data_sharing" id="data_sharing">
                                <label class="form-check-label" for="data_sharing">
                                    <strong>Berbagi dengan Profesional Kesehatan</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya menyetujui data saya dibagikan dengan profesional kesehatan mental terdaftar
                                        untuk mendapatkan konsultasi lebih lanjut (dengan identitas dianonimkan).
                                    </p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded bg-light">
                                <input class="form-check-input" type="checkbox" name="analytics" id="analytics">
                                <label class="form-check-label" for="analytics">
                                    <strong>Analitik & Riset (Anonim)</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya menyetujui data saya digunakan untuk analisis statistik dan riset (dalam bentuk anonim)
                                        untuk meningkatkan akurasi sistem prediksi.
                                    </p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded bg-light">
                                <input class="form-check-input" type="checkbox" name="marketing" id="marketing">
                                <label class="form-check-label" for="marketing">
                                    <strong>Komunikasi Marketing</strong>
                                    <p class="text-muted mb-0 mt-2">
                                        Saya bersedia menerima informasi tentang fitur baru, tips kesehatan mental, dan
                                        pembaruan sistem melalui email.
                                    </p>
                                </label>
                            </div>
                        </div>

                        <!-- User Rights -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Hak-Hak Anda</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Akses:</strong> Mengunduh semua data Anda dalam format CSV/JSON
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Perbaikan:</strong> Mengubah data pribadi Anda kapan saja
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Penghapusan:</strong> Menghapus akun dan seluruh data Anda secara permanen
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Portabilitas:</strong> Memindahkan data Anda ke sistem lain
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Keberatan:</strong> Menolak pemrosesan data untuk tujuan tertentu
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <strong>Hak Menarik Persetujuan:</strong> Mencabut persetujuan ini kapan saja
                                </li>
                            </ul>
                        </div>

                        <!-- Data Protection -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="text-primary">
                                <i class="bi bi-lock-fill me-2"></i>
                                Keamanan Data Anda
                            </h6>
                            <ul class="mb-0 text-muted small">
                                <li>Enkripsi AES-256 untuk data sensitif</li>
                                <li>HTTPS/TLS untuk semua komunikasi</li>
                                <li>Audit logging untuk semua akses data</li>
                                <li>Backup terenkripsi setiap hari</li>
                                <li>Akses terbatas hanya untuk personel terotorisasi</li>
                            </ul>
                        </div>

                        <!-- Contact -->
                        <div class="mb-4 p-3 border-start border-primary border-4">
                            <h6>Pertanyaan tentang Privasi?</h6>
                            <p class="text-muted mb-2">
                                Hubungi Data Protection Officer kami:
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-envelope-fill me-2"></i>
                                Email: <a href="mailto:privacy@mentalhealthpredictor.com">privacy@mentalhealthpredictor.com</a>
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Saya Setuju & Lanjutkan
                            </button>
                            <a href="?page=logout" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Saya Tidak Setuju
                            </a>
                        </div>

                        <p class="text-center text-muted mt-3 small">
                            Dengan menekan "Saya Setuju", Anda menyetujui 
                            <a href="?page=privacy-policy">Kebijakan Privasi</a> dan 
                            <a href="?page=terms">Syarat & Ketentuan</a> kami.
                        </p>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i>
                    Dokumen ini versi 1.0 | Terakhir diperbarui: 21 Desember 2025
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
