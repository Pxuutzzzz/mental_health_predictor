<?php
$page = 'history';
$pageTitle = 'Riwayat Pemeriksaan';

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
                                    <div class="text-muted small">Tingkat Keyakinan</div>
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
        const pageWidth = 210; // A4 width in mm
        const pageHeight = 297; // A4 height in mm
        const margin = 15;
        const contentWidth = pageWidth - (margin * 2);
        
        // Colors
        const primaryColor = [78, 115, 223];
        const successColor = [40, 167, 69];
        const warningColor = [255, 193, 7];
        const dangerColor = [220, 53, 69];
        const darkColor = [52, 58, 64];
        const lightColor = [248, 249, 250];
        
        // Determine risk color
        const predColor = (pred.prediction || "").includes("Low") ? successColor :
                         (pred.prediction || "").includes("High") ? dangerColor : warningColor;
        
        // ===== HEADER SECTION =====
        let yPos = 12;
        
        // Header background
        doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.rect(0, 0, pageWidth, 28, "F");
        
        // Logo/Icon
        doc.setFillColor(255, 255, 255);
        doc.circle(20, 15, 6, "F");
        doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.setFontSize(11);
        doc.text("MH", 20, 16.5, { align: "center" });
        
        // Title
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.text("LAPORAN PEMERIKSAAN KESEHATAN MENTAL", 30, 13);
        
        // Subtitle
        doc.setFontSize(8);
        doc.text("Tanggal Pemeriksaan: " + (pred.timestamp || "N/A") + " | Dibuat: " + new Date().toLocaleString(), 30, 20);
        
        yPos = 35;
        
        // ===== RESULT CARD =====
        doc.setFillColor(predColor[0], predColor[1], predColor[2]);
        doc.roundedRect(margin, yPos, contentWidth, 22, 3, 3, "F");
        
        // Risk Level
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(14);
        doc.text(pred.prediction || "Unknown Risk", pageWidth / 2, yPos + 10, { align: "center" });
        
        // Confidence badge
        doc.setFillColor(255, 255, 255);
        doc.roundedRect(pageWidth / 2 - 18, yPos + 13, 36, 6, 2, 2, "F");
        doc.setTextColor(predColor[0], predColor[1], predColor[2]);
        doc.setFontSize(9);
        doc.text("Keyakinan: " + (((pred.confidence || 0) * 100).toFixed(1)) + "%", pageWidth / 2, yPos + 17, { align: "center" });
        
        yPos += 28;
        
        // ===== TWO COLUMN LAYOUT =====
        const col1X = margin;
        const col2X = pageWidth / 2 + 2;
        const colWidth = (contentWidth / 2) - 2;
        
        // LEFT COLUMN: Risk Distribution
        let yCol1 = yPos;
        doc.setTextColor(darkColor[0], darkColor[1], darkColor[2]);
        doc.setFontSize(11);
        doc.text("Distribusi Risiko", col1X, yCol1);
        yCol1 += 1;
        doc.setDrawColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.setLineWidth(0.3);
        doc.line(col1X, yCol1, col1X + 40, yCol1);
        yCol1 += 6;
        
        if (pred.probabilities) {
            const probData = [];
            for (const [risk, prob] of Object.entries(pred.probabilities)) {
                const rColor = risk.includes("Low") ? successColor : 
                              risk.includes("High") ? dangerColor : warningColor;
                probData.push([risk, (prob * 100).toFixed(1) + "%", rColor]);
            }
            
            probData.forEach(([risk, percent, color]) => {
                // Progress bar background
                doc.setFillColor(230, 230, 230);
                doc.roundedRect(col1X + 30, yCol1 - 3, 50, 5, 1, 1, "F");
                
                // Progress bar fill
                const barWidth = (parseFloat(percent) / 100) * 50;
                doc.setFillColor(color[0], color[1], color[2]);
                doc.roundedRect(col1X + 30, yCol1 - 3, barWidth, 5, 1, 1, "F");
                
                // Label
                doc.setFontSize(8);
                doc.setTextColor(darkColor[0], darkColor[1], darkColor[2]);
                const shortRisk = risk.replace(" Risk", "");
                doc.text(shortRisk, col1X, yCol1);
                doc.text(percent, col1X + 82, yCol1);
                
                yCol1 += 7;
            });
        }
        
        // RIGHT COLUMN: Patient Data Summary
        let yCol2 = yPos;
        doc.setFontSize(11);
        doc.text("Informasi Pasien", col2X, yCol2);
        yCol2 += 1;
        doc.line(col2X, yCol2, col2X + 40, yCol2);
        yCol2 += 6;
        
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
            doc.setFontSize(8);
            doc.setTextColor(100, 100, 100);
            doc.text(label + ":", col2X, yCol2);
            doc.setTextColor(darkColor[0], darkColor[1], darkColor[2]);
            doc.text(String(value), col2X + 32, yCol2);
            yCol2 += 7;
        });
        
        yPos = Math.max(yCol1, yCol2) + 5;
        
        // ===== RECOMMENDATIONS SECTION =====
        doc.setFontSize(11);
        doc.setTextColor(darkColor[0], darkColor[1], darkColor[2]);
        doc.text("Rekomendasi Profesional", margin, yPos);
        yPos += 1;
        doc.setDrawColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.setLineWidth(0.3);
        doc.line(margin, yPos, margin + 60, yPos);
        yPos += 5;
        
        const recommendations = pred.recommendations || [];
        console.log("Writing recommendations to PDF:", recommendations);
        
        if (recommendations && recommendations.length > 0) {
            // Limit to 5 recommendations for space
            const limitedRecs = recommendations.slice(0, 5);
            
            limitedRecs.forEach((rec, idx) => {
                // Compact recommendation item
                doc.setFillColor(250, 251, 252);
                const recText = String(rec);
                const lines = doc.splitTextToSize(recText, contentWidth - 15);
                const itemHeight = Math.min(lines.length * 4 + 4, 12); // Limit height
                
                doc.roundedRect(margin, yPos - 2, contentWidth, itemHeight, 1, 1, "F");
                
                // Number badge (smaller)
                doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
                doc.circle(margin + 3, yPos + 2, 2.5, "F");
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(7);
                doc.text(String(idx + 1), margin + 3, yPos + 2.8, { align: "center" });
                
                // Recommendation text (compact)
                doc.setTextColor(darkColor[0], darkColor[1], darkColor[2]);
                doc.setFontSize(8);
                const displayLines = lines.slice(0, 2); // Max 2 lines per item
                displayLines.forEach((line, lineIdx) => {
                    doc.text(line, margin + 8, yPos + 2 + (lineIdx * 4));
                });
                
                yPos += itemHeight + 2;
            });
            
            // Show count if more recommendations
            if (recommendations.length > 5) {
                doc.setFontSize(7);
                doc.setTextColor(100, 100, 100);
                doc.text("+" + (recommendations.length - 5) + " more recommendations available", margin, yPos);
                yPos += 4;
            }
        } else {
            // No recommendations message
            doc.setFillColor(255, 243, 205);
            doc.roundedRect(margin, yPos - 2, contentWidth, 12, 1, 1, "F");
            
            doc.setTextColor(133, 100, 4);
            doc.setFontSize(8);
            doc.text("⚠ Tidak ada rekomendasi tersedia. Lakukan pemeriksaan baru untuk saran yang dipersonalisasi.", margin + 3, yPos + 4);
            
            yPos += 14;
        }
        
        // ===== FOOTER =====
        const footerY = pageHeight - 15;
        
        // Footer line
        doc.setDrawColor(200, 200, 200);
        doc.setLineWidth(0.3);
        doc.line(margin, footerY, pageWidth - margin, footerY);
        
        // Footer text
        doc.setFontSize(7);
        doc.setTextColor(120, 120, 120);
        doc.text("Sistem Prediksi Kesehatan Mental - Laporan Medis Rahasia", margin, footerY + 4);
        doc.text(new Date().toLocaleDateString() + " " + new Date().toLocaleTimeString(), pageWidth - margin, footerY + 4, { align: "right" });
        
        // Disclaimer
        doc.setFontSize(6);
        doc.setTextColor(150, 150, 150);
        doc.text("Ini adalah pemeriksaan otomatis. Untuk diagnosis profesional, silakan konsultasi dengan profesional kesehatan mental berlisensi.", pageWidth / 2, footerY + 8, { align: "center" });
        
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
            csv += "\\"" + (pred.timestamp || "") + "\\",";
            csv += "\\"" + (pred.prediction || "") + "\\",";
            csv += "\\"" + (((pred.confidence || 0) * 100).toFixed(1)) + "%\\",";
            csv += "\\"" + (input.age || "") + "\\",";
            csv += "\\"" + (input.stress || "") + "\\",";
            csv += "\\"" + (input.anxiety || "") + "\\",";
            csv += "\\"" + (input.depression || "") + "\\",";
            csv += "\\"" + (input.mental_history || "") + "\\",";
            csv += "\\"" + (input.sleep || "") + "\\",";
            csv += "\\"" + (input.exercise || "") + "\\",";
            csv += "\\"" + (input.social_support || "") + "\\"\\n";
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
</script>';

$title = 'Riwayat Pemeriksaan - Mental Health Predictor';
require __DIR__ . '/layout.php';
?>
