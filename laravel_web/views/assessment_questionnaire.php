<?php
$page = 'assessment';
$pageTitle = 'Kuesioner Kesehatan Mental';

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
    background: rgba(255,255,255,0.98);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.loading-overlay.show {
    display: flex;
}

.loading-content {
    text-align: center;
    max-width: 400px;
}

.loading-spinner {
    width: 80px;
    height: 80px;
    border: 6px solid #e9ecef;
    border-top: 6px solid #4e73df;
    border-right: 6px solid #1cc88a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 25px;
}

.loading-progress-bar {
    width: 100%;
    height: 6px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin: 20px 0;
}

.loading-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4e73df, #1cc88a);
    width: 0%;
    animation: loadProgress 3s ease-in-out forwards;
}

.loading-steps {
    text-align: left;
    margin-top: 20px;
}

.loading-step {
    display: flex;
    align-items: center;
    padding: 10px 0;
    font-size: 14px;
    color: #6c757d;
    opacity: 0.5;
    transition: all 0.3s;
}

.loading-step.active {
    color: #4e73df;
    opacity: 1;
    font-weight: 600;
}

.loading-step.completed {
    color: #1cc88a;
    opacity: 1;
}

.loading-step i {
    margin-right: 10px;
    font-size: 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes loadProgress {
    0% { width: 0%; }
    25% { width: 30%; }
    50% { width: 60%; }
    75% { width: 85%; }
    100% { width: 100%; }
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
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <h4 class="mb-2">🤖 AI Sedang Menganalisis...</h4>
        <p class="text-muted mb-3">Mohon tunggu sebentar</p>
        
        <div class="loading-progress-bar">
            <div class="loading-progress-fill"></div>
        </div>
        
        <div class="loading-steps">
            <div class="loading-step" id="step1">
                <i class="bi bi-circle-fill"></i>
                <span>Memproses data Anda...</span>
            </div>
            <div class="loading-step" id="step2">
                <i class="bi bi-circle-fill"></i>
                <span>Menjalankan model AI...</span>
            </div>
            <div class="loading-step" id="step3">
                <i class="bi bi-circle-fill"></i>
                <span>Menganalisis pola kesehatan mental...</span>
            </div>
            <div class="loading-step" id="step4">
                <i class="bi bi-circle-fill"></i>
                <span>Menyiapkan rekomendasi...</span>
            </div>
        </div>
    </div>
</div>

<div class="questionnaire-container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <i class="bi bi-heart-pulse" style="font-size: 48px; margin-bottom: 20px;"></i>
        <h2>Kuesioner Kesehatan Mental</h2>
        <p>Jawab 8 pertanyaan singkat untuk mendapatkan analisis AI tentang kondisi kesehatan mental Anda</p>
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
            <div class="question-number">Pertanyaan 1 dari 8</div>
            <h3 class="question-title">Berapa usia Anda?</h3>
            <p class="question-subtitle">Usia membantu kami memberikan rekomendasi yang lebih personal</p>
            
            <div class="slider-container">
                <div class="slider-value-display">
                    <div class="slider-value-number" id="ageDisplay">25</div>
                    <div class="slider-value-label">tahun</div>
                </div>
                <input type="range" class="form-range" name="age" id="age" 
                       min="18" max="80" value="25" 
                       oninput="document.getElementById('ageDisplay').textContent = this.value">
                <div class="slider-labels">
                    <span>18 tahun</span>
                    <span>80 tahun</span>
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

        <!-- Question 2: Mental History -->
        <div class="question-card" data-question="2">
            <div class="question-number">Pertanyaan 2 dari 8</div>
            <h3 class="question-title">Apakah Anda pernah didiagnosis dengan masalah kesehatan mental?</h3>
            <p class="question-subtitle">Riwayat kesehatan mental membantu kami memahami kondisi Anda</p>
            
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
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(1)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="nextQuestion(3)">
                    Lanjut <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Question 3: Stress Level -->
        <div class="question-card" data-question="3">
            <div class="question-number">Pertanyaan 3 dari 8</div>
            <h3 class="question-title">Seberapa tinggi tingkat stres Anda saat ini?</h3>
            <p class="question-subtitle">Skala 0 (tidak ada stres) hingga 10 (sangat stres)</p>
            
            <div class="slider-container">
                <div class="slider-value-display">
                    <div class="slider-value-number" id="stressDisplay">5</div>
                    <div class="slider-value-label">dari 10</div>
                </div>
                <input type="range" class="form-range" name="stress" id="stress" 
                       min="0" max="10" value="5" 
                       oninput="document.getElementById('stressDisplay').textContent = this.value">
                <div class="slider-labels">
                    <span>😊 Tidak Stres</span>
                    <span>😰 Sangat Stres</span>
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

        <!-- Question 4: Anxiety Level -->
        <div class="question-card" data-question="4">
            <div class="question-number">Pertanyaan 4 dari 8</div>
            <h3 class="question-title">Seberapa sering Anda merasa cemas atau khawatir?</h3>
            <p class="question-subtitle">Skala 0 (tidak pernah) hingga 10 (sangat sering)</p>
            
            <div class="slider-container">
                <div class="slider-value-display">
                    <div class="slider-value-number" id="anxietyDisplay">5</div>
                    <div class="slider-value-label">dari 10</div>
                </div>
                <input type="range" class="form-range" name="anxiety" id="anxiety" 
                       min="0" max="10" value="5" 
                       oninput="document.getElementById('anxietyDisplay').textContent = this.value">
                <div class="slider-labels">
                    <span>😌 Tenang</span>
                    <span>😟 Sangat Cemas</span>
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

        <!-- Question 5: Depression Level -->
        <div class="question-card" data-question="5">
            <div class="question-number">Pertanyaan 5 dari 8</div>
            <h3 class="question-title">Seberapa sering Anda merasa sedih atau murung?</h3>
            <p class="question-subtitle">Skala 0 (tidak pernah) hingga 10 (sangat sering)</p>
            
            <div class="slider-container">
                <div class="slider-value-display">
                    <div class="slider-value-number" id="depressionDisplay">5</div>
                    <div class="slider-value-label">dari 10</div>
                </div>
                <input type="range" class="form-range" name="depression" id="depression" 
                       min="0" max="10" value="5" 
                       oninput="document.getElementById('depressionDisplay').textContent = this.value">
                <div class="slider-labels">
                    <span>😊 Bahagia</span>
                    <span>😢 Sangat Sedih</span>
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

        <!-- Question 6: Sleep Hours -->
        <div class="question-card" data-question="6">
            <div class="question-number">Pertanyaan 6 dari 8</div>
            <h3 class="question-title">Berapa jam Anda tidur rata-rata per hari?</h3>
            <p class="question-subtitle">Kualitas tidur sangat mempengaruhi kesehatan mental</p>
            
            <div class="slider-container">
                <div class="slider-value-display">
                    <div class="slider-value-number" id="sleepDisplay">7.0</div>
                    <div class="slider-value-label">jam per hari</div>
                </div>
                <input type="range" class="form-range" name="sleep" id="sleep" 
                       min="0" max="12" step="0.5" value="7" 
                       oninput="document.getElementById('sleepDisplay').textContent = this.value">
                <div class="slider-labels">
                    <span>😴 0 jam</span>
                    <span>😴 12 jam</span>
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

        <!-- Question 7: Exercise Level -->
        <div class="question-card" data-question="7">
            <div class="question-number">Pertanyaan 7 dari 8</div>
            <h3 class="question-title">Seberapa sering Anda berolahraga?</h3>
            <p class="question-subtitle">Aktivitas fisik membantu menjaga kesehatan mental</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="exercise" id="exercise_low" value="Low" checked>
                    <label for="exercise_low">
                        <div class="radio-card-icon">🚶</div>
                        <div class="radio-card-title">Jarang</div>
                        <div class="radio-card-desc">< 1x seminggu</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="exercise" id="exercise_medium" value="Medium">
                    <label for="exercise_medium">
                        <div class="radio-card-icon">🏃</div>
                        <div class="radio-card-title">Sedang</div>
                        <div class="radio-card-desc">1-3x seminggu</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="exercise" id="exercise_high" value="High">
                    <label for="exercise_high">
                        <div class="radio-card-icon">💪</div>
                        <div class="radio-card-title">Sering</div>
                        <div class="radio-card-desc">> 3x seminggu</div>
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

        <!-- Question 8: Social Support -->
        <div class="question-card" data-question="8">
            <div class="question-number">Pertanyaan 8 dari 8</div>
            <h3 class="question-title">Apakah Anda memiliki dukungan sosial yang baik?</h3>
            <p class="question-subtitle">Keluarga, teman, atau orang terdekat yang dapat Anda andalkan</p>
            
            <div class="radio-card-group">
                <div class="radio-card">
                    <input type="radio" name="social_support" id="support_no" value="No">
                    <label for="support_no">
                        <div class="radio-card-icon">😞</div>
                        <div class="radio-card-title">Tidak</div>
                        <div class="radio-card-desc">Saya merasa sendirian</div>
                    </label>
                </div>
                <div class="radio-card">
                    <input type="radio" name="social_support" id="support_yes" value="Yes" checked>
                    <label for="support_yes">
                        <div class="radio-card-icon">🤗</div>
                        <div class="radio-card-title">Ya</div>
                        <div class="radio-card-desc">Saya memiliki dukungan</div>
                    </label>
                </div>
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(7)">
                    <i class="bi bi-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary btn-wizard" onclick="showReview()">
                    <i class="bi bi-eye"></i> Review Jawaban
                </button>
            </div>
        </div>

        <!-- Review Page -->
        <div class="question-card" data-question="9" id="reviewPage">
            <div class="question-number">Review & Konfirmasi</div>
            <h3 class="question-title">Periksa Kembali Jawaban Anda</h3>
            <p class="question-subtitle">Pastikan semua informasi sudah benar sebelum melanjutkan</p>
            
            <div class="card">
                <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="bi bi-person-circle"></i> Profil & Riwayat</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" style="width: 200px;">Usia</td>
                            <td class="fw-bold" id="review_age">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(1)">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Riwayat Mental</td>
                            <td class="fw-bold" id="review_mental">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(2)">Edit</button></td>
                        </tr>
                    </table>

                    <hr>
                    <h6 class="text-primary mb-3"><i class="bi bi-heart-pulse"></i> Kondisi Emosional</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" style="width: 200px;">Tingkat Stres</td>
                            <td class="fw-bold" id="review_stress">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(3)">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tingkat Kecemasan</td>
                            <td class="fw-bold" id="review_anxiety">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(4)">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tingkat Depresi</td>
                            <td class="fw-bold" id="review_depression">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(5)">Edit</button></td>
                        </tr>
                    </table>

                    <hr>
                    <h6 class="text-primary mb-3"><i class="bi bi-activity"></i> Gaya Hidup & Sosial</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" style="width: 200px;">Jam Tidur</td>
                            <td class="fw-bold" id="review_sleep">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(6)">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tingkat Olahraga</td>
                            <td class="fw-bold" id="review_exercise">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(7)">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dukungan Sosial</td>
                            <td class="fw-bold" id="review_social">-</td>
                            <td class="text-end"><button class="btn btn-sm btn-link" onclick="showQuestion(8)">Edit</button></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> Setelah Anda submit, AI kami akan menganalisis jawaban Anda dan memberikan hasil dalam beberapa detik.
            </div>
            
            <div class="wizard-navigation">
                <button type="button" class="btn btn-secondary btn-wizard" onclick="prevQuestion(8)">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-success btn-wizard" onclick="submitQuestionnaire()">
                    <i class="bi bi-check-circle"></i> Submit & Lihat Hasil
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
const totalQuestions = 8;

function updateProgress() {
    const progress = ((currentQuestion - 1) / totalQuestions) * 100;
    document.getElementById('progressFill').style.width = progress + '%';
    
    // Update wizard steps based on question groups
    document.querySelectorAll('.wizard-step').forEach(step => {
        const stepNum = parseInt(step.dataset.step);
        step.classList.remove('active', 'completed');
        
        // Step 1: Q1-2 (Profil)
        // Step 2: Q3-5 (Emosi)
        // Step 3: Q6-8 (Gaya Hidup)
        // Step 4: Q9 (Review/Hasil)
        
        if ((stepNum === 1 && currentQuestion > 2) ||
            (stepNum === 2 && currentQuestion > 5) ||
            (stepNum === 3 && currentQuestion > 8)) {
            step.classList.add('completed');
        } else if ((stepNum === 1 && currentQuestion <= 2) ||
                   (stepNum === 2 && currentQuestion >= 3 && currentQuestion <= 5) ||
                   (stepNum === 3 && currentQuestion >= 6 && currentQuestion <= 8) ||
                   (stepNum === 4 && currentQuestion === 9)) {
            step.classList.add('active');
        }
    });
}

function showReview() {
    // Collect all form data
    const formData = new FormData(document.getElementById('questionnaireForm'));
    
    // Update review page
    document.getElementById('review_age').textContent = formData.get('age') + ' tahun';
    document.getElementById('review_mental').textContent = formData.get('mental_history') === 'Yes' ? 'Ada riwayat' : 'Tidak ada riwayat';
    document.getElementById('review_stress').textContent = formData.get('stress') + '/10';
    document.getElementById('review_anxiety').textContent = formData.get('anxiety') + '/10';
    document.getElementById('review_depression').textContent = formData.get('depression') + '/10';
    document.getElementById('review_sleep').textContent = formData.get('sleep') + ' jam/hari';
    
    const exercise = formData.get('exercise');
    const exerciseText = exercise === 'Low' ? 'Rendah' : exercise === 'Moderate' ? 'Sedang' : 'Tinggi';
    document.getElementById('review_exercise').textContent = exerciseText;
    
    document.getElementById('review_social').textContent = formData.get('social_support') === 'Yes' ? 'Ada dukungan' : 'Tidak ada dukungan';
    
    // Show review page
    showQuestion(9);
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
    // Show loading with animation
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.add('show');
    
    // Animate loading steps
    const steps = ['step1', 'step2', 'step3', 'step4'];
    steps.forEach((stepId, index) => {
        setTimeout(() => {
            document.getElementById(stepId).classList.add('active');
            
            // Mark previous as completed
            if (index > 0) {
                document.getElementById(steps[index - 1]).classList.remove('active');
                document.getElementById(steps[index - 1]).classList.add('completed');
            }
            
            // Mark last as completed
            if (index === steps.length - 1) {
                setTimeout(() => {
                    document.getElementById(stepId).classList.remove('active');
                    document.getElementById(stepId).classList.add('completed');
                }, 500);
            }
        }, index * 700);
    });
    
    // Collect form data
    const formData = new FormData(document.getElementById('questionnaireForm'));
    
    // Convert to object
    const data = {
        age: formData.get('age'),
        stress: formData.get('stress'),
        anxiety: formData.get('anxiety'),
        depression: formData.get('depression'),
        mental_history: formData.get('mental_history'),
        sleep: formData.get('sleep'),
        exercise: formData.get('exercise'),
        social_support: formData.get('social_support')
    };
    
    // Send to server
    fetch('predict', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data)
    })
    .then(response => response.json())
    .then(result => {
        // Wait for animation to complete
        setTimeout(() => {
            overlay.classList.remove('show');
            
            // Reset loading steps
            steps.forEach(stepId => {
                document.getElementById(stepId).classList.remove('active', 'completed');
            });
            
            if (result.success) {
                displayResults(result);
            } else {
                alert('Error: ' + result.error);
            }
        }, 3000); // Wait for animation
    })
    .catch(error => {
        setTimeout(() => {
            overlay.classList.remove('show');
            
            // Reset loading steps
            steps.forEach(stepId => {
                document.getElementById(stepId).classList.remove('active', 'completed');
            });
            
            alert('Terjadi kesalahan: ' + error.message);
        }, 1000);
    });
}

