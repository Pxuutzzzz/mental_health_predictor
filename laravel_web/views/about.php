<?php
$page = 'about';
$pageTitle = 'Tentang Sistem';

ob_start();
?>

<style>
.info-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.info-card h5 {
    color: #4e73df;
    font-weight: 700;
    margin-bottom: 20px;
}

.metric-card {
    text-align: center;
    padding: 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    margin-bottom: 20px;
}

.metric-number {
    font-size: 48px;
    font-weight: 700;
    line-height: 1;
}

.metric-label {
    font-size: 14px;
    opacity: 0.9;
    margin-top: 10px;
}

.algorithm-badge {
    display: inline-block;
    padding: 8px 16px;
    background: #e7f3ff;
    color: #4e73df;
    border-radius: 20px;
    font-weight: 600;
    margin: 5px;
}

.feature-item {
    padding: 15px;
    background: #f8f9fc;
    border-left: 4px solid #4e73df;
    border-radius: 5px;
    margin-bottom: 15px;
}

.comparison-table {
    width: 100%;
    margin-top: 20px;
}

.comparison-table th {
    background: #4e73df;
    color: white;
    padding: 12px;
    text-align: center;
}

.comparison-table td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #e3e6f0;
}

.tech-stack-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border: 2px solid #e3e6f0;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.tech-stack-item:hover {
    border-color: #4e73df;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
}

.tech-icon {
    font-size: 32px;
    margin-right: 15px;
    color: #4e73df;
}
</style>

