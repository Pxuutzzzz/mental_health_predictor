<?php
$page = 'history';
$pageTitle = 'Riwayat Pemeriksaan';
$title = 'Riwayat Pemeriksaan - Mental Health Predictor';
ob_start();
?>

<!-- History Stats Row -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stat-card primary">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Total Pemeriksaan</div>
                    <div class="stat-value">
                        <?= count($_SESSION['predictions'] ?? []) ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-clipboard-data stat-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Durasi Sesi</div>
                    <div class="stat-value">
                        <?= isset($_SESSION['start_time']) ? round((time() - $_SESSION['start_time']) / 60) : 0 ?> menit
                    </div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-clock-history stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="stat-title">Penyimpanan Data</div>
                    <div class="stat-value">Sesi</div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-server stat-icon text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Controls -->
<div class="card mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-clock-history"></i> Riwayat Pemeriksaan
        </h6>
        <?php if (!empty($_SESSION['predictions'])): ?>
            <a href="?page=history&clear_history=1" class="btn btn-danger btn-sm"
                onclick="return confirm('Apakah Anda yakin ingin menghapus semua riwayat?')">
                <i class="bi bi-trash"></i> Hapus Riwayat
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($_SESSION['predictions'])): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: var(--secondary);"></i>
                <h5 class="mt-3 text-muted">Belum Ada Riwayat Pemeriksaan</h5>
                <p class="text-muted mb-4">Mulai dengan melakukan pemeriksaan pertama Anda</p>
                <a href="." class="btn btn-primary">
                    <i class="bi bi-clipboard-plus"></i> Pemeriksaan Baru
                </a>
            </div>
        <?php else: ?>

            <!-- History Timeline -->
            <div class="timeline">
                <?php
                $predictions = array_reverse($_SESSION['predictions']);
                foreach ($predictions as $index => $pred):
                    // Safe access with isset
                    $prediction = isset($pred['prediction']) ? $pred['prediction'] : 'Unknown';
                    $confidence = isset($pred['confidence']) ? $pred['confidence'] : 0;
                    $timestamp = isset($pred['timestamp']) ? $pred['timestamp'] : date('Y-m-d H:i:s');
                    $probabilities = isset($pred['probabilities']) ? $pred['probabilities'] : [];

                    $riskColor = strpos($prediction, 'Low') !== false ? 'success' :
                        (strpos($prediction, 'High') !== false ? 'danger' : 'warning');
                    ?>
                    <div class="card mb-3 border-start border-4 border-<?= $riskColor ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge bg-<?= $riskColor ?> mb-2">
                                                <?= htmlspecialchars($prediction) ?>
                                            </span>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar3"></i>
                                                <?= $timestamp ?>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <div class="h5 mb-0 text-<?= $riskColor ?>">
                                                <?= round($confidence * 100, 1) ?>%
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Usia</small>
                                                <strong>
                                                    <?= $pred['input']['age'] ?> tahun
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Tingkat Stres</small>
                                                <strong>
                                                    <?= $pred['input']['stress'] ?>/10
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Tingkat Kecemasan</small>
                                                <strong>
                                                    <?= $pred['input']['anxiety'] ?>/10
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Depresi</small>
                                                <strong>
                                                    <?= $pred['input']['depression'] ?>/10
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Tidur</small>
                                                <strong>
                                                    <?= $pred['input']['sleep'] ?> jam
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Olahraga</small>
                                                <strong>
                                                    <?= $pred['input']['exercise'] ?>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Download PDF Button -->
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="exportSingleToPDF(<?= $index ?>)">
                                            <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h6 class="mb-2 small text-muted">
                                        Distribusi Risiko
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Persentase ini menunjukkan tingkat keyakinan AI terhadap setiap kategori risiko berdasarkan jawaban Anda"
                                            style="cursor: help; font-size: 12px;"></i>
                                    </h6>
                                    <p class="small text-muted mb-3" style="font-size: 11px; line-height: 1.4;">
                                        💡 <em>Angka ini menunjukkan seberapa yakin sistem AI bahwa kondisi Anda masuk ke
                                            kategori tertentu</em>
                                    </p>
                                    <?php if (!empty($probabilities)): ?>
                                        <?php foreach ($probabilities as $risk => $prob): ?>
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between small mb-1">
                                                    <span>
                                                        <?= htmlspecialchars($risk) ?>
                                                    </span>
                                                    <strong>
                                                        <?= round($prob * 100, 1) ?>%
                                                    </strong>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <?php
                                                    $barColor = strpos($risk, 'Low') !== false ? 'success' :
                                                        (strpos($risk, 'High') !== false ? 'danger' : 'warning');
                                                    ?>
                                                    <div class="progress-bar bg-<?= $barColor ?>" role="progressbar"
                                                        style="width: <?= round($prob * 100, 1) ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="small text-muted">Tidak ada data probabilitas</p>
                                    <?php endif; ?>

                                    <!-- Recommendations -->
                                    <?php
                                    $recommendations = isset($pred['recommendations']) ? $pred['recommendations'] : [];
                                    if (!empty($recommendations)):
                                        ?>
                                        <div class="mt-3 pt-3 border-top">
                                            <h6 class="mb-2 small text-muted">
                                                <i class="bi bi-lightbulb"></i> Rekomendasi
                                            </h6>
                                            <div class="recommendations-list">
                                                <?php foreach (array_slice($recommendations, 0, 3) as $rec): ?>
                                                    <div class="small mb-1">
                                                        <i class="bi bi-check-circle text-success"></i>
                                                        <?= htmlspecialchars($rec) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Statistics Chart -->
            <?php if (count($predictions) > 1): ?>
                <div class="card mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">
                            <i class="bi bi-graph-up"></i> Tren Pemeriksaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="trendChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<!-- Session Info -->
