
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
                    <div class="stat-value"><?= count($_SESSION['predictions'] ?? []) ?></div>
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
                    <div class="stat-value"><?= isset($_SESSION['start_time']) ? round((time() - $_SESSION['start_time']) / 60) : 0 ?> menit</div>
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
                                        <i class="bi bi-calendar3"></i> <?= $timestamp ?>
                                    </p>
                                </div>
                                <div class="text-end">
                                    </script>
                                    <div class="h5 mb-0 text-<?= $riskColor ?>">
                                        <?= round($confidence * 100, 1) ?>%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Usia</small>
                                        <strong><?= $pred['input']['age'] ?> tahun</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Tingkat Stres</small>
                                        <strong><?= $pred['input']['stress'] ?>/10</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Tingkat Kecemasan</small>
                                        <strong><?= $pred['input']['anxiety'] ?>/10</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Depresi</small>
                                        <strong><?= $pred['input']['depression'] ?>/10</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Tidur</small>
                                        <strong><?= $pred['input']['sleep'] ?> jam</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Olahraga</small>
                                        <strong><?= $pred['input']['exercise'] ?></strong>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Download PDF Button -->
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-danger" onclick="exportSingleToPDF(<?= $index ?>)">
                                    <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <h6 class="mb-2 small text-muted">Distribusi Risiko</h6>
                            <?php if (!empty($probabilities)): ?>
                                <?php foreach ($probabilities as $risk => $prob): ?>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span><?= htmlspecialchars($risk) ?></span>
                                        <strong><?= round($prob * 100, 1) ?>%</strong>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <?php 
                                        $barColor = strpos($risk, 'Low') !== false ? 'success' : 
                                                   (strpos($risk, 'High') !== false ? 'danger' : 'warning');
                                        ?>
                                        <div class="progress-bar bg-<?= $barColor ?>" 
                                             role="progressbar" 
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
        
        <!-- Export Options -->
        <div class="text-center mt-4 pt-3 border-top">
            <button class="btn btn-success" onclick="exportToCSV()">
                <i class="bi bi-file-earmark-spreadsheet"></i> Ekspor Semua ke CSV
            </button>
        </div>
        
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
                        <td><?= count($_SESSION['predictions'] ?? []) ?> pemeriksaan</td>
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

$extraScripts = '<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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
        const margin = 15;
        const contentWidth = pageWidth - (margin * 2);

        let yPos = 18;
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.text("LAPORAN PEMERIKSAAN KESEHATAN MENTAL", pageWidth / 2, yPos, { align: "center" });
        yPos += 8;
        doc.setFontSize(10);
        doc.text("Tanggal Pemeriksaan: " + (pred.timestamp || "N/A"), margin, yPos);
        yPos += 8;

        // Tabel Data Pasien
        doc.setFontSize(11);
        doc.text("Data Pasien:", margin, yPos);
        yPos += 4;
        doc.setFontSize(9);
        const patientData = [
            ["Usia", (input.age || "N/A") + " tahun"],
            ["Stres", (input.stress || "N/A") + "/10"],
            ["Kecemasan", (input.anxiety || "N/A") + "/10"],
            ["Depresi", (input.depression || "N/A") + "/10"],
            ["Riwayat Mental", input.mental_history || "N/A"],
            ["Tidur", (input.sleep || "N/A") + " jam"],
            ["Olahraga", input.exercise || "N/A"],
            ["Dukungan Sosial", input.social_support || "N/A"]
        ];
        patientData.forEach(([label, value]) => {
            doc.text(label + ":", margin, yPos);
            doc.text(String(value), margin + 40, yPos);
            yPos += 6;
        });
        yPos += 2;

        // Hasil Prediksi
        doc.setFontSize(11);
        doc.text("Hasil Prediksi:", margin, yPos);
        yPos += 4;
        doc.setFontSize(10);
        doc.text("Risiko Kesehatan Mental: " + (pred.prediction || "-"), margin, yPos);
        yPos += 6;
        doc.text("Keyakinan Model: " + (((pred.confidence || 0) * 100).toFixed(1)) + "%", margin, yPos);
        yPos += 6;

        // Distribusi Risiko (tabel sederhana)
        if (pred.probabilities) {
            doc.setFontSize(10);
            doc.text("Distribusi Risiko:", margin, yPos);
            yPos += 4;
            Object.entries(pred.probabilities).forEach(([risk, prob]) => {
                doc.text("- " + risk + ": " + (prob * 100).toFixed(1) + "%", margin + 4, yPos);
                yPos += 5;
            });
        }
        yPos += 2;

        // Rekomendasi
        doc.setFontSize(11);
        doc.text("Rekomendasi Profesional:", margin, yPos);
        yPos += 4;
        doc.setFontSize(9);
        const recommendations = pred.recommendations || [];
        if (recommendations && recommendations.length > 0) {
            recommendations.slice(0, 8).forEach((rec, idx) => {
                const lines = doc.splitTextToSize(String(rec), contentWidth - 10);
                doc.text("• " + lines[0], margin + 4, yPos);
                for (let i = 1; i < lines.length; i++) {
                    yPos += 4;
                    doc.text("  " + lines[i], margin + 8, yPos);
                }
                yPos += 6;
            });
        } else {
            doc.text("Tidak ada rekomendasi tersedia.", margin + 4, yPos);
            yPos += 6;
        }

        // Footer formal
        const footerY = pageHeight - 18;
        doc.setFontSize(8);
        doc.setTextColor(80, 80, 80);
        doc.text("Laporan ini dicetak otomatis oleh Sistem Prediksi Kesehatan Mental.", margin, footerY);
        doc.text("Tanggal cetak: " + new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString(), margin, footerY + 5);

        // Download
        const timestamp = (pred.timestamp || new Date().toISOString()).replace(/[:\s]/g, "-");
        doc.save("mental_health_assessment_" + timestamp + ".pdf");
        hideLoading();
    } catch (error) {
        hideLoading();
        alert("Kesalahan saat mengekspor PDF: " + error.message);
        console.error(error);
    }
}

function exportToCSV() {
    if (predictions.length === 0) {
        alert("Tidak ada data untuk diekspor");
        return;
    }
    
    showLoading();
    
    try {
        // CSV Headers
        let csv = "Waktu,Prediksi,Keyakinan,Usia,Stres,Kecemasan,Depresi,Riwayat Mental,Tidur,Olahraga,Dukungan Sosial\n";
        
        // CSV Data
        predictions.forEach(pred => {
            const input = pred.input || {};
            // ...existing code in <script>...
        });
        
        // Download
        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "mental_health_assessment_" + new Date().toISOString().split("T")[0] + ".csv";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        hideLoading();
    } catch (error) {
        hideLoading();
        alert("Kesalahan saat mengekspor CSV: " + error.message);
    }
}
</script>
<?php
require __DIR__ . '/layout.php';
?>
<script>
