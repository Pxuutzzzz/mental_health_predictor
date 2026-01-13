<?php
$page = 'screening';
$pageTitle = 'Mental Health Screening Tools';

ob_start();
?>

<style>
.screening-dashboard {
    max-width: 1200px;
    margin: 0 auto;
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    text-align: center;
}

.tool-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
    transition: all 0.3s;
    border: 3px solid transparent;
}

.tool-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.tool-card.phq9-card {
    border-color: #667eea;
}

.tool-card.gad7-card {
    border-color: #f5576c;
}

.tool-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.tool-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
}

.tool-description {
    color: #6c757d;
    margin-bottom: 20px;
}

.tool-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    font-size: 14px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.info-section {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.severity-guide {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.severity-item {
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    color: white;
}

.severity-minimal { background: #28a745; }
.severity-mild { background: #ffc107; color: #000; }
.severity-moderate { background: #fd7e14; }
.severity-severe { background: #dc3545; }
</style>

<div class="screening-dashboard">
    <div class="dashboard-header">
        <h1><i class="bi bi-heart-pulse-fill"></i> Mental Health Screening Tools</h1>
        <p class="mb-0">Standardized screening instruments for clinical use</p>
    </div>

    <!-- PHQ-9 Card -->
    <div class="tool-card phq9-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="tool-icon text-primary">
                    <i class="bi bi-clipboard-heart"></i>
                </div>
                <div class="tool-title">PHQ-9</div>
                <div class="tool-subtitle">Patient Health Questionnaire - 9</div>
                <div class="tool-description">
                    Screening standar untuk mendeteksi depresi. 9 pertanyaan berdasarkan kriteria DSM-IV untuk Major Depressive Disorder.
                </div>
                <div class="tool-meta">
                    <div class="meta-item">
                        <i class="bi bi-clock"></i>
                        <span>5-10 menit</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-question-circle"></i>
                        <span>9 pertanyaan</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-bar-chart"></i>
                        <span>Score: 0-27</span>
                    </div>
                </div>
                <a href="screening-phq9" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-right-circle"></i> Start PHQ-9 Screening
                </a>
            </div>
            <div class="col-md-4">
                <div class="severity-guide">
                    <div class="severity-item severity-minimal">
                        <strong>0-4</strong><br>Minimal
                    </div>
                    <div class="severity-item severity-mild">
                        <strong>5-9</strong><br>Mild
                    </div>
                    <div class="severity-item severity-moderate">
                        <strong>10-14</strong><br>Moderate
                    </div>
                    <div class="severity-item severity-moderate">
                        <strong>15-19</strong><br>Mod. Severe
                    </div>
                    <div class="severity-item severity-severe">
                        <strong>20-27</strong><br>Severe
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GAD-7 Card -->
    <div class="tool-card gad7-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="tool-icon" style="color: #f5576c;">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <div class="tool-title">GAD-7</div>
                <div class="tool-subtitle">Generalized Anxiety Disorder - 7</div>
                <div class="tool-description">
                    Screening untuk gangguan kecemasan umum. 7 pertanyaan untuk mengukur tingkat kecemasan dalam 2 minggu terakhir.
                </div>
                <div class="tool-meta">
                    <div class="meta-item">
                        <i class="bi bi-clock"></i>
                        <span>3-5 menit</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-question-circle"></i>
                        <span>7 pertanyaan</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-bar-chart"></i>
                        <span>Score: 0-21</span>
                    </div>
                </div>
                <a href="screening-gad7" class="btn btn-danger btn-lg">
                    <i class="bi bi-arrow-right-circle"></i> Start GAD-7 Screening
                </a>
            </div>
            <div class="col-md-4">
                <div class="severity-guide">
                    <div class="severity-item severity-minimal">
                        <strong>0-4</strong><br>Minimal
                    </div>
                    <div class="severity-item severity-mild">
                        <strong>5-9</strong><br>Mild
                    </div>
                    <div class="severity-item severity-moderate">
                        <strong>10-14</strong><br>Moderate
                    </div>
                    <div class="severity-item severity-severe">
                        <strong>15-21</strong><br>Severe
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="info-section">
        <h4 class="mb-4"><i class="bi bi-info-circle"></i> Tentang Screening Tools</h4>
        
        <div class="row">
            <div class="col-md-6">
                <h5>Kapan Menggunakan?</h5>
                <ul>
                    <li><strong>Initial Assessment:</strong> Pasien baru di klinik/poli jiwa</li>
                    <li><strong>Routine Screening:</strong> Check-up rutin (6-12 bulan)</li>
                    <li><strong>Follow-up:</strong> Monitoring progress treatment</li>
                    <li><strong>Emergency:</strong> Pasien dengan keluhan mental health</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Siapa yang Bisa Melakukan?</h5>
                <ul>
                    <li><i class="bi bi-check-circle text-success"></i> Psikiater</li>
                    <li><i class="bi bi-check-circle text-success"></i> Psikolog Klinis</li>
                    <li><i class="bi bi-check-circle text-success"></i> Perawat Jiwa</li>
                    <li><i class="bi bi-check-circle text-success"></i> Konselor</li>
                    <li><i class="bi bi-check-circle text-success"></i> Dokter Umum (trained)</li>
                </ul>
            </div>
        </div>

        <hr class="my-4">

        <div class="alert alert-warning">
            <strong><i class="bi bi-exclamation-triangle"></i> Penting:</strong>
            <ul class="mb-0 mt-2">
                <li>Screening tools ini adalah <strong>alat bantu</strong>, bukan diagnosis definitif</li>
                <li>Hasil harus diinterpretasikan oleh tenaga kesehatan yang kompeten</li>
                <li>Jika ada <strong>red flags</strong> (suicide ideation, psychosis), lakukan evaluasi mendalam</li>
                <li>Selalu dokumentasikan hasil dan tindak lanjut</li>
            </ul>
        </div>

        <div class="alert alert-info">
            <strong><i class="bi bi-shield-check"></i> Evidence-Based:</strong><br>
            PHQ-9 dan GAD-7 adalah instrumen yang telah divalidasi secara internasional dan direkomendasikan oleh:
            <div class="mt-2">
                <span class="badge bg-primary me-2">WHO</span>
                <span class="badge bg-primary me-2">DSM-5</span>
                <span class="badge bg-primary me-2">NICE Guidelines</span>
                <span class="badge bg-primary me-2">Kemenkes RI</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats (if logged in) -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="info-section">
        <h4 class="mb-3"><i class="bi bi-graph-up"></i> Recent Screening Activity</h4>
        <div id="recentActivity" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    
    <script>
    // Load recent activity
    fetch('screening-history')
        .then(response => response.json())
        .then(result => {
            const container = document.getElementById('recentActivity');
            if (result.success && result.data.length > 0) {
                let html = '<div class="table-responsive"><table class="table table-hover">';
                html += '<thead><tr><th>Date</th><th>Patient</th><th>Type</th><th>Score</th><th>Severity</th><th>Urgent</th></tr></thead><tbody>';
                
                result.data.forEach(item => {
                    const urgent = item.urgent_flag ? '<span class="badge bg-danger">Yes</span>' : '';
                    html += `<tr>
                        <td>${item.assessment_date}</td>
                        <td>${item.patient_name}${item.patient_mrn ? ' (' + item.patient_mrn + ')' : ''}</td>
                        <td><span class="badge bg-info">${item.screening_type}</span></td>
                        <td>${item.phq9_total_score || item.gad7_total_score || '-'}</td>
                        <td>${item.phq9_severity || item.gad7_severity || '-'}</td>
                        <td>${urgent}</td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<p class="text-muted">No recent screening activity</p>';
            }
        })
        .catch(error => {
            document.getElementById('recentActivity').innerHTML = 
                '<p class="text-danger">Error loading activity</p>';
        });
    </script>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
