<?php
$page = 'assessment';
$pageTitle = 'Cek Kesehatan Mental';

ob_start();
?>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Akurasi Model</div>
                    <div class="stat-value">86.4%</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-check-circle stat-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Data Pelatihan</div>
                    <div class="stat-value">5,000</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-database stat-icon text-success"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Waktu Respon</div>
                    <div class="stat-value">&lt;100ms</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-lightning stat-icon text-info"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Pemeriksaan Hari Ini</div>
                    <div class="stat-value"><?= count($_SESSION['predictions'] ?? []) ?></div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-graph-up stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assessment Form Card -->
<div class="card">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Form Pemeriksaan Kesehatan Mental</h6>
        <span class="badge bg-primary">Berbasis AI</span>
    </div>
    <div class="card-body">
        <form id="assessmentForm">
            <div class="row">
                <!-- Demographics Section -->
                <div class="col-md-4">
                    <h6 class="text-primary mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-circle me-2"></i>Demografi
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Usia <span class="range-value" id="ageValue">25</span> tahun
                        </label>
                        <input type="range" class="form-range" name="age" id="age" 
                               min="18" max="80" value="25" oninput="updateValue('age', this.value)">
                        <small class="text-muted">Rentang: 18-80 tahun</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Riwayat Masalah Kesehatan Mental</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="mental_history" id="history_no" value="No" checked>
                            <label class="btn btn-outline-primary" for="history_no">Tidak</label>
                            
                            <input type="radio" class="btn-check" name="mental_history" id="history_yes" value="Yes">
                            <label class="btn btn-outline-primary" for="history_yes">Ya</label>
                        </div>
                    </div>
                </div>
                
                <!-- Mental Health Indicators Section -->
                <div class="col-md-4">
                    <h6 class="text-danger mb-3 pb-2 border-bottom">
                        <i class="bi bi-heart-pulse-fill me-2"></i>Indikator Kesehatan Mental
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Tingkat Stres <span class="range-value" id="stressValue">5</span>/10
                        </label>
                        <input type="range" class="form-range" name="stress" id="stress" 
                               min="0" max="10" value="5" oninput="updateValue('stress', this.value)">
                        <small class="text-muted">0 = Tidak Ada, 10 = Parah</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Tingkat Kecemasan <span class="range-value" id="anxietyValue">5</span>/10
                        </label>
                        <input type="range" class="form-range" name="anxiety" id="anxiety" 
                               min="0" max="10" value="5" oninput="updateValue('anxiety', this.value)">
                        <small class="text-muted">0 = Tidak Ada, 10 = Parah</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Skor Depresi <span class="range-value" id="depressionValue">5</span>/10
                        </label>
                        <input type="range" class="form-range" name="depression" id="depression" 
                               min="0" max="10" value="5" oninput="updateValue('depression', this.value)">
                        <small class="text-muted">0 = Tidak Ada, 10 = Parah</small>
                    </div>
                </div>
                
                <!-- Lifestyle Factors Section -->
                <div class="col-md-4">
                    <h6 class="text-success mb-3 pb-2 border-bottom">
                        <i class="bi bi-activity me-2"></i>Faktor Gaya Hidup
                    </h6>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Durasi Tidur <span class="range-value" id="sleepValue">7.0</span> jam
                        </label>
                        <input type="range" class="form-range" name="sleep" id="sleep" 
                               min="0" max="12" step="0.5" value="7" oninput="updateValue('sleep', this.value)">
                        <small class="text-muted">Rata-rata jam per malam</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tingkat Aktivitas Fisik</label>
                        <select class="form-select" name="exercise">
                            <option value="Low">Rendah (&lt;1x/minggu)</option>
                            <option value="Medium" selected>Sedang (2-3x/minggu)</option>
                            <option value="High">Tinggi (4+x/minggu)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Dukungan Sosial yang Kuat</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="social_support" id="support_yes" value="Yes" checked>
                            <label class="btn btn-outline-success" for="support_yes">Ya</label>
                            
                            <input type="radio" class="btn-check" name="social_support" id="support_no" value="No">
                            <label class="btn btn-outline-success" for="support_no">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-cpu"></i> Mulai Pemeriksaan
                </button>
                <button type="reset" class="btn btn-outline-secondary btn-lg px-4 ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Card -->
<div class="card mt-4" id="resultCard" style="display: none;">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-clipboard-data"></i> Hasil Pemeriksaan
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: var(--light);">
                    <div class="card-body text-center" id="predictionCard">
                        <h4 id="predictionText" class="mb-2"></h4>
                        <p class="text-muted mb-0">Hasil Prediksi</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Tingkat Keyakinan:</span>
                            <strong id="confidenceText" class="text-primary"></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8 mb-3">
                <h6 class="mb-3 text-dark fw-bold">Rekomendasi</h6>
                <div id="recommendationsList"></div>
            </div>
        </div>
        
        <div class="mt-4">
            <h6 class="mb-3 text-dark fw-bold">Distribusi Probabilitas Risiko</h6>
            <div class="row">
                <div class="col-md-6">
                    <div id="probabilitiesChart"></div>
                </div>
                <div class="col-md-6">
                    <canvas id="riskPieChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Important Notice -->
