<?php
$page = 'assessment_clinical';
$pageTitle = 'Assessment Klinis - Mode Tenaga Kesehatan';
$staffConfig = require dirname(__DIR__) . '/config/hospital_staff.php';
$hospitalConfig = require dirname(__DIR__) . '/config/hospital.php';

// Check staff access
$isStaffMode = isset($_GET['staff_mode']) || isset($_SESSION['staff_mode']);
$facilityId = $_SESSION['staff_facility_id'] ?? ($_GET['facility_id'] ?? '');

// Find facility info
$facility = null;
if ($facilityId) {
    foreach ($hospitalConfig['facilities'] as $f) {
        if ($f['id'] === $facilityId) {
            $facility = $f;
            break;
        }
    }
}

ob_start();
?>

<style>
/* Clinical Mode Styles */
.clinical-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.clinical-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
}

.patient-info-card {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 25px;
}

.clinical-form-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e7ff;
}

.quick-score-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.quick-score-btn {
    flex: 1;
    min-width: 60px;
    padding: 12px;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 600;
}

.quick-score-btn:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.quick-score-btn.active {
    border-color: #3b82f6;
    background: #3b82f6;
    color: white;
}

.clinical-notes-area {
    min-height: 100px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.staff-info-bar {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 12px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
}

.form-label-clinical {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 8px;
}

.btn-clinical-primary {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    color: white;
    padding: 14px 32px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-clinical-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

.patient-lookup-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.patient-lookup-modal.show {
    display: flex;
}

.modal-content-clinical {
    background: white;
    border-radius: 15px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
</style>

<div class="container mt-4">
    <!-- Clinical Header -->
    <div class="clinical-header">
        <div class="clinical-badge">
            <i class="bi bi-hospital"></i> MODE TENAGA KESEHATAN
        </div>
        <h2 class="mb-2">Assessment Kesehatan Mental - Klinis</h2>
        <p class="mb-0">
            <?php if ($facility): ?>
                📍 <?= htmlspecialchars($facility['name']) ?>
            <?php else: ?>
                Untuk penggunaan oleh tenaga kesehatan profesional
            <?php endif; ?>
        </p>
    </div>

    <?php if ($isStaffMode && $facility): ?>
    <div class="staff-info-bar">
        <i class="bi bi-info-circle-fill me-2"></i>
        Anda sedang menggunakan sistem sebagai <strong>Tenaga Kesehatan</strong> di <strong><?= htmlspecialchars($facility['name']) ?></strong>. 
        Data assessment akan tersimpan langsung di sistem rumah sakit.
    </div>
    <?php endif; ?>

    <!-- Patient Information Card -->
    <div class="patient-info-card">
        <h5 class="mb-3"><i class="bi bi-person-badge"></i> Informasi Pasien</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-clinical">No. Rekam Medis (MRN)</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="patientMRN" placeholder="Contoh: MRN-2026-001">
                    <button class="btn btn-outline-secondary" type="button" onclick="lookupPatient()">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label-clinical">Nama Pasien (Inisial)</label>
                <input type="text" class="form-control" id="patientName" placeholder="Contoh: A.B.C.">
            </div>
            <div class="col-md-4">
                <label class="form-label-clinical">Usia</label>
                <input type="number" class="form-control" id="patientAge" min="18" max="80" value="25">
            </div>
            <div class="col-md-4">
                <label class="form-label-clinical">Jenis Kelamin</label>
                <select class="form-select" id="patientGender">
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label-clinical">Tipe Kunjungan</label>
                <select class="form-select" id="visitType">
                    <option value="outpatient">Rawat Jalan</option>
                    <option value="inpatient">Rawat Inap</option>
                    <option value="emergency">IGD</option>
                </select>
            </div>
        </div>
    </div>

    <form id="clinicalAssessmentForm">
        <!-- Mental Health Assessment Sections -->
        <div class="clinical-form-section">
            <div class="section-title">
                <i class="bi bi-clipboard-pulse"></i> Parameter Kesehatan Mental
            </div>
            
            <div class="row g-4">
                <!-- Stress Level -->
                <div class="col-md-6">
                    <label class="form-label-clinical">Tingkat Stres (0-10)</label>
                    <div class="quick-score-buttons" id="stressButtons">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <button type="button" class="quick-score-btn" data-value="<?= $i ?>" onclick="setScore('stress', <?= $i ?>)">
                            <?= $i ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="stress" id="stress" value="5">
                </div>

                <!-- Anxiety Level -->
                <div class="col-md-6">
                    <label class="form-label-clinical">Tingkat Kecemasan (0-10)</label>
                    <div class="quick-score-buttons" id="anxietyButtons">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <button type="button" class="quick-score-btn" data-value="<?= $i ?>" onclick="setScore('anxiety', <?= $i ?>)">
                            <?= $i ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="anxiety" id="anxiety" value="5">
                </div>

                <!-- Depression Level -->
                <div class="col-md-6">
                    <label class="form-label-clinical">Tingkat Depresi (0-10)</label>
                    <div class="quick-score-buttons" id="depressionButtons">
                        <?php for($i = 0; $i <= 10; $i++): ?>
                        <button type="button" class="quick-score-btn" data-value="<?= $i ?>" onclick="setScore('depression', <?= $i ?>)">
                            <?= $i ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" name="depression" id="depression" value="5">
                </div>

                <!-- Sleep Hours -->
                <div class="col-md-6">
                    <label class="form-label-clinical">Durasi Tidur (jam/hari)</label>
                    <select class="form-select" name="sleep" id="sleep">
                        <option value="0">< 2 jam</option>
                        <option value="2">2-4 jam</option>
                        <option value="4">4-6 jam</option>
                        <option value="6">6-7 jam</option>
                        <option value="7" selected>7-8 jam (Normal)</option>
                        <option value="8">8-9 jam</option>
                        <option value="10">> 9 jam</option>
                    </select>
                </div>

                <!-- Mental History -->
                <div class="col-md-4">
                    <label class="form-label-clinical">Riwayat Mental Health</label>
                    <select class="form-select" name="mental_history" id="mental_history">
                        <option value="No">Tidak Ada</option>
                        <option value="Yes">Ada Riwayat</option>
                    </select>
                </div>

                <!-- Exercise Level -->
                <div class="col-md-4">
                    <label class="form-label-clinical">Aktivitas Fisik</label>
                    <select class="form-select" name="exercise" id="exercise">
                        <option value="Low">Rendah (< 1x/minggu)</option>
                        <option value="Medium" selected>Sedang (1-3x/minggu)</option>
                        <option value="High">Tinggi (> 3x/minggu)</option>
                    </select>
                </div>

                <!-- Social Support -->
                <div class="col-md-4">
                    <label class="form-label-clinical">Dukungan Sosial</label>
                    <select class="form-select" name="social_support" id="social_support">
                        <option value="Yes" selected>Baik</option>
                        <option value="No">Kurang</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Clinical Notes Section -->
        <div class="clinical-form-section">
            <div class="section-title">
                <i class="bi bi-journal-medical"></i> Catatan Klinis
            </div>
            
            <div class="mb-3">
                <label class="form-label-clinical">Keluhan Utama / Chief Complaint</label>
                <textarea class="form-control clinical-notes-area" name="chief_complaint" id="chief_complaint" 
                          rows="3" placeholder="Contoh: Pasien mengeluh sulit tidur sejak 2 minggu terakhir..."></textarea>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-clinical">Observasi Klinis</label>
                    <textarea class="form-control" name="clinical_observation" id="clinical_observation" 
                              rows="3" placeholder="Observasi penampilan, perilaku, mood..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-clinical">Rencana Tindak Lanjut</label>
                    <textarea class="form-control" name="follow_up_plan" id="follow_up_plan" 
                              rows="3" placeholder="Rujukan, resep, jadwal kontrol..."></textarea>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label-clinical">Kode Diagnosa (ICD-10) - Opsional</label>
                    <input type="text" class="form-control" name="icd_code" id="icd_code" 
                           placeholder="Contoh: F41.9 (Anxiety disorder, unspecified)">
                </div>
                <div class="col-md-6">
                    <label class="form-label-clinical">Nama Dokter / Konselor</label>
                    <input type="text" class="form-control" name="clinician_name" id="clinician_name" 
                           placeholder="dr. [Nama], Sp.KJ">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="text-center">
            <button type="button" class="btn btn-clinical-primary" onclick="submitClinicalAssessment()">
                <i class="bi bi-check-circle-fill me-2"></i> Simpan & Proses Assessment
            </button>
            <button type="button" class="btn btn-outline-secondary ms-2" onclick="resetForm()">
                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Form
            </button>
        </div>
    </form>

    <!-- Results Section (Hidden initially) -->
    <div id="clinicalResults" style="display: none;" class="mt-4">
        <div class="clinical-form-section">
            <div class="section-title">
                <i class="bi bi-file-earmark-medical"></i> Hasil Assessment AI
            </div>
            <div id="resultsContent"></div>
            <div class="text-center mt-4">
                <button class="btn btn-success" onclick="printReport()">
                    <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                </button>
                <button class="btn btn-primary" onclick="saveToEMR()">
                    <i class="bi bi-database me-2"></i> Simpan ke EMR
                </button>
                <button class="btn btn-outline-secondary" onclick="newAssessment()">
                    <i class="bi bi-plus-circle me-2"></i> Assessment Baru
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Patient Lookup Modal -->
<div class="patient-lookup-modal" id="patientLookupModal">
    <div class="modal-content-clinical">
        <h5 class="mb-3"><i class="bi bi-search"></i> Cari Data Pasien</h5>
        <div class="mb-3">
            <input type="text" class="form-control" id="searchMRN" placeholder="Masukkan No. Rekam Medis">
        </div>
        <div id="searchResults"></div>
        <div class="text-end mt-3">
            <button class="btn btn-secondary" onclick="closePatientLookup()">Tutup</button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.95); z-index: 9999; justify-content: center; align-items: center;">
    <div class="text-center">
        <div class="spinner-border text-primary" style="width: 60px; height: 60px;"></div>
        <h4 class="mt-3">Memproses Assessment...</h4>
    </div>
</div>

<script>
// Initialize scores
let currentScores = {
    stress: 5,
    anxiety: 5,
    depression: 5
};

function setScore(type, value) {
    currentScores[type] = value;
    document.getElementById(type).value = value;
    
    // Update button states
    const buttons = document.querySelectorAll(`#${type}Buttons .quick-score-btn`);
    buttons.forEach(btn => {
        if (parseInt(btn.dataset.value) === value) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

// Initialize default scores
document.addEventListener('DOMContentLoaded', function() {
    setScore('stress', 5);
    setScore('anxiety', 5);
    setScore('depression', 5);
});

function submitClinicalAssessment() {
    document.getElementById('loadingOverlay').style.display = 'flex';
    
    const formData = new FormData(document.getElementById('clinicalAssessmentForm'));
    
    // Add patient info
    formData.append('age', document.getElementById('patientAge').value);
    formData.append('patient_mrn', document.getElementById('patientMRN').value);
    formData.append('patient_name', document.getElementById('patientName').value);
    formData.append('patient_gender', document.getElementById('patientGender').value);
    formData.append('visit_type', document.getElementById('visitType').value);
    formData.append('staff_mode', 'true');
    formData.append('facility_id', '<?= htmlspecialchars($facilityId) ?>');
    
    fetch('predict', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(result => {
        document.getElementById('loadingOverlay').style.display = 'none';
        
        if (result.success) {
            displayClinicalResults(result);
        } else {
            alert('Error: ' + (result.error || 'Unknown error'));
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').style.display = 'none';
        alert('Terjadi kesalahan: ' + error.message);
    });
}

function displayClinicalResults(result) {
    const prediction = result.prediction || 'Unknown';
    const confidence = ((result.confidence || 0) * 100).toFixed(1);
    
    let riskClass = 'text-success';
    if (prediction.toLowerCase().includes('high') || prediction.toLowerCase().includes('tinggi')) {
        riskClass = 'text-danger';
    } else if (prediction.toLowerCase().includes('moderate') || prediction.toLowerCase().includes('sedang')) {
        riskClass = 'text-warning';
    }
    
    const html = `
        <div class="row g-4">
            <div class="col-md-6">
                <div class="text-center p-4 border rounded">
                    <h6 class="text-muted">Kategori Risiko</h6>
                    <h3 class="${riskClass}">${prediction}</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-4 border rounded">
                    <h6 class="text-muted">Tingkat Keyakinan AI</h6>
                    <h3 class="text-primary">${confidence}%</h3>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h6><i class="bi bi-lightbulb"></i> Rekomendasi Klinis:</h6>
            <ul class="list-group">
                ${(result.recommendations || []).map(rec => `<li class="list-group-item">${rec}</li>`).join('')}
            </ul>
        </div>
        
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i> Data assessment telah tersimpan di sistem dengan ID: <strong>${result.assessment_id || 'N/A'}</strong>
        </div>
    `;
    
    document.getElementById('resultsContent').innerHTML = html;
    document.getElementById('clinicalResults').style.display = 'block';
    document.getElementById('clinicalResults').scrollIntoView({ behavior: 'smooth' });
}

function lookupPatient() {
    document.getElementById('patientLookupModal').classList.add('show');
    // TODO: Implement patient search via AJAX
}

function closePatientLookup() {
    document.getElementById('patientLookupModal').classList.remove('show');
}

function resetForm() {
    if (confirm('Reset semua data form?')) {
        document.getElementById('clinicalAssessmentForm').reset();
        document.getElementById('patientMRN').value = '';
        document.getElementById('patientName').value = '';
        document.getElementById('patientAge').value = '25';
        setScore('stress', 5);
        setScore('anxiety', 5);
        setScore('depression', 5);
    }
}

function printReport() {
    window.print();
}

function saveToEMR() {
    alert('Fitur integrasi EMR akan segera tersedia.');
}

function newAssessment() {
    document.getElementById('clinicalResults').style.display = 'none';
    resetForm();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