<div class="card">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="bi bi-info-circle"></i> Informasi Sesi
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <th width="50%">ID Sesi</th>
                        <td><code><?= session_id() ?></code></td>
                    </tr>
                    <tr>
                        <th>Total Rekaman</th>
                        <td>
                            <?= count($_SESSION['predictions'] ?? []) ?> pemeriksaan
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tr>
                        <th width="50%">Jenis Penyimpanan</th>
                        <td>Sesi PHP (Sementara)</td>
                    </tr>
                    <tr>
                        <th>Persistensi Data</th>
                        <td>Hingga browser ditutup</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="alert alert-info border-start border-4 border-info mt-3 mb-0">
            <small>
                <i class="bi bi-info-circle-fill"></i>
                <strong>Catatan:</strong> Riwayat disimpan dalam sesi Anda dan akan dihapus saat Anda menutup browser.
                Untuk penyimpanan permanen, pertimbangkan integrasi database.
            </small>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$predictionsJson = isset($_SESSION['predictions']) ? json_encode($_SESSION['predictions']) : '[]';
$sessionId = session_id();

$extraScripts = '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script>
const predictionsRaw = ' . $predictionsJson . ';
const predictions = predictionsRaw.slice().reverse(); // Reverse to match PHP display order

// Create trend chart if multiple assessments
if (predictions.length > 1) {
    const ctx = document.getElementById("trendChart");
    if (ctx) {
        const timestamps = predictions.map((p, i) => "Assessment " + (i + 1));
        const confidences = predictions.map(p => ((p.confidence || 0) * 100).toFixed(1));
        
        // Extract risk levels for color coding
        const riskLevels = predictions.map(p => {
            const pred = p.prediction || "";
            if (pred.includes("Low")) return 0;
            if (pred.includes("High")) return 2;
            return 1;
        });
        
        const pointColors = riskLevels.map(level => {
            if (level === 0) return "rgba(28, 200, 138, 1)";
            if (level === 2) return "rgba(231, 74, 59, 1)";
            return "rgba(246, 194, 62, 1)";
        });
        
        new Chart(ctx, {
            type: "line",
            data: {
                labels: timestamps,
                datasets: [{
                    label: "Tingkat Keyakinan",
                    data: confidences,
                    borderColor: "rgba(78, 115, 223, 1)",
                    backgroundColor: "rgba(78, 115, 223, 0.1)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: pointColors,
                    pointBorderColor: pointColors,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: "top"
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const pred = predictions[index].prediction || "Tidak Diketahui";
                                return [
                                    "Keyakinan: " + context.parsed.y + "%",
                                    "Hasil: " + pred
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: "Keyakinan (%)"
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: "Timeline Pemeriksaan"
                        }
                    }
                }
            }
        });
    }
}
window.exportSingleToPDF = exportSingleToPDF;


