<?php
$page = 'screening';
$pageTitle = 'GAD-7 Anxiety Screening';

ob_start();
?>

<style>
.screening-container {
    max-width: 900px;
    margin: 0 auto;
}

.screening-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
}

.screening-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 20px;
}

.question-item {
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.question-item:hover {
    border-color: #f5576c;
    box-shadow: 0 4px 12px rgba(245,87,108,0.1);
}

.question-number {
    display: inline-block;
    width: 35px;
    height: 35px;
    background: #f5576c;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 35px;
    font-weight: bold;
    margin-right: 15px;
}

.question-text {
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 15px;
}

.answer-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.answer-option {
    flex: 1;
    min-width: 150px;
}

.answer-option input[type="radio"] {
    display: none;
}

.answer-label {
    display: block;
    padding: 12px 20px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.answer-option input[type="radio"]:checked + .answer-label {
    background: #f5576c;
    color: white;
    border-color: #f5576c;
}

.answer-label:hover {
    border-color: #f5576c;
    background: #fff5f7;
}

.answer-option input[type="radio"]:checked + .answer-label:hover {
    background: #e04560;
}

.score-display {
    font-size: 14px;
    color: #6c757d;
    margin-top: 5px;
}

.info-box {
    background: #fff0f5;
    border-left: 4px solid #f5576c;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.patient-info-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}
</style>

<div class="screening-container">
    <div class="screening-header">
        <h1><i class="bi bi-heart-pulse"></i> GAD-7 Anxiety Screening</h1>
        <p class="mb-0">Generalized Anxiety Disorder - 7 Items</p>
    </div>

    <div class="screening-card">
        <div class="info-box">
            <strong><i class="bi bi-info-circle"></i> Petunjuk:</strong><br>
            Selama <strong>2 minggu terakhir</strong>, seberapa sering Anda merasa terganggu oleh masalah-masalah berikut?
        </div>

        <form id="gad7Form" method="POST">
            <!-- Patient Information -->
            <div class="patient-info-section">
                <h5 class="mb-3"><i class="bi bi-person-badge"></i> Informasi Pasien</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="patient_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Rekam Medis (MRN)</label>
                        <input type="text" class="form-control" name="patient_mrn" placeholder="Opsional">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Klinisi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="clinician_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="clinician_role" required>
                            <option value="">Pilih role...</option>
                            <option value="Psychiatrist">Psikiater</option>
                            <option value="Psychologist">Psikolog Klinis</option>
                            <option value="Nurse">Perawat Jiwa</option>
                            <option value="Counselor">Konselor</option>
                            <option value="Doctor">Dokter Umum</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- GAD-7 Questions -->
            <?php
            $gad7_questions = [
                ['id' => 'q1', 'text' => 'Merasa gugup, cemas, atau tegang'],
                ['id' => 'q2', 'text' => 'Tidak dapat menghentikan atau mengendalikan kekhawatiran'],
                ['id' => 'q3', 'text' => 'Khawatir terlalu banyak tentang berbagai hal'],
                ['id' => 'q4', 'text' => 'Kesulitan untuk rileks'],
                ['id' => 'q5', 'text' => 'Sangat gelisah sehingga sulit untuk duduk diam'],
                ['id' => 'q6', 'text' => 'Mudah menjadi kesal atau mudah tersinggung'],
                ['id' => 'q7', 'text' => 'Merasa takut seolah-olah sesuatu yang mengerikan akan terjadi']
            ];

            $answers = [
                ['value' => '0', 'label' => 'Tidak sama sekali'],
                ['value' => '1', 'label' => 'Beberapa hari'],
                ['value' => '2', 'label' => 'Lebih dari setengah hari'],
                ['value' => '3', 'label' => 'Hampir setiap hari']
            ];

            foreach ($gad7_questions as $index => $question):
            ?>
            <div class="question-item">
                <div class="d-flex align-items-start mb-3">
                    <span class="question-number"><?php echo $index + 1; ?></span>
                    <div class="flex-grow-1">
                        <div class="question-text"><?php echo $question['text']; ?></div>
                    </div>
                </div>
                <div class="answer-options">
                    <?php foreach ($answers as $answer): ?>
                    <div class="answer-option">
                        <input type="radio" 
                               id="gad7_<?php echo $question['id'] . '_' . $answer['value']; ?>" 
                               name="gad7_<?php echo $question['id']; ?>" 
                               value="<?php echo $answer['value']; ?>"
                               required>
                        <label class="answer-label" for="gad7_<?php echo $question['id'] . '_' . $answer['value']; ?>">
                            <?php echo $answer['label']; ?>
                            <div class="score-display">(<?php echo $answer['value']; ?>)</div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Clinical Notes -->
            <div class="mt-4">
                <label class="form-label"><strong>Catatan Klinis</strong></label>
                <textarea class="form-control" name="clinical_notes" rows="4" placeholder="Observasi tambahan, context, atau catatan klinis..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-check-circle"></i> Submit Screening
                </button>
            </div>
        </form>
    </div>

    <!-- Score Display (Hidden, will show after calculation) -->
    <div id="scoreDisplay" class="screening-card" style="display: none;">
        <h4 class="mb-4"><i class="bi bi-graph-up"></i> Hasil Screening GAD-7</h4>
        <div class="row text-center">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Score</h6>
                        <h2 id="totalScore" class="mb-0">-</h2>
                        <small class="text-muted">Range: 0-21</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" id="severityCard">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Severity</h6>
                        <h4 id="severityLevel" class="mb-0">-</h4>
                        <small id="severityRange" class="text-muted">-</small>
                    </div>
                </div>
            </div>
        </div>
        <div id="recommendations" class="mt-4"></div>
    </div>
</div>

<script>
document.getElementById('gad7Form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Calculate score
    let totalScore = 0;
    for (let i = 1; i <= 7; i++) {
        const selected = document.querySelector(`input[name="gad7_q${i}"]:checked`);
        if (selected) {
            totalScore += parseInt(selected.value);
        }
    }
    
    // Determine severity
    let severity, severityColor, severityRange, recommendations;
    
    if (totalScore >= 0 && totalScore <= 4) {
        severity = 'Minimal Anxiety';
        severityColor = '#28a745';
        severityRange = '0-4';
        recommendations = `
            <div class="alert alert-success">
                <strong>Rekomendasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Tidak ada gangguan kecemasan yang signifikan</li>
                    <li>Monitor kondisi pasien</li>
                    <li>Edukasi manajemen stress</li>
                </ul>
            </div>
        `;
    } else if (totalScore >= 5 && totalScore <= 9) {
        severity = 'Mild Anxiety';
        severityColor = '#ffc107';
        severityRange = '5-9';
        recommendations = `
            <div class="alert alert-warning">
                <strong>Rekomendasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Watchful waiting dengan follow-up</li>
                    <li>Pertimbangkan relaxation techniques</li>
                    <li>Counseling atau terapi suportif</li>
                    <li>Re-evaluasi dalam 4-6 minggu</li>
                </ul>
            </div>
        `;
    } else if (totalScore >= 10 && totalScore <= 14) {
        severity = 'Moderate Anxiety';
        severityColor = '#fd7e14';
        severityRange = '10-14';
        recommendations = `
            <div class="alert alert-warning">
                <strong>Rekomendasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Pertimbangkan terapi (CBT sangat efektif untuk GAD)</li>
                    <li>Atau farmakoterapi (SSRI/SNRI)</li>
                    <li>Rujuk ke psikolog atau psikiater</li>
                    <li>Follow-up setiap 2-4 minggu</li>
                </ul>
            </div>
        `;
    } else if (totalScore >= 15) {
        severity = 'Severe Anxiety';
        severityColor = '#dc3545';
        severityRange = '15-21';
        recommendations = `
            <div class="alert alert-danger">
                <strong>⚠️ Rekomendasi:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Memerlukan treatment aktif</strong></li>
                    <li>Kombinasi psikoterapi (CBT) dan farmakoterapi</li>
                    <li>Rujuk segera ke psikiater</li>
                    <li>Evaluasi kondisi komorbid (depresi, panic disorder)</li>
                    <li>Follow-up ketat setiap 1-2 minggu</li>
                    <li>Pertimbangkan screening tambahan (panic, PTSD, OCD)</li>
                </ul>
            </div>
        `;
    }
    
    // Display results
    document.getElementById('totalScore').textContent = totalScore;
    document.getElementById('severityLevel').textContent = severity;
    document.getElementById('severityRange').textContent = severityRange;
    document.getElementById('severityCard').style.borderLeft = `5px solid ${severityColor}`;
    document.getElementById('recommendations').innerHTML = recommendations;
    document.getElementById('scoreDisplay').style.display = 'block';
    
    // Scroll to results
    document.getElementById('scoreDisplay').scrollIntoView({ behavior: 'smooth' });
    
    // Prepare data for submission
    const formData = new FormData(this);
    formData.append('screening_type', 'GAD7');
    formData.append('gad7_total_score', totalScore);
    formData.append('gad7_severity', severity);
    formData.append('urgent_flag', totalScore >= 15 ? '1' : '0');
    
    // Send to server
    fetch('save-screening', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Screening saved successfully:', result.id);
        } else {
            console.error('Error saving screening:', result.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Real-time score calculation display
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        let currentTotal = 0;
        for (let i = 1; i <= 7; i++) {
            const selected = document.querySelector(`input[name="gad7_q${i}"]:checked`);
            if (selected) {
                currentTotal += parseInt(selected.value);
            }
        }
        console.log('Current GAD-7 score:', currentTotal);
    });
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