<div class="alert alert-warning border-left-warning mt-4" role="alert">
    <h6 class="alert-heading">
        <i class="bi bi-exclamation-triangle-fill"></i> Pernyataan Penting
    </h6>
    <p class="mb-2">Alat skrining berbasis AI ini hanya untuk tujuan edukasi.</p>
    <ul class="mb-2">
        <li>BUKAN pengganti diagnosis medis profesional</li>
        <li>BUKAN pengganti konsultasi dengan profesional kesehatan mental</li>
        <li>TIDAK boleh digunakan untuk situasi darurat</li>
    </ul>
    <hr>
    <p class="mb-0 small">
        <strong>Bantuan Darurat:</strong> Crisis Line 119 ext.8 • Hotline 1500-567 (24/7)
    </p>
</div>

<?php
$content = ob_get_clean();

$extraScripts = <<<'SCRIPT'
<script>
    let isSubmitting = false;
    
    document.getElementById('assessmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Prevent duplicate submissions
        if (isSubmitting) {
            console.log('Already submitting, ignoring duplicate request');
            return;
        }
        
        isSubmitting = true;
        console.log('Form submitted');
        
        // Disable submit button to prevent double submission
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        showLoading();
        document.getElementById('resultCard').style.display = 'none';
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('predict', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                displayResults(result.data);
            } else {
                alert('Terjadi kesalahan: ' + result.error);
            }
        } catch (error) {
            alert('Kesalahan koneksi: ' + error.message);
        } finally {
            hideLoading();
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            isSubmitting = false;
        }
    });
    
    function displayResults(data) {
        document.getElementById('resultCard').style.display = 'block';
        
        const predictionCard = document.getElementById('predictionCard');
        const colors = {
            'success': 'success',
            'warning': 'warning',
            'danger': 'danger'
        };
        
        predictionCard.style.background = `var(--${colors[data.color]})`;
        predictionCard.style.color = 'white';
        
        document.getElementById('predictionText').textContent = data.prediction;
        document.getElementById('confidenceText').textContent = (data.confidence * 100).toFixed(1) + '%';
        
        const recommendationsList = document.getElementById('recommendationsList');
        recommendationsList.innerHTML = data.recommendations.map(rec => `
            <div class="alert alert-light border-start border-4 border-primary mb-2 py-2">
                <small>${rec}</small>
            </div>
        `).join('');
        
        const probabilitiesChart = document.getElementById('probabilitiesChart');
        probabilitiesChart.innerHTML = '<div class="row">';
        
        for (const [risk, prob] of Object.entries(data.probabilities)) {
            const percentage = (prob * 100).toFixed(1);
            const barColor = risk.includes('Low') ? 'success' : risk.includes('High') ? 'danger' : 'warning';
            
            probabilitiesChart.innerHTML += `
                <div class="col-md-4 mb-2">
                    <div class="small text-muted mb-1">${risk}</div>
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-${barColor}" role="progressbar" 
                             style="width: ${percentage}%" 
                             aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100">
                            <strong>${percentage}%</strong>
                        </div>
                    </div>
                </div>
            `;
        }
        
        probabilitiesChart.innerHTML += '</div>';
        
        // Create Pie Chart
        createRiskPieChart(data.probabilities);
        
        document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    let riskChart = null;
    
    function createRiskPieChart(probabilities) {
        const ctx = document.getElementById('riskPieChart');
        
        // Destroy previous chart if exists
        if (riskChart) {
            riskChart.destroy();
        }
        
        const labels = Object.keys(probabilities);
        const data = Object.values(probabilities).map(v => (v * 100).toFixed(1));
        const colors = labels.map(label => {
            if (label.includes('Low')) return 'rgba(28, 200, 138, 0.8)';
            if (label.includes('High')) return 'rgba(231, 74, 59, 0.8)';
            return 'rgba(246, 194, 62, 0.8)';
        });
        const borderColors = labels.map(label => {
            if (label.includes('Low')) return 'rgba(28, 200, 138, 1)';
            if (label.includes('High')) return 'rgba(231, 74, 59, 1)';
            return 'rgba(246, 194, 62, 1)';
        });
        
        riskChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderColor: borderColors,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Risiko',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    }
                }
            }
        });
    }
</script>
SCRIPT;

$title = 'Cek Kesehatan Mental - Mental Health Predictor';
require __DIR__ . '/layout.php';
?>