function exportSingleToPDF(index) {
    if (!predictions[index]) {
        alert("Pemeriksaan tidak ditemukan");
        return;
    }
    
    console.log("Exporting assessment:", predictions[index]);
    console.log("Recommendations:", predictions[index].recommendations);
    
    showLoading();
    
    // Check if jsPDF is loaded
    if (typeof window.jspdf === "undefined") {
        hideLoading();
        alert("Library PDF tidak dimuat. Silakan refresh halaman dan coba lagi.");
        return;
    }
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF("p", "mm", "a4");
        const pred = predictions[index];
        const input = pred.input || {};
        const pageWidth = 210;
        const pageHeight = 297;
        const margin = 20;
        const contentWidth = pageWidth - (margin * 2);

        // ===== HEADER SECTION =====
        // Header background
        doc.setFillColor(41, 128, 185); // Professional blue
        doc.rect(0, 0, pageWidth, 35, \'F\');
        
        // Logo placeholder (you can replace with actual logo)
        doc.setFillColor(255, 255, 255);
        doc.circle(25, 17.5, 8, \'F\');
        doc.setTextColor(41, 128, 185);
        doc.setFontSize(12);
        doc.setFont(undefined, \'bold\');
        doc.text("MH", 25, 19, { align: "center" });
        
        // Header title
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.setFont(undefined, \'bold\');
        doc.text("MENTAL HEALTH PREDICTOR", 40, 15);
        doc.setFontSize(9);
        doc.setFont(undefined, \'normal\');
        doc.text("Sistem Prediksi & Analisis Kesehatan Mental", 40, 21);
        doc.text("Email: support@mentalhealthpredictor.com | Tel: (021) 1234-5678", 40, 26);

        // Document title box
        let yPos = 45;
        doc.setFillColor(236, 240, 241);
        doc.rect(margin, yPos, contentWidth, 15, \'F\');
        doc.setTextColor(44, 62, 80);
        doc.setFontSize(14);
        doc.setFont(undefined, \'bold\');
        doc.text("LAPORAN HASIL PEMERIKSAAN KESEHATAN MENTAL", pageWidth / 2, yPos + 10, { align: "center" });
        
        yPos += 20;
        
        // Document info
        doc.setFontSize(9);
        doc.setFont(undefined, \'normal\');
        doc.setTextColor(100, 100, 100);
        const docDate = new Date(pred.timestamp || Date.now());
        doc.text("No. Laporan: MHP/" + docDate.getFullYear() + "/" + String(index + 1).padStart(4, \'0\'), margin, yPos);
        doc.text("Tanggal: " + docDate.toLocaleDateString(\'id-ID\', { day: \'2-digit\', month: \'long\', year: \'numeric\' }), pageWidth - margin, yPos, { align: "right" });
        
        yPos += 10;
        
        // Horizontal line
        doc.setDrawColor(189, 195, 199);
        doc.setLineWidth(0.5);
        doc.line(margin, yPos, pageWidth - margin, yPos);
        yPos += 8;

        // ===== PATIENT DATA SECTION =====
        doc.setFontSize(11);
        doc.setFont(undefined, \'bold\');
        doc.setTextColor(44, 62, 80);
        doc.text("I. DATA PEMERIKSAAN", margin, yPos);
        yPos += 7;
        
        // Patient data table
        doc.setFont(undefined, \'normal\');
        doc.setFontSize(9);
        const patientData = [
            ["Usia", (input.age || "N/A") + " tahun"],
            ["Jenis Kelamin", input.gender || "N/A"],
            ["Status Pekerjaan", input.employment_status || "N/A"],
            ["Lingkungan Kerja", input.work_environment || "N/A"],
            ["Riwayat Kesehatan Mental", input.mental_history || "N/A"],
            ["Mencari Bantuan Profesional", input.seeks_treatment || "N/A"],
            ["Tingkat Stres", (input.stress || "N/A") + "/10"],
            ["Skor Depresi", (input.depression || "N/A")],
            ["Skor Kecemasan", (input.anxiety || "N/A")],
            ["Durasi Tidur", (input.sleep || "N/A") + " jam/hari"],
            ["Aktivitas Fisik (hari/minggu)", input.exercise || "N/A"],
            ["Skor Dukungan Sosial", input.social_support || "N/A"],
            ["Skor Produktivitas", input.productivity || "N/A"]
        ];
        
        patientData.forEach(([label, value], idx) => {
            if (idx % 2 === 0) {
                doc.setFillColor(249, 249, 249);
                doc.rect(margin, yPos - 4, contentWidth, 6, \'F\');
            }
            doc.setTextColor(80, 80, 80);
            doc.text(label, margin + 2, yPos);
            doc.setTextColor(44, 62, 80);
            doc.setFont(undefined, \'bold\');
            doc.text(": " + String(value), margin + 60, yPos);
            doc.setFont(undefined, \'normal\');
            yPos += 6;
        });
        
        yPos += 5;

        // ===== ALL INPUT DATA SECTION (DYNAMIC) =====
        doc.setFontSize(10);
        doc.setFont(undefined, "bold");
        doc.setTextColor(44, 62, 80);
        doc.text("Data Input Lengkap:", margin, yPos);
        yPos += 6;
        doc.setFont(undefined, "normal");
        doc.setFontSize(9);
        doc.setTextColor(80, 80, 80);
        Object.entries(input).forEach(([key, value]) => {
            doc.text(key + ": " + String(value), margin + 2, yPos);
            yPos += 5;
        });

        // ===== ASSESSMENT RESULTS SECTION =====
        doc.setFontSize(11);
        doc.setFont(undefined, \'bold\');
        doc.setTextColor(44, 62, 80);
        doc.text("II. HASIL ANALISIS", margin, yPos);
        yPos += 7;
        
        // Risk level box
        const riskLevel = pred.prediction || "Tidak Diketahui";
        const confidence = ((pred.confidence || 0) * 100).toFixed(1);
        let riskColor = [52, 152, 219]; // Default blue
        if (riskLevel.includes("Low")) riskColor = [46, 204, 113]; // Green
        else if (riskLevel.includes("High")) riskColor = [231, 76, 60]; // Red
        else if (riskLevel.includes("Medium")) riskColor = [241, 196, 15]; // Yellow
        
        doc.setFillColor(riskColor[0], riskColor[1], riskColor[2]);
        doc.roundedRect(margin, yPos, contentWidth, 18, 2, 2, \'F\');
        
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.setFont(undefined, \'normal\');
        doc.text("Tingkat Risiko Kesehatan Mental:", margin + 5, yPos + 7);
        doc.setFontSize(13);
        doc.setFont(undefined, \'bold\');
        doc.text(riskLevel.toUpperCase(), margin + 5, yPos + 14);
        
        doc.setFontSize(10);
        doc.setFont(undefined, \'normal\');
        doc.text("Tingkat Keyakinan:", pageWidth - margin - 45, yPos + 7);
        doc.setFontSize(13);
        doc.setFont(undefined, \'bold\');
        doc.text(confidence + "%", pageWidth - margin - 45, yPos + 14);
        
        yPos += 23;

        // Probability distribution
        if (pred.probabilities && Object.keys(pred.probabilities).length > 0) {
            doc.setFontSize(10);
            doc.setFont(undefined, \'bold\');
            doc.setTextColor(44, 62, 80);
            doc.text("Distribusi Probabilitas:", margin, yPos);
            yPos += 5;
            
            Object.entries(pred.probabilities).forEach(([risk, prob]) => {
                const probPercent = (prob * 100).toFixed(1);
                doc.setFont(undefined, \'normal\');
                doc.setFontSize(9);
                doc.setTextColor(80, 80, 80);
                doc.text("• " + risk, margin + 2, yPos);
                
                // Progress bar
                const barWidth = 80;
                const barHeight = 4;
                const barX = margin + 60;
                doc.setDrawColor(200, 200, 200);
                doc.setFillColor(240, 240, 240);
                doc.roundedRect(barX, yPos - 3, barWidth, barHeight, 1, 1, \'FD\');
                
                let barColor = [52, 152, 219];
                if (risk.includes("Low")) barColor = [46, 204, 113];
                else if (risk.includes("High")) barColor = [231, 76, 60];
                else if (risk.includes("Medium")) barColor = [241, 196, 15];
                
                doc.setFillColor(barColor[0], barColor[1], barColor[2]);
                doc.roundedRect(barX, yPos - 3, barWidth * prob, barHeight, 1, 1, \'F\');
                
                doc.setTextColor(44, 62, 80);
                doc.setFont(undefined, \'bold\');
                doc.text(probPercent + "%", barX + barWidth + 3, yPos);
                
                yPos += 7;
            });
            yPos += 3;
        }

        // ===== RECOMMENDATIONS SECTION =====
        doc.setFontSize(11);
        doc.setFont(undefined, \'bold\');
        doc.setTextColor(44, 62, 80);
        doc.text("III. REKOMENDASI PROFESIONAL", margin, yPos);
        yPos += 7;
        
        doc.setFontSize(9);
        doc.setFont(undefined, \'normal\');
        doc.setTextColor(80, 80, 80);
        const recommendations = pred.recommendations || [];
        if (recommendations && recommendations.length > 0) {
            recommendations.slice(0, 8).forEach((rec, idx) => {
                const lines = doc.splitTextToSize(String(rec), contentWidth - 10);
                doc.text((idx + 1) + ". " + lines[0], margin + 2, yPos);
                for (let i = 1; i < lines.length; i++) {
                    yPos += 4;
                    doc.text("   " + lines[i], margin + 2, yPos);
                }
                yPos += 5;
            });
        } else {
            doc.text("Tidak ada rekomendasi khusus pada saat ini.", margin + 2, yPos);
            yPos += 5;
        }
        
        yPos += 5;

        // ===== DISCLAIMER =====
        doc.setFillColor(255, 243, 205);
        doc.setDrawColor(255, 193, 7);
        doc.setLineWidth(0.5);
        const disclaimerHeight = 15;
        doc.roundedRect(margin, yPos, contentWidth, disclaimerHeight, 2, 2, \'FD\');
        doc.setFontSize(8);
        doc.setTextColor(133, 100, 4);
        doc.setFont(undefined, \'bold\');
        doc.text("PERHATIAN:", margin + 3, yPos + 5);
        doc.setFont(undefined, \'normal\');
        const disclaimerText = "Hasil pemeriksaan ini bersifat prediktif dan tidak menggantikan diagnosis profesional. Konsultasikan dengan tenaga kesehatan mental berlisensi untuk evaluasi lebih lanjut.";
        const disclaimerLines = doc.splitTextToSize(disclaimerText, contentWidth - 30);
        doc.text(disclaimerLines, margin + 23, yPos + 5);
        
        yPos += disclaimerHeight + 10;

        // ===== SIGNATURE SECTION =====
        doc.setFontSize(9);
        doc.setTextColor(80, 80, 80);
        doc.setFont(undefined, \'normal\');
        const signDate = new Date().toLocaleDateString(\'id-ID\', { day: \'2-digit\', month: \'long\', year: \'numeric\' });
        doc.text("Jakarta, " + signDate, pageWidth - margin - 50, yPos, { align: "left" });
        yPos += 5;
        doc.text("Sistem Otomatis", pageWidth - margin - 50, yPos, { align: "left" });
        yPos += 15;
        doc.setFont(undefined, \'bold\');
        doc.text("_______________________", pageWidth - margin - 50, yPos, { align: "left" });
        yPos += 4;
        doc.setFont(undefined, \'normal\');
        doc.text("Mental Health Predictor", pageWidth - margin - 50, yPos, { align: "left" });

        // ===== FOOTER =====
        const footerY = pageHeight - 15;
        doc.setDrawColor(189, 195, 199);
        doc.setLineWidth(0.3);
        doc.line(margin, footerY - 3, pageWidth - margin, footerY - 3);
        
        doc.setFontSize(7);
        doc.setTextColor(120, 120, 120);
        doc.setFont(undefined, \'italic\');
        doc.text("Dokumen ini dicetak secara otomatis oleh sistem Mental Health Predictor", margin, footerY);
        doc.text("Halaman 1 dari 1", pageWidth - margin, footerY, { align: "right" });
        doc.text("Dicetak pada: " + new Date().toLocaleDateString(\'id-ID\') + " " + new Date().toLocaleTimeString(\'id-ID\'), margin, footerY + 4);

        // Download
        const timestamp = (pred.timestamp || new Date().toISOString()).replace(/[:\s]/g, "-");
        doc.save("Laporan_Kesehatan_Mental_" + timestamp + ".pdf");
        hideLoading();
    } catch (error) {
        hideLoading();
        alert("Kesalahan saat mengekspor PDF: " + error.message);
        console.error(error);
    }
}

// Initialize Bootstrap tooltips
document.addEventListener(\'DOMContentLoaded\', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll(\'[data-bs-toggle="tooltip"]\'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>';

require __DIR__ . '/layout.php';
