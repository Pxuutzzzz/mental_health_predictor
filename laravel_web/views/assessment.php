<?php
$page = 'assessment';
$pageTitle = 'Kuesioner Kesehatan Mental';

// Load hospital config safely
$activeHospitals = [];
$hospitalConfigFile = dirname(__DIR__) . '/config/hospital.php';
if (file_exists($hospitalConfigFile)) {
    try {
        $hospitalConfig = require $hospitalConfigFile;
        if (isset($hospitalConfig['facilities']) && is_array($hospitalConfig['facilities'])) {
            $activeHospitals = array_values(array_filter($hospitalConfig['facilities'], function ($facility) {
                return isset($facility['active']) && $facility['active'] === true;
            }));
        }
    } catch (Exception $e) {
        error_log("Hospital config error: " . $e->getMessage());
        $activeHospitals = [];
    }
}

ob_start();
?>

<style>
/* Questionnaire Wizard Styles */
.questionnaire-container {
    max-width: 800px;
    margin: 0 auto;
}

.wizard-progress {
    position: relative;
    padding: 20px 0 40px;
}

.wizard-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 1;
}

.wizard-step {
    flex: 1;
    text-align: center;
    position: relative;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    font-size: 18px;
    transition: all 0.3s;
    border: 3px solid #e9ecef;
}

.wizard-step.active .step-circle {
    background: #4e73df;
    color: white;
    border-color: #4e73df;
    transform: scale(1.1);
}

.wizard-step.completed .step-circle {
    background: #1cc88a;
    color: white;
    border-color: #1cc88a;
}

.step-title {
    font-size: 13px;
    color: #6c757d;
    font-weight: 500;
}

.wizard-step.active .step-title {
    color: #4e73df;
    font-weight: 600;
}

.wizard-progress-bar {
    position: absolute;
    top: 45px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 0;
}

.wizard-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #1cc88a, #4e73df);
    transition: width 0.4s ease;
    width: 0%;
}

.question-card {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin: 30px 0;
    display: none;
    animation: fadeInUp 0.4s ease;
}