<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h2 class="fw-bold mb-3">🤖 Mental Health Prediction System</h2>
                <p class="lead mb-0">Sistem Prediksi Kesehatan Mental Berbasis Artificial Intelligence</p>
            </div>
        </div>
    </div>

    <!-- Metrics Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-number">78.7%</div>
                <div class="metric-label">Akurasi Model</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);">
                <div class="metric-number">94K+</div>
                <div class="metric-label">Dataset Records</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                <div class="metric-number">13</div>
                <div class="metric-label">Parameter Input</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card" style="background: linear-gradient(135deg, #e74a3b 0%, #d32e1f 100%);">
                <div class="metric-number">3</div>
                <div class="metric-label">Kategori Risiko</div>
            </div>
        </div>
    </div>

    <!-- Algorithm Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="info-card">
                <h5><i class="bi bi-cpu"></i> Algoritma Machine Learning</h5>
                
                <h6 class="mt-4 mb-3">Random Forest Classifier</h6>
                <p>Sistem kami menggunakan <strong>Random Forest Classifier</strong>, sebuah algoritma ensemble learning yang menggabungkan multiple decision trees untuk menghasilkan prediksi yang lebih akurat dan robust.</p>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> <strong>Kenapa Random Forest?</strong><br>
                    Random Forest dipilih karena kemampuannya menangani data non-linear, resistant terhadap overfitting, dan dapat memberikan feature importance untuk interpretability.
                </div>

                <h6 class="mt-4 mb-3">Spesifikasi Model</h6>
                <table class="table table-bordered">
                    <tr>
                        <td class="fw-bold" style="width: 250px;">Algoritma</td>
                        <td>Random Forest Classifier</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Number of Estimators</td>
                        <td>100 decision trees</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Max Depth</td>
                        <td>10 levels</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Min Samples Split</td>
                        <td>2 samples</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Min Samples Leaf</td>
                        <td>1 sample</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Random State</td>
                        <td>42 (for reproducibility)</td>
                    </tr>
                </table>

                <h6 class="mt-4 mb-3">Cara Kerja Model</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="feature-item">
                            <strong>1. Input Processing</strong>
                            <p class="mb-0 small mt-2">13 parameter (demografi, riwayat kesehatan mental, skor emosi/mental, gaya hidup, produktivitas) diproses menggunakan label encoding dan feature scaling</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-item">
                            <strong>2. Ensemble Prediction</strong>
                            <p class="mb-0 small mt-2">100 decision trees memberikan vote untuk menentukan kategori risiko</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-item">
                            <strong>3. Probability Calculation</strong>
                            <p class="mb-0 small mt-2">Model menghitung probabilitas untuk setiap kategori (Low, Moderate, High Risk)</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-item">
                            <strong>4. Output & Recommendations</strong>
                            <p class="mb-0 small mt-2">Hasil prediksi disertai confidence score dan rekomendasi personal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-card">
                <h5><i class="bi bi-graph-up"></i> Performa Model</h5>
                
                <h6 class="mt-4">Training Data</h6>
                <p class="small">Model dilatih menggunakan dataset kesehatan mental dengan <strong>94,024 records</strong>. Data dibagi dengan train/test split 80/20 (random_state=42).</p>
                
                <table class="table table-sm table-borderless mt-3">
                    <tr>
                        <td class="fw-bold" style="width: 150px;">Total Dataset</td>
                        <td>94,024 records</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Training Set</td>
                        <td>75,219 records (80%)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Test Set</td>
                        <td>18,805 records (20%)</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Features</td>
                        <td>13 parameters</td>
                    </tr>
                </table>

                <h6 class="mt-3">Metrics</h6>
                <table class="table table-sm">
                    <tr>
                        <td class="fw-bold">Accuracy</td>
                        <td class="text-end">78.67%</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Precision</td>
                        <td class="text-end">78.18%</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Recall</td>
                        <td class="text-end">78.67%</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">F1-Score</td>
                        <td class="text-end">78.31%</td>
                    </tr>
                </table>

                <h6 class="mt-4">Model Training</h6>
                <p class="small">Model dilatih menggunakan Random Forest dengan 100 trees dan max_depth=10. Training dilakukan dengan train/test split 80/20.</p>

                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle"></i> Model dalam tahap pengembangan dan terus ditingkatkan
                </div>
            </div>

            <div class="info-card">
                <h5><i class="bi bi-shield-check"></i> Validasi & Testing</h5>
                <ul class="small">
                    <li><strong>Train/Test Split:</strong> 80/20</li>
                    <li><strong>Stratified Sampling:</strong> Menjaga distribusi kelas</li>
                    <li><strong>Hyperparameter Tuning:</strong> Grid Search CV</li>
                    <li><strong>Feature Scaling:</strong> StandardScaler</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Feature Importance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h5><i class="bi bi-bar-chart"></i> Feature Importance Analysis</h5>
                <p>Berdasarkan analisis Random Forest dengan 13 parameter input, berikut adalah tingkat pengaruh setiap parameter terhadap prediksi kesehatan mental:</p>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Parameter Mental & Emosional (Paling Berpengaruh)</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>1. Depression Score (PHQ-9)</span>
                                <span class="fw-bold">22.5%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" style="width: 22.5%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>2. Anxiety Score (GAD-7)</span>
                                <span class="fw-bold">19.8%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-warning" style="width: 19.8%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>3. Stress Level</span>
                                <span class="fw-bold">16.2%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: 16.2%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>4. Mental Health History</span>
                                <span class="fw-bold">11.5%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary" style="width: 11.5%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>5. Seeks Treatment</span>
                                <span class="fw-bold">8.3%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-secondary" style="width: 8.3%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Parameter Gaya Hidup & Demografi</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>6. Productivity Score</span>
                                <span class="fw-bold">6.8%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: 6.8%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>7. Sleep Hours</span>
                                <span class="fw-bold">5.2%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: 5.2%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>8. Social Support Score</span>
                                <span class="fw-bold">3.9%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: 3.9%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>9. Physical Activity Days</span>
                                <span class="fw-bold">2.7%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: 2.7%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>10-13. Demographics (Age, Gender, Employment, Work Env)</span>
                                <span class="fw-bold">3.1%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: 3.1%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <i class="bi bi-lightbulb"></i> <strong>Insight:</strong> Parameter mental & emosional (depression, anxiety, stress) memiliki pengaruh terbesar (58.5%) dalam memprediksi risiko kesehatan mental, diikuti oleh riwayat kesehatan mental dan perilaku mencari bantuan profesional.
                </div>
            </div>
        </div>
    </div>

    <!-- Technology Stack -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h5><i class="bi bi-stack"></i> Technology Stack</h5>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Backend & AI</h6>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🐍</div>
                            <div>
                                <strong>Python 3.x</strong>
                                <p class="mb-0 small text-muted">Core language untuk ML pipeline</p>
                            </div>
                        </div>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🤖</div>
                            <div>
                                <strong>Scikit-learn</strong>
                                <p class="mb-0 small text-muted">Machine Learning library</p>
                            </div>
                        </div>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🔢</div>
                            <div>
                                <strong>NumPy & Pandas</strong>
                                <p class="mb-0 small text-muted">Data processing & manipulation</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Frontend & Web</h6>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🐘</div>
                            <div>
                                <strong>PHP 7.4+</strong>
                                <p class="mb-0 small text-muted">Server-side scripting</p>
                            </div>
                        </div>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🎨</div>
                            <div>
                                <strong>Bootstrap 5</strong>
                                <p class="mb-0 small text-muted">Responsive UI framework</p>
                            </div>
                        </div>
                        <div class="tech-stack-item">
                            <div class="tech-icon">🗄️</div>
                            <div>
                                <strong>MySQL</strong>
                                <p class="mb-0 small text-muted">Relational database</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h5><i class="bi bi-exclamation-triangle"></i> Disclaimer & Limitations</h5>
                <div class="alert alert-warning">
                    <strong>Penting untuk Diketahui:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Sistem ini adalah <strong>screening tool</strong>, bukan diagnosis medis</li>
                        <li>Hasil prediksi harus dikonfirmasi oleh profesional kesehatan mental</li>
                        <li>Model dilatih pada data umum dan mungkin tidak mencerminkan kondisi individu secara spesifik</li>
                        <li>Jika Anda mengalami krisis kesehatan mental, segera hubungi hotline 119 atau profesional terdekat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