function displayResults(result) {
    // Update risk badge
    const riskBadge = document.getElementById('riskBadge');
    riskBadge.textContent = result.prediction;
    riskBadge.className = 'risk-badge';
    
    if (result.prediction.toLowerCase().includes('low') || result.prediction.toLowerCase().includes('rendah')) {
        riskBadge.classList.add('risk-low');
    } else if (result.prediction.toLowerCase().includes('moderate') || result.prediction.toLowerCase().includes('sedang')) {
        riskBadge.classList.add('risk-moderate');
    } else {
        riskBadge.classList.add('risk-high');
    }
    
    // Update confidence
    const confidence = (result.confidence * 100).toFixed(1);
    document.getElementById('confidenceNumber').textContent = confidence + '%';
    
    // Update probabilities
    const probabilities = result.probabilities;
    
    document.getElementById('probLow').textContent = (probabilities.low * 100).toFixed(1) + '%';
    document.getElementById('probLowBar').style.width = (probabilities.low * 100) + '%';
    
    document.getElementById('probModerate').textContent = (probabilities.moderate * 100).toFixed(1) + '%';
    document.getElementById('probModerateBar').style.width = (probabilities.moderate * 100) + '%';
    
    document.getElementById('probHigh').textContent = (probabilities.high * 100).toFixed(1) + '%';
    document.getElementById('probHighBar').style.width = (probabilities.high * 100) + '%';
    
    // Update recommendations
    const container = document.getElementById('recommendationsContainer');
    container.innerHTML = '';
    
    result.recommendations.forEach(rec => {
        const div = document.createElement('div');
        div.className = 'recommendation-item';
        div.innerHTML = `<i class="bi bi-check-circle-fill text-primary me-2"></i>${rec}`;
        container.appendChild(div);
    });
    
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
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