.question-card.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.question-number {
    color: #4e73df;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

.question-title {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.question-subtitle {
    font-size: 15px;
    color: #6c757d;
    margin-bottom: 30px;
}

/* Slider Styles */
.slider-container {
    padding: 20px 0;
}

.slider-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 13px;
    color: #6c757d;
}

.slider-value-display {
    text-align: center;
    margin-bottom: 20px;
}

.slider-value-number {
    font-size: 48px;
    font-weight: bold;
    color: #4e73df;
    line-height: 1;
}

.slider-value-label {
    font-size: 16px;
    color: #6c757d;
    margin-top: 5px;
}

/* Custom Range Slider */
.form-range {
    height: 8px;
    background: linear-gradient(90deg, #1cc88a 0%, #f6c23e 50%, #e74a3b 100%);
    border-radius: 10px;
}

.form-range::-webkit-slider-thumb {
    width: 24px;
    height: 24px;
    background: white;
    border: 3px solid #4e73df;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.form-range::-moz-range-thumb {
    width: 24px;
    height: 24px;
    background: white;
    border: 3px solid #4e73df;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Radio Button Cards */
.radio-card-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.radio-card {
    position: relative;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-card label {
    display: block;
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    background: white;
}

.radio-card label:hover {
    border-color: #4e73df;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
}

.radio-card input[type="radio"]:checked + label {
    background: linear-gradient(135deg, #4e73df, #224abe);
    border-color: #4e73df;
    color: white;
    transform: scale(1.05);
}

.radio-card-icon {
    font-size: 32px;
    margin-bottom: 10px;
}

.radio-card-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.radio-card-desc {
    font-size: 12px;
    opacity: 0.8;
}

/* Navigation Buttons */
.wizard-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    gap: 15px;
}

.btn-wizard {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-wizard:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Results Card */
.results-card {
    display: none;
    animation: fadeInUp 0.5s ease;
}

.results-card.show {
    display: block;
}

.risk-badge {
    display: inline-block;
    padding: 10px 25px;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 600;
    margin: 20px 0;
}

.risk-low { background: linear-gradient(135deg, #1cc88a, #17a673); color: white; }
.risk-moderate { background: linear-gradient(135deg, #f6c23e, #dda20a); color: white; }
.risk-high { background: linear-gradient(135deg, #e74a3b, #d32e1f); color: white; }

.confidence-display {
    text-align: center;
    margin: 30px 0;
}

.confidence-number {
    font-size: 64px;
    font-weight: bold;
    color: #4e73df;
}

.confidence-label {
    font-size: 18px;
    color: #6c757d;
}

.recommendation-item {
    padding: 15px;
    background: #f8f9fc;
    border-left: 4px solid #4e73df;
    border-radius: 5px;
    margin-bottom: 15px;
}

/* Loading Overlay */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.95);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-overlay.show {
    display: flex;
}

.loading-content {
    text-align: center;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #e9ecef;
    border-top: 5px solid #4e73df;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 40px;
    text-align: center;
    margin-bottom: 30px;
}

.welcome-card h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 15px;
}

.welcome-card p {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 25px;
}

.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 20px;
    margin: 5px;
    font-size: 14px;
}

.hospital-integration-card {
    border: 2px dashed #4e73df;
    border-radius: 16px;
    padding: 24px;
    background: #f6f8ff;
    margin-top: 20px;
}

.hospital-fields {
    margin-top: 20px;
    display: none;
    animation: fadeInUp 0.3s ease;
}

.hospital-fields.show {
    display: block;
}

.hospital-facility-info {
    margin-top: 12px;
    font-size: 13px;
    color: #4e73df;
}

.hospital-status-alert {
    border-radius: 12px;
}
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <h4>Menganalisis Data Anda...</h4>
        <p class="text-muted">AI sedang memproses jawaban Anda</p>
    </div>
</div>

<div class="questionnaire-container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <i class="bi bi-heart-pulse" style="font-size: 48px; margin-bottom: 20px;"></i>
        <h2>Asesmen Kesehatan Mental</h2>
        <p>Jawab 13 pertanyaan singkat untuk mendapatkan analisis AI tentang kondisi kesehatan mental Anda menggunakan Machine Learning</p>
        <div class="mt-3">
            <div class="info-badge">
                <i class="bi bi-shield-check"></i> 100% Aman & Rahasia
            </div>
            <div class="info-badge">
                <i class="bi bi-clock"></i> ~3 Menit
            </div>
            <div class="info-badge">
                <i class="bi bi-robot"></i> Berbasis AI
            </div>
        </div>
    </div>

    <!-- Progress Wizard -->
    <div class="wizard-progress">
        <div class="wizard-progress-bar">
            <div class="wizard-progress-fill" id="progressFill"></div>
        </div>
        <div class="wizard-steps">
            <div class="wizard-step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-title">Profil</div>
            </div>
            <div class="wizard-step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-title">Emosi</div>
            </div>
            <div class="wizard-step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-title">Gaya Hidup</div>
            </div>
            <div class="wizard-step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-title">Hasil</div>
            </div>
        </div>
    </div>

    <form id="questionnaireForm">
        <!-- Question 1: Age -->
        <div class="question-card active" data-question="1">
            <div class="question-number">Pertanyaan 1 dari 13</div>
            <h3 class="question-title">Berapa usia Anda?</h3>
            <p class="question-subtitle">Ceritakan sedikit tentang diri Anda untuk membantu kami memberikan rekomendasi yang lebih personal</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="age" id="age_18_25" value="22" checked>
                    <label for="age_18_25">
                        <div class="radio-card-icon">🧒</div>
                        <div class="radio-card-title">18-25 tahun</div>
                        <div class="radio-card-desc">Dewasa muda</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="age" id="age_26_35" value="30">
                    <label for="age_26_35">
                        <div class="radio-card-icon">👨</div>
                        <div class="radio-card-title">26-35 tahun</div>
                        <div class="radio-card-desc">Dewasa</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="age" id="age_36_50" value="43">
                    <label for="age_36_50">
                        <div class="radio-card-icon">👨‍💼</div>
                        <div class="radio-card-title">36-50 tahun</div>
                        <div class="radio-card-desc">Dewasa matang</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="age" id="age_51_plus" value="60">
                    <label for="age_51_plus">
                        <div class="radio-card-icon">👴</div>
                        <div class="radio-card-title">51+ tahun</div>
                        <div class="radio-card-desc">Lansia</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" disabled>
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(2)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 2: Gender -->
        <div class="question-card" data-question="2">
            <div class="question-number">Pertanyaan 2 dari 13</div>
            <h3 class="question-title">Apa jenis kelamin Anda?</h3>
            <p class="question-subtitle">Informasi ini membantu personalisasi analisis</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="gender" id="gender_female" value="Female" checked>
                    <label for="gender_female">
                        <div class="radio-card-icon">👩</div>
                        <div class="radio-card-title">Perempuan</div>
                        <div class="radio-card-desc">Female</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="gender" id="gender_male" value="Male">
                    <label for="gender_male">
                        <div class="radio-card-icon">👨</div>
                        <div class="radio-card-title">Laki-laki</div>
                        <div class="radio-card-desc">Male</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(1)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(3)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 3: Employment Status -->
        <div class="question-card" data-question="3">
            <div class="question-number">Pertanyaan 3 dari 13</div>
            <h3 class="question-title">Apa status pekerjaan Anda saat ini?</h3>
            <p class="question-subtitle">Status pekerjaan mempengaruhi tingkat stress dan kesehatan mental</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="employment_status" id="emp_employed" value="Employed" checked>
                    <label for="emp_employed">
                        <div class="radio-card-icon">💼</div>
                        <div class="radio-card-title">Bekerja</div>
                        <div class="radio-card-desc">Karyawan/Wiraswasta</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="employment_status" id="emp_unemployed" value="Unemployed">
                    <label for="emp_unemployed">
                        <div class="radio-card-icon">🏠</div>
                        <div class="radio-card-title">Tidak Bekerja</div>
                        <div class="radio-card-desc">Mencari pekerjaan/Lainnya</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="employment_status" id="emp_student" value="Student">
                    <label for="emp_student">
                        <div class="radio-card-icon">🎓</div>
                        <div class="radio-card-title">Pelajar/Mahasiswa</div>
                        <div class="radio-card-desc">Sedang menempuh pendidikan</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(2)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(4)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 4: Work Environment -->
        <div class="question-card" data-question="4">
            <div class="question-number">Pertanyaan 4 dari 13</div>
            <h3 class="question-title">Bagaimana lingkungan kerja/belajar Anda?</h3>
            <p class="question-subtitle">Lingkungan kerja mempengaruhi tingkat stress dan produktivitas</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="work_environment" id="env_office" value="Office" checked>
                    <label for="env_office">
                        <div class="radio-card-icon">🏢</div>
                        <div class="radio-card-title">Kantor/Kampus</div>
                        <div class="radio-card-desc">Bekerja di tempat</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="work_environment" id="env_remote" value="Remote">
                    <label for="env_remote">
                        <div class="radio-card-icon">🏠</div>
                        <div class="radio-card-title">Remote (WFH)</div>
                        <div class="radio-card-desc">Dari rumah</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="work_environment" id="env_hybrid" value="Hybrid">
                    <label for="env_hybrid">
                        <div class="radio-card-icon">🔄</div>
                        <div class="radio-card-title">Hybrid</div>
                        <div class="radio-card-desc">Kombinasi</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(3)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(5)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 5: Mental History -->
        <div class="question-card" data-question="5">
            <div class="question-number">Pertanyaan 5 dari 13</div>
            <h3 class="question-title">Apakah Anda memiliki riwayat gangguan kesehatan mental sebelumnya?</h3>
            <p class="question-subtitle">Contoh: depresi, kecemasan/anxiety, gangguan panik, PTSD, dll.</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="mental_history" id="history_no" value="No" checked>
                    <label for="history_no">
                        <div class="radio-card-icon">❌</div>
                        <div class="radio-card-title">Tidak Pernah</div>
                        <div class="radio-card-desc">Saya tidak memiliki riwayat diagnosis</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="mental_history" id="history_yes" value="Yes">
                    <label for="history_yes">
                        <div class="radio-card-icon">✅</div>
                        <div class="radio-card-title">Pernah</div>
                        <div class="radio-card-desc">Saya pernah didiagnosis sebelumnya</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(4)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(6)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 6: Seeks Treatment -->
        <div class="question-card" data-question="6">
            <div class="question-number">Pertanyaan 6 dari 13</div>
            <h3 class="question-title">Apakah Anda saat ini sedang mencari atau mendapat bantuan profesional?</h3>
            <p class="question-subtitle">Seperti berkonsultasi dengan psikolog, psikiater, atau konselor</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="seeks_treatment" id="treatment_no" value="No" checked>
                    <label for="treatment_no">
                        <div class="radio-card-icon">❌</div>
                        <div class="radio-card-title">Tidak</div>
                        <div class="radio-card-desc">Tidak sedang konseling</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="seeks_treatment" id="treatment_yes" value="Yes">
                    <label for="treatment_yes">
                        <div class="radio-card-icon">✅</div>
                        <div class="radio-card-title">Ya</div>
                        <div class="radio-card-desc">Sedang konseling/terapi</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(5)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(7)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 7: Stress Level -->
        <div class="question-card" data-question="7">
            <div class="question-number">Pertanyaan 7 dari 13</div>
            <h3 class="question-title">Seberapa tinggi tingkat stress yang Anda rasakan?</h3>
            <p class="question-subtitle">Skala 0 (sangat tenang) hingga 10 (sangat stress)</p>
            
            <div class="radio-card-group" style="grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));">
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_0" value="0">
                    <label for="stress_0">
                        <div class="radio-card-title">0</div>
                        <div class="radio-card-desc" style="font-size: 20px;">😊</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_1" value="1">
                    <label for="stress_1">
                        <div class="radio-card-title">1</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_2" value="2">
                    <label for="stress_2">
                        <div class="radio-card-title">2</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_3" value="3">
                    <label for="stress_3">
                        <div class="radio-card-title">3</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_4" value="4">
                    <label for="stress_4">
                        <div class="radio-card-title">4</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_5" value="5" checked>
                    <label for="stress_5">
                        <div class="radio-card-title">5</div>
                        <div class="radio-card-desc" style="font-size: 20px;">😐</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_6" value="6">
                    <label for="stress_6">
                        <div class="radio-card-title">6</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_7" value="7">
                    <label for="stress_7">
                        <div class="radio-card-title">7</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_8" value="8">
                    <label for="stress_8">
                        <div class="radio-card-title">8</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_9" value="9">
                    <label for="stress_9">
                        <div class="radio-card-title">9</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="stress_level" id="stress_10" value="10">
                    <label for="stress_10">
                        <div class="radio-card-title">10</div>
                        <div class="radio-card-desc" style="font-size: 20px;">😰</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(6)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(8)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 8: Depression Score -->
        <div class="question-card" data-question="8">
            <div class="question-number">Pertanyaan 8 dari 13</div>
            <h3 class="question-title">Skor Depresi - Seberapa sering Anda merasa sedih/tidak bersemangat?</h3>
            <p class="question-subtitle">Pilih kategori yang paling sesuai dengan kondisi Anda (PHQ-9)</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="depression_score" id="dep_minimal" value="2" checked>
                    <label for="dep_minimal">
                        <div class="radio-card-icon">😊</div>
                        <div class="radio-card-title">Minimal</div>
                        <div class="radio-card-desc">Skor 0-4: Tidak pernah</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="depression_score" id="dep_ringan" value="7">
                    <label for="dep_ringan">
                        <div class="radio-card-icon">🙂</div>
                        <div class="radio-card-title">Ringan</div>
                        <div class="radio-card-desc">Skor 5-9: Kadang-kadang</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="depression_score" id="dep_sedang" value="12">
                    <label for="dep_sedang">
                        <div class="radio-card-icon">😕</div>
                        <div class="radio-card-title">Sedang</div>
                        <div class="radio-card-desc">Skor 10-14: Cukup sering</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="depression_score" id="dep_cukup_parah" value="17">
                    <label for="dep_cukup_parah">
                        <div class="radio-card-icon">😟</div>
                        <div class="radio-card-title">Cukup Parah</div>
                        <div class="radio-card-desc">Skor 15-19: Sering</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="depression_score" id="dep_parah" value="25">
                    <label for="dep_parah">
                        <div class="radio-card-icon">😢</div>
                        <div class="radio-card-title">Parah</div>
                        <div class="radio-card-desc">Skor 20+: Sangat sering</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(7)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(9)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 9: Anxiety Score -->
        <div class="question-card" data-question="9">
            <div class="question-number">Pertanyaan 9 dari 13</div>
            <h3 class="question-title">Skor Kecemasan - Seberapa sering Anda merasa cemas/khawatir berlebihan?</h3>
            <p class="question-subtitle">Pilih kategori yang paling sesuai dengan kondisi Anda (GAD-7)</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="anxiety_score" id="anx_minimal" value="2" checked>
                    <label for="anx_minimal">
                        <div class="radio-card-icon">😌</div>
                        <div class="radio-card-title">Minimal</div>
                        <div class="radio-card-desc">Skor 0-4: Tenang</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="anxiety_score" id="anx_ringan" value="7">
                    <label for="anx_ringan">
                        <div class="radio-card-icon">🙂</div>
                        <div class="radio-card-title">Ringan</div>
                        <div class="radio-card-desc">Skor 5-9: Kadang cemas</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="anxiety_score" id="anx_sedang" value="12">
                    <label for="anx_sedang">
                        <div class="radio-card-icon">😟</div>
                        <div class="radio-card-title">Sedang</div>
                        <div class="radio-card-desc">Skor 10-14: Sering cemas</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="anxiety_score" id="anx_parah" value="20">
                    <label for="anx_parah">
                        <div class="radio-card-icon">😨</div>
                        <div class="radio-card-title">Parah</div>
                        <div class="radio-card-desc">Skor 15+: Sangat cemas</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(8)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(10)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 10: Sleep Hours -->
        <div class="question-card" data-question="10">
            <div class="question-number">Pertanyaan 10 dari 13</div>
            <h3 class="question-title">Berapa rata-rata jam tidur Anda per malam?</h3>
            <p class="question-subtitle">Ideal: 7-9 jam per malam untuk orang dewasa</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="sleep_hours" id="sleep_poor" value="4">
                    <label for="sleep_poor">
                        <div class="radio-card-icon">😴</div>
                        <div class="radio-card-title">< 5 jam</div>
                        <div class="radio-card-desc">Kurang tidur</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="sleep_hours" id="sleep_low" value="6">
                    <label for="sleep_low">
                        <div class="radio-card-icon">😪</div>
                        <div class="radio-card-title">5-6 jam</div>
                        <div class="radio-card-desc">Kurang ideal</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="sleep_hours" id="sleep_good" value="7.5" checked>
                    <label for="sleep_good">
                        <div class="radio-card-icon">😊</div>
                        <div class="radio-card-title">7-8 jam</div>
                        <div class="radio-card-desc">Ideal</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="sleep_hours" id="sleep_high" value="9">
                    <label for="sleep_high">
                        <div class="radio-card-icon">😴</div>
                        <div class="radio-card-title">9+ jam</div>
                        <div class="radio-card-desc">Banyak tidur</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(9)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(11)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 11: Physical Activity Days -->
        <div class="question-card" data-question="11">
            <div class="question-number">Pertanyaan 11 dari 13</div>
            <h3 class="question-title">Berapa hari dalam seminggu Anda melakukan aktivitas fisik/olahraga?</h3>
            <p class="question-subtitle">Termasuk jalan kaki, jogging, gym, olahraga ringan, berkebun, dll.</p>
            
            <div class="radio-card-group" style="grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));">
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_0" value="0">
                    <label for="activity_0">
                        <div class="radio-card-icon">🛌</div>
                        <div class="radio-card-title">0 hari</div>
                        <div class="radio-card-desc">Tidak aktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_1" value="1">
                    <label for="activity_1">
                        <div class="radio-card-title">1 hari</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_2" value="2">
                    <label for="activity_2">
                        <div class="radio-card-title">2 hari</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_3" value="3" checked>
                    <label for="activity_3">
                        <div class="radio-card-icon">🚶</div>
                        <div class="radio-card-title">3 hari</div>
                        <div class="radio-card-desc">Cukup aktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_4" value="4">
                    <label for="activity_4">
                        <div class="radio-card-title">4 hari</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_5" value="5">
                    <label for="activity_5">
                        <div class="radio-card-icon">🏃</div>
                        <div class="radio-card-title">5 hari</div>
                        <div class="radio-card-desc">Aktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_6" value="6">
                    <label for="activity_6">
                        <div class="radio-card-title">6 hari</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="physical_activity_days" id="activity_7" value="7">
                    <label for="activity_7">
                        <div class="radio-card-icon">💪</div>
                        <div class="radio-card-title">7 hari</div>
                        <div class="radio-card-desc">Sangat aktif</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(10)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(12)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 12: Social Support Score -->
        <div class="question-card" data-question="12">
            <div class="question-number">Pertanyaan 12 dari 13</div>
            <h3 class="question-title">Seberapa kuat dukungan sosial yang Anda miliki?</h3>
            <p class="question-subtitle">Apakah ada keluarga/teman yang bisa Anda andalkan saat butuh bantuan emosional?</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="social_support_score" id="social_very_low" value="10">
                    <label for="social_very_low">
                        <div class="radio-card-icon">😞</div>
                        <div class="radio-card-title">Sangat Rendah</div>
                        <div class="radio-card-desc">Merasa sangat sendirian</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="social_support_score" id="social_low" value="30">
                    <label for="social_low">
                        <div class="radio-card-icon">😕</div>
                        <div class="radio-card-title">Rendah</div>
                        <div class="radio-card-desc">Kurang dukungan</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="social_support_score" id="social_medium" value="50" checked>
                    <label for="social_medium">
                        <div class="radio-card-icon">🙂</div>
                        <div class="radio-card-title">Sedang</div>
                        <div class="radio-card-desc">Ada beberapa dukungan</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="social_support_score" id="social_high" value="75">
                    <label for="social_high">
                        <div class="radio-card-icon">😊</div>
                        <div class="radio-card-title">Tinggi</div>
                        <div class="radio-card-desc">Banyak dukungan</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="social_support_score" id="social_very_high" value="95">
                    <label for="social_very_high">
                        <div class="radio-card-icon">🤗</div>
                        <div class="radio-card-title">Sangat Tinggi</div>
                        <div class="radio-card-desc">Sangat didukung</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(11)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(13)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 13: Productivity Score -->
        <div class="question-card" data-question="13">
            <div class="question-number">Pertanyaan 13 dari 13</div>
            <h3 class="question-title">Bagaimana tingkat produktivitas Anda akhir-akhir ini?</h3>
            <p class="question-subtitle">Apakah Anda bisa menyelesaikan tugas-tugas sehari-hari dengan baik?</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="productivity_score" id="prod_very_low" value="10">
                    <label for="prod_very_low">
                        <div class="radio-card-icon">😫</div>
                        <div class="radio-card-title">Sangat Rendah</div>
                        <div class="radio-card-desc">Sulit menyelesaikan tugas</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="productivity_score" id="prod_low" value="30">
                    <label for="prod_low">
                        <div class="radio-card-icon">😕</div>
                        <div class="radio-card-title">Rendah</div>
                        <div class="radio-card-desc">Kurang produktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="productivity_score" id="prod_medium" value="50">
                    <label for="prod_medium">
                        <div class="radio-card-icon">😐</div>
                        <div class="radio-card-title">Sedang</div>
                        <div class="radio-card-desc">Cukup produktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="productivity_score" id="prod_high" value="70" checked>
                    <label for="prod_high">
                        <div class="radio-card-icon">😊</div>
                        <div class="radio-card-title">Tinggi</div>
                        <div class="radio-card-desc">Produktif</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="productivity_score" id="prod_very_high" value="90">
                    <label for="prod_very_high">
                        <div class="radio-card-icon">🚀</div>
                        <div class="radio-card-title">Sangat Tinggi</div>
                        <div class="radio-card-desc">Sangat produktif</div>
                    </label>
                </div>
            </div>

            <?php if (!empty($activeHospitals)): ?>
            <div class="hospital-integration-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="mb-1">Integrasi Rumah Sakit Mitra</h5>
                        <p class="mb-0 text-muted">Kirim hasil assessment langsung ke RS untuk tindak lanjut klinis.</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="shareHospitalToggle" name="share_with_hospital">
                        <label class="form-check-label" for="shareHospitalToggle">Aktifkan Rujukan</label>
                    </div>
                </div>

                <div id="hospitalFields" class="hospital-fields">
                    <div class="mb-3">
                        <label for="hospitalSelect" class="form-label">Pilih Rumah Sakit</label>
                        <select class="form-select" id="hospitalSelect" name="hospital_id">
                            <option value="">Pilih Rumah Sakit Mitra</option>
                            <?php foreach ($activeHospitals as $hospital): ?>
                                <option value="<?= htmlspecialchars($hospital['id']) ?>">
                                    <?= htmlspecialchars($hospital['name']) ?> (<?= htmlspecialchars($hospital['type'] ?? 'Mitra') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="hospital-facility-info" id="hospitalFacilityInfo"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="patientReference" class="form-label">ID Pasien / No. Rekam Medis (opsional)</label>
                            <input type="text" class="form-control" id="patientReference" name="patient_reference" placeholder="Contoh: MRN-2025-001">
                        </div>
                        <div class="col-md-6">
                            <label for="hospitalNotes" class="form-label">Catatan Klinis Ringkas</label>
                            <input type="text" class="form-control" id="hospitalNotes" name="hospital_notes" placeholder="Keluhan utama / context singkat">
                        </div>
                    </div>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-shield-lock me-2"></i> Data yang dikirimkan telah dienkripsi dan hanya berisi parameter assessment.
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(12)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-success btn-wizard" onclick="submitQuestionnaire()">
                    <i class="bi bi-check-circle"></i> Selesai & Lihat Hasil
                </button>
            </div>
        </div>
    </form>

    <!-- Results Card -->
    <div class="results-card" id="resultsCard">
        <div class="card">
            <div class="card-header py-3 text-center bg-primary text-white">
                <h5 class="m-0"><i class="bi bi-clipboard-check"></i> Hasil Analisis AI</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h6 class="text-muted mb-3">Tingkat Risiko Kesehatan Mental Anda:</h6>
                    <div class="risk-badge" id="riskBadge">Sedang</div>
                </div>

                <div class="confidence-display">
                    <div class="confidence-number" id="confidenceNumber">85</div>
                    <div class="confidence-label">Tingkat Keyakinan AI</div>
                </div>

                <div class="mb-4">
                    <h6 class="mb-3"><i class="bi bi-pie-chart"></i> Distribusi Probabilitas:</h6>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Risiko Rendah</span>
                            <span id="probLow">30%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" id="probLowBar" style="width: 30%"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Risiko Sedang</span>
                            <span id="probModerate">50%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" id="probModerateBar" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Risiko Tinggi</span>
                            <span id="probHigh">20%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" id="probHighBar" style="width: 20%"></div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="mb-3"><i class="bi bi-lightbulb"></i> Rekomendasi untuk Anda:</h6>
                    <div id="recommendationsContainer"></div>
                </div>

                <div class="mb-4" id="hospitalStatusBlock" style="display:none;">
                    <h6 class="mb-3"><i class="bi bi-hospital"></i> Status Integrasi Rumah Sakit</h6>
                    <div class="alert hospital-status-alert" id="hospitalStatusAlert"></div>
                </div>

                <div class="text-center">
                    <a href="history" class="btn btn-primary">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetQuestionnaire()">
                        <i class="bi bi-arrow-counterclockwise"></i> Mulai Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentQuestion = 1;
const totalQuestions = 13;

// Safely parse hospital options
let hospitalOptions = [];
try {
    const hospitalData = '<?php echo addslashes(json_encode($activeHospitals)); ?>';
    if (hospitalData && hospitalData !== '[]' && hospitalData !== '') {
        hospitalOptions = JSON.parse(hospitalData);
    }
} catch (e) {
    console.error('Failed to parse hospital options:', e);
    hospitalOptions = [];
}

const shareHospitalToggle = document.getElementById('shareHospitalToggle');
const hospitalFields = document.getElementById('hospitalFields');
const hospitalSelect = document.getElementById('hospitalSelect');
const hospitalInfo = document.getElementById('hospitalFacilityInfo');
const hospitalStatusBlock = document.getElementById('hospitalStatusBlock');
const hospitalStatusAlert = document.getElementById('hospitalStatusAlert');

function updateProgress() {
    const progress = ((currentQuestion - 1) / totalQuestions) * 100;
    document.getElementById('progressFill').style.width = progress + '%';
    
    // Update wizard steps
    document.querySelectorAll('.wizard-step').forEach(step => {
        const stepNum = parseInt(step.dataset.step);
        step.classList.remove('active', 'completed');
        
        if (stepNum < Math.ceil(currentQuestion / 2)) {
            step.classList.add('completed');
        } else if (stepNum === Math.ceil(currentQuestion / 2)) {
            step.classList.add('active');
        }
    });
}

function showQuestion(questionNum) {
    document.querySelectorAll('.question-card').forEach(card => {
        card.classList.remove('active');
    });
    
    const card = document.querySelector(`[data-question="${questionNum}"]`);
    if (card) {
        card.classList.add('active');
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    currentQuestion = questionNum;
    updateProgress();
}

function nextQuestion(nextNum) {
    showQuestion(nextNum);
}

function prevQuestion(prevNum) {
    showQuestion(prevNum);
}

function submitQuestionnaire() {
    // Show loading
    document.getElementById('loadingOverlay').classList.add('show');
    
    // Collect form data
    const formData = new FormData(document.getElementById('questionnaireForm'));
    
    // Convert to object with 13 fields matching the model
    const data = {
        age: formData.get('age'),
        gender: formData.get('gender'),
        employment_status: formData.get('employment_status'),
        work_environment: formData.get('work_environment'),
        mental_history: formData.get('mental_health_history'),
        seeks_treatment: formData.get('seeks_treatment'),
        stress: formData.get('stress_level'),
        depression: formData.get('depression_score'),
        anxiety: formData.get('anxiety_score'),
        sleep: formData.get('sleep_hours'),
        exercise: formData.get('physical_activity_days'),
        social_support: formData.get('social_support_score'),
        productivity: formData.get('productivity_score')
    };

    const shareWithHospital = shareHospitalToggle ? shareHospitalToggle.checked : false;
    data.share_with_hospital = shareWithHospital ? 'true' : 'false';
    if (shareWithHospital) {
        data.hospital_id = formData.get('hospital_id') || '';
        data.patient_reference = formData.get('patient_reference') || '';
        data.hospital_notes = formData.get('hospital_notes') || '';
    } else {
        data.hospital_id = '';
        data.patient_reference = '';
        data.hospital_notes = '';
    }
    
    // Send to server
    fetch('predict', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error: ' + response.status);
        }
        return response.json();
    })
    .then(result => {
        // Hide loading
        document.getElementById('loadingOverlay').classList.remove('show');
        
        console.log('=== SERVER RESPONSE DEBUG ===');
        console.log('Full response:', JSON.stringify(result, null, 2));
        console.log('prediction:', result.prediction);
        console.log('confidence:', result.confidence);
        console.log('probabilities:', result.probabilities);
        console.log('recommendations:', result.recommendations);
        console.log('===========================');
        
        if (result.success) {
            displayResults(result);
        } else {
            alert('Error: ' + (result.error || 'Unknown error'));
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').classList.remove('show');
        console.error('Error:', error); // Debug log
        alert('Terjadi kesalahan: ' + error.message);
    });
}

function displayResults(result) {
    // Update risk badge
    const riskBadge = document.getElementById('riskBadge');
    const prediction = result.prediction || 'Unknown';
    riskBadge.textContent = prediction;
    riskBadge.className = 'risk-badge';
    
    // Determine risk level with safe string handling
    const predictionLower = prediction.toString().toLowerCase();
    if (predictionLower.includes('low') || predictionLower.includes('rendah')) {
        riskBadge.classList.add('risk-low');
    } else if (predictionLower.includes('moderate') || predictionLower.includes('sedang') || predictionLower.includes('medium')) {
        riskBadge.classList.add('risk-moderate');
    } else {
        riskBadge.classList.add('risk-high');
    }
    
    // Update confidence
    const confidence = ((result.confidence || 0) * 100).toFixed(1);
    document.getElementById('confidenceNumber').textContent = confidence + '%';
    
    // Update probabilities - with safe fallback
    const probabilities = result.probabilities || {};
    
    // Handle different probability formats
    let probLow = 0, probModerate = 0, probHigh = 0;
    
    // If probabilities exist, categorize them
    if (Object.keys(probabilities).length > 0) {
        for (const [label, prob] of Object.entries(probabilities)) {
            const labelLower = label.toString().toLowerCase();
            const probValue = (prob || 0) * 100;
            
            if (labelLower.includes('low') || labelLower.includes('rendah') || labelLower.includes('normal')) {
                probLow += probValue;
            } else if (labelLower.includes('moderate') || labelLower.includes('sedang') || labelLower.includes('medium')) {
                probModerate += probValue;
            } else if (labelLower.includes('high') || labelLower.includes('tinggi') || 
                       labelLower.includes('depression') || labelLower.includes('anxiety') || 
                       labelLower.includes('stress') || labelLower.includes('suicidal') ||
                       labelLower.includes('bipolar') || labelLower.includes('personality')) {
                probHigh += probValue;
            }
        }
    }
    
    document.getElementById('probLow').textContent = probLow.toFixed(1) + '%';
    document.getElementById('probLowBar').style.width = probLow + '%';
    
    document.getElementById('probModerate').textContent = probModerate.toFixed(1) + '%';
    document.getElementById('probModerateBar').style.width = probModerate + '%';
    
    document.getElementById('probHigh').textContent = probHigh.toFixed(1) + '%';
    document.getElementById('probHighBar').style.width = probHigh + '%';
    
    // Update recommendations with safe handling
    const container = document.getElementById('recommendationsContainer');
    container.innerHTML = '';
    
    const recommendations = result.recommendations || [];
    if (recommendations.length > 0) {
        recommendations.forEach(rec => {
            const div = document.createElement('div');
            div.className = 'recommendation-item';
            div.innerHTML = `<i class="bi bi-check-circle-fill text-primary me-2"></i>${rec}`;
            container.appendChild(div);
        });
    } else {
        container.innerHTML = '<p class="text-muted">Tidak ada rekomendasi tersedia.</p>';
    }

    if (result.hospital_sync) {
        const hs = result.hospital_sync;
        let alertClass = 'alert-info';
        let iconClass = 'bi-hourglass-split';
        if (hs.success) {
            alertClass = 'alert-success';
            iconClass = 'bi-check-circle-fill';
        } else if (hs.status === 'QUEUED') {
            alertClass = 'alert-warning';
            iconClass = 'bi-clock-fill';
        } else if (hs.status === 'FAILED') {
            alertClass = 'alert-danger';
            iconClass = 'bi-exclamation-triangle-fill';
        }

        hospitalStatusAlert.className = 'alert hospital-status-alert ' + alertClass;
        hospitalStatusAlert.innerHTML = `
            <div><i class="${iconClass} me-2"></i><strong>${hs.message || 'Pengiriman data ke rumah sakit.'}</strong></div>
            ${hs.facility ? `<div class="mt-1">Rumah Sakit: ${hs.facility.name}</div>` : ''}
            ${hs.reference ? `<div class="mt-1 small">Referensi: <code>${hs.reference}</code></div>` : ''}
        `;
        hospitalStatusBlock.style.display = 'block';
    } else {
        hospitalStatusBlock.style.display = 'none';
    }
    
    // Hide form, show results
    document.getElementById('questionnaireForm').style.display = 'none';
    document.querySelector('.wizard-progress').style.display = 'none';
    document.getElementById('resultsCard').classList.add('show');
    document.getElementById('resultsCard').scrollIntoView({ behavior: 'smooth' });
}

function resetQuestionnaire() {
    // Reset form
    document.getElementById('questionnaireForm').reset();
    
    // Reset displays
    document.getElementById('ageDisplay').textContent = '25';
    document.getElementById('stressDisplay').textContent = '5';
    document.getElementById('anxietyDisplay').textContent = '5';
    document.getElementById('depressionDisplay').textContent = '5';
    document.getElementById('sleepDisplay').textContent = '7.0';
    
    // Show form, hide results
    document.getElementById('questionnaireForm').style.display = 'block';
    document.querySelector('.wizard-progress').style.display = 'block';
    document.getElementById('resultsCard').classList.remove('show');
    
    // Go to first question
    showQuestion(1);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialize
updateProgress();

if (shareHospitalToggle) {
    shareHospitalToggle.addEventListener('change', function () {
        if (this.checked) {
            hospitalFields.classList.add('show');
        } else {
            hospitalFields.classList.remove('show');
        }
    });
}

if (hospitalSelect && hospitalOptions.length > 0) {
    hospitalSelect.addEventListener('change', function () {
        const selectedId = this.value;
        if (!selectedId || !hospitalInfo) {
            if (hospitalInfo) hospitalInfo.innerHTML = '';
            return;
        }

        const facility = hospitalOptions.find(h => h.id === selectedId);
        if (facility) {
            hospitalInfo.innerHTML = `
                <i class="bi bi-geo-alt-fill me-1"></i> ${facility.location || 'Lokasi tidak diketahui'}<br>
                <i class="bi bi-telephone-fill me-1"></i> ${facility.contact_phone || '-'}
            `;
        } else {
            hospitalInfo.innerHTML = '';
        }
    });
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
