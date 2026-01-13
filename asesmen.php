<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesmen Kesehatan Mental - Mental Health Predictor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .progress-bar {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .step.active .step-number {
            background: #667eea;
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            color: #666;
        }

        .form-content {
            padding: 40px;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .section-subtitle {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
            font-size: 1.05rem;
        }

        input[type="range"] {
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: #e0e0e0;
            outline: none;
            -webkit-appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #667eea;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        input[type="range"]::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #667eea;
            cursor: pointer;
            border: none;
        }

        .range-value {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 10px;
            min-width: 50px;
            text-align: center;
        }

        select {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        select:focus {
            border-color: #667eea;
            outline: none;
        }

        .range-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #999;
            margin-top: 8px;
        }

        small {
            display: block;
            color: #999;
            margin-top: 5px;
            font-size: 0.85rem;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        button {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-prev {
            background: #e0e0e0;
            color: #666;
        }

        .btn-prev:hover {
            background: #d0d0d0;
        }

        .btn-next {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .result-card {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .result-card.show {
            display: block;
            animation: fadeIn 0.5s;
        }

        .risk-badge {
            display: inline-block;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 20px 0;
        }

        .risk-low {
            background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
            color: white;
        }

        .risk-medium {
            background: linear-gradient(135deg, #ffd93d 0%, #f6c90e 100%);
            color: #333;
        }

        .risk-high {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        .recommendations {
            text-align: left;
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .recommendations h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .recommendations ul {
            list-style: none;
            padding: 0;
        }

        .recommendations li {
            padding: 12px 15px;
            margin: 8px 0;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧠 Asesmen Kesehatan Mental</h1>
            <p>Sistem Prediksi Risiko Kesehatan Mental Berbasis Machine Learning</p>
        </div>

        <div class="progress-bar">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Profil</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Mental & Emosi</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Gaya Hidup</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Hasil</div>
            </div>
        </div>

        <form id="assessmentForm">
            <div class="form-content">
                <!-- Section 1: Profil -->
                <div class="form-section active" data-section="1">
                    <h2 class="section-title">📋 Informasi Diri</h2>
                    <p class="section-subtitle">Ceritakan sedikit tentang diri Anda untuk membantu kami memberikan rekomendasi yang lebih personal</p>

                    <div class="form-group">
                        <label>Berapa usia Anda? <span class="range-value" id="ageDisplay">25</span> tahun</label>
                        <input type="range" id="age" name="age" min="18" max="80" value="25">
                        <div class="range-labels">
                            <span>18</span>
                            <span>80</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="gender" id="gender">
                            <option value="Female">Perempuan</option>
                            <option value="Male">Laki-laki</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Apa status pekerjaan Anda saat ini?</label>
                        <select name="employment_status" id="employment_status">
                            <option value="Employed">Bekerja (karyawan/wiraswasta)</option>
                            <option value="Unemployed">Tidak bekerja</option>
                            <option value="Student">Pelajar/Mahasiswa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bagaimana lingkungan kerja/belajar Anda?</label>
                        <select name="work_environment" id="work_environment">
                            <option value="Office">Di kantor/kampus</option>
                            <option value="Remote">Remote/Dari rumah (WFH)</option>
                            <option value="Hybrid">Hybrid (kombinasi)</option>
                        </select>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-next" onclick="nextSection()">Lanjut →</button>
                    </div>
                </div>

                <!-- Section 2: Mental & Emosi -->
                <div class="form-section" data-section="2">
                    <h2 class="section-title">🧠 Kesehatan Mental & Emosi</h2>
                    <p class="section-subtitle">Bagaimana kondisi mental dan emosi Anda dalam beberapa minggu terakhir?</p>

                    <div class="form-group">
                        <label>Apakah Anda memiliki riwayat gangguan kesehatan mental sebelumnya?</label>
                        <select name="mental_health_history" id="mental_health_history">
                            <option value="No">Tidak, tidak ada riwayat</option>
                            <option value="Yes">Ya, pernah mengalami</option>
                        </select>
                        <small>Contoh: depresi, kecemasan/anxiety, gangguan panik, PTSD, dll.</small>
                    </div>

                    <div class="form-group">
                        <label>Apakah Anda saat ini sedang mencari atau mendapat bantuan profesional?</label>
                        <select name="seeks_treatment" id="seeks_treatment">
                            <option value="No">Tidak</option>
                            <option value="Yes">Ya, sedang konseling/terapi</option>
                        </select>
                        <small>Seperti berkonsultasi dengan psikolog, psikiater, atau konselor</small>
                    </div>

                    <div class="form-group">
                        <label>Seberapa tinggi tingkat stress yang Anda rasakan? <span class="range-value" id="stressDisplay">5</span>/10</label>
                        <input type="range" id="stress_level" name="stress_level" min="0" max="10" value="5">
                        <div class="range-labels">
                            <span>0 - Sangat tenang</span>
                            <span>10 - Sangat stress</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Skor Depresi - Seberapa sering Anda merasa sedih/tidak bersemangat? <span class="range-value" id="depressionDisplay">15</span>/50</label>
                        <input type="range" id="depression_score" name="depression_score" min="0" max="50" value="15">
                        <div class="range-labels">
                            <span>0 - Tidak pernah</span>
                            <span>50 - Sangat sering</span>
                        </div>
                        <small>PHQ-9: 0-4 minimal, 5-9 ringan, 10-14 sedang, 15-19 cukup parah, 20+ parah</small>
                    </div>

                    <div class="form-group">
                        <label>Skor Kecemasan - Seberapa sering Anda merasa cemas/khawatir berlebihan? <span class="range-value" id="anxietyDisplay">10</span>/30</label>
                        <input type="range" id="anxiety_score" name="anxiety_score" min="0" max="30" value="10">
                        <div class="range-labels">
                            <span>0 - Tidak cemas</span>
                            <span>30 - Sangat cemas</span>
                        </div>
                        <small>GAD-7: 0-4 minimal, 5-9 ringan, 10-14 sedang, 15+ parah</small>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-prev" onclick="prevSection()">← Sebelumnya</button>
                        <button type="button" class="btn-next" onclick="nextSection()">Lanjut →</button>
                    </div>
                </div>

                <!-- Section 3: Gaya Hidup -->
                <div class="form-section" data-section="3">
                    <h2 class="section-title">💪 Gaya Hidup & Aktivitas</h2>
                    <p class="section-subtitle">Kebiasaan sehari-hari sangat berpengaruh pada kesehatan mental Anda</p>

                    <div class="form-group">
                        <label>Berapa rata-rata jam tidur Anda per malam? <span class="range-value" id="sleepDisplay">7.0</span> jam</label>
                        <input type="range" id="sleep_hours" name="sleep_hours" min="0" max="12" step="0.5" value="7">
                        <div class="range-labels">
                            <span>0 jam</span>
                            <span>12 jam</span>
                        </div>
                        <small>Ideal: 7-9 jam per malam untuk orang dewasa</small>
                    </div>

                    <div class="form-group">
                        <label>Berapa hari dalam seminggu Anda melakukan aktivitas fisik/olahraga? <span class="range-value" id="activityDisplay">3</span> hari</label>
                        <input type="range" id="physical_activity_days" name="physical_activity_days" min="0" max="7" value="3">
                        <div class="range-labels">
                            <span>Tidak pernah</span>
                            <span>Setiap hari</span>
                        </div>
                        <small>Termasuk jalan kaki, jogging, gym, olahraga ringan, berkebun, dll.</small>
                    </div>

                    <div class="form-group">
                        <label>Seberapa kuat dukungan sosial yang Anda miliki? <span class="range-value" id="socialDisplay">50</span>/100</label>
                        <input type="range" id="social_support_score" name="social_support_score" min="0" max="100" value="50">
                        <div class="range-labels">
                            <span>Tidak ada dukungan</span>
                            <span>Sangat didukung</span>
                        </div>
                        <small>Apakah ada keluarga/teman yang bisa Anda andalkan saat butuh bantuan emosional?</small>
                    </div>

                    <div class="form-group">
                        <label>Bagaimana tingkat produktivitas Anda akhir-akhir ini? <span class="range-value" id="productivityDisplay">70</span>/100</label>
                        <input type="range" id="productivity_score" name="productivity_score" min="0" max="100" value="70">
                        <div class="range-labels">
                            <span>Sangat tidak produktif</span>
                            <span>Sangat produktif</span>
                        </div>
                        <small>Apakah Anda bisa menyelesaikan tugas-tugas sehari-hari dengan baik?</small>
                    </div>

                    <div class="button-group">
                        <button type="button" class="btn-prev" onclick="prevSection()">← Sebelumnya</button>
                        <button type="button" class="btn-next" onclick="submitForm()">🎯 Lihat Hasil Asesmen</button>
                    </div>
                </div>

                <!-- Section 4: Result -->
                <div class="result-card" id="resultCard">
                    <h2 class="section-title">📊 Hasil Asesmen Anda</h2>
                    <div id="resultContent"></div>
                    <button type="button" class="btn-next" onclick="location.reload()" style="margin-top: 30px;">🔄 Mulai Asesmen Baru</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentSection = 1;

        // Update range displays
        document.getElementById('age').addEventListener('input', (e) => {
            document.getElementById('ageDisplay').textContent = e.target.value;
        });

        document.getElementById('stress_level').addEventListener('input', (e) => {
            document.getElementById('stressDisplay').textContent = e.target.value;
        });

        document.getElementById('depression_score').addEventListener('input', (e) => {
            document.getElementById('depressionDisplay').textContent = e.target.value;
        });

        document.getElementById('anxiety_score').addEventListener('input', (e) => {
            document.getElementById('anxietyDisplay').textContent = e.target.value;
        });

        document.getElementById('sleep_hours').addEventListener('input', (e) => {
            document.getElementById('sleepDisplay').textContent = parseFloat(e.target.value).toFixed(1);
        });

        document.getElementById('physical_activity_days').addEventListener('input', (e) => {
            document.getElementById('activityDisplay').textContent = e.target.value;
        });

        document.getElementById('social_support_score').addEventListener('input', (e) => {
            document.getElementById('socialDisplay').textContent = e.target.value;
        });

        document.getElementById('productivity_score').addEventListener('input', (e) => {
            document.getElementById('productivityDisplay').textContent = e.target.value;
        });

        function nextSection() {
            const sections = document.querySelectorAll('.form-section');
            
            sections[currentSection - 1].classList.remove('active');
            currentSection++;
            sections[currentSection - 1].classList.add('active');
            
            updateProgress();
            window.scrollTo(0, 0);
        }

        function prevSection() {
            const sections = document.querySelectorAll('.form-section');
            
            sections[currentSection - 1].classList.remove('active');
            currentSection--;
            sections[currentSection - 1].classList.add('active');
            
            updateProgress();
            window.scrollTo(0, 0);
        }

        function updateProgress() {
            const steps = document.querySelectorAll('.step');
            steps.forEach((step, index) => {
                if (index < currentSection) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }

        function submitForm() {
            // Collect form data
            const formData = {
                age: parseInt(document.getElementById('age').value),
                gender: document.getElementById('gender').value,
                employment_status: document.getElementById('employment_status').value,
                work_environment: document.getElementById('work_environment').value,
                mental_health_history: document.getElementById('mental_health_history').value,
                seeks_treatment: document.getElementById('seeks_treatment').value,
                stress_level: parseInt(document.getElementById('stress_level').value),
                sleep_hours: parseFloat(document.getElementById('sleep_hours').value),
                physical_activity_days: parseInt(document.getElementById('physical_activity_days').value),
                depression_score: parseInt(document.getElementById('depression_score').value),
                anxiety_score: parseInt(document.getElementById('anxiety_score').value),
                social_support_score: parseInt(document.getElementById('social_support_score').value),
                productivity_score: parseInt(document.getElementById('productivity_score').value)
            };

            // Simple risk calculation
            const riskScore = calculateRisk(formData);
            
            // Hide form, show result
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            document.getElementById('resultCard').classList.add('show');
            
            // Update progress
            currentSection = 4;
            updateProgress();
            
            // Display result
            displayResult(riskScore, formData);
            window.scrollTo(0, 0);
        }

        function calculateRisk(data) {
            const stressWeight = (data.stress_level / 10) * 0.25;
            const depressionWeight = (data.depression_score / 50) * 0.35;
            const anxietyWeight = (data.anxiety_score / 30) * 0.25;
            const sleepWeight = (7 - Math.abs(7 - data.sleep_hours)) / 7 * 0.10;
            const activityWeight = (data.physical_activity_days / 7) * 0.05;
            
            const totalRisk = stressWeight + depressionWeight + anxietyWeight + (1 - sleepWeight) + (1 - activityWeight);
            
            return totalRisk;
        }

        function displayResult(riskScore, data) {
            let riskLevel, riskClass, recommendations;
            
            if (riskScore > 0.6) {
                riskLevel = 'Risiko Tinggi';
                riskClass = 'risk-high';
                recommendations = [
                    '🚨 Sangat disarankan untuk segera konsultasi dengan profesional kesehatan mental (psikolog atau psikiater)',
                    '👥 Hubungi keluarga atau teman dekat untuk mendapatkan dukungan emosional',
                    '📞 Hotline Crisis Indonesia: 119 ext 8 atau 021-500-454 (24/7)',
                    '🧘 Praktikkan teknik relaksasi seperti meditasi atau pernapasan dalam',
                    '😴 Prioritaskan tidur yang cukup (7-9 jam per malam)',
                    '🚫 Hindari alkohol dan zat adiktif yang dapat memperburuk kondisi',
                    '💊 Jika sudah dalam pengobatan, pastikan minum obat sesuai resep dokter'
                ];
            } else if (riskScore > 0.35) {
                riskLevel = 'Risiko Sedang';
                riskClass = 'risk-medium';
                recommendations = [
                    '📊 Monitor kondisi kesehatan mental Anda secara rutin',
                    '🧘 Lakukan meditasi atau mindfulness setiap hari (minimal 10 menit)',
                    '👫 Jaga koneksi dengan orang-orang terdekat dan ceritakan perasaan Anda',
                    '💬 Pertimbangkan untuk konseling atau terapi dengan psikolog',
                    '🏃 Olahraga teratur minimal 30 menit per hari, 3-5 kali seminggu',
                    '🥗 Jaga pola makan sehat dan seimbang, batasi kafein',
                    '📱 Kurangi penggunaan media sosial jika terasa overwhelming'
                ];
            } else {
                riskLevel = 'Risiko Rendah';
                riskClass = 'risk-low';
                recommendations = [
                    '✅ Pertahankan gaya hidup sehat yang sudah Anda jalani',
                    '👨‍👩‍👧 Tetap terhubung dengan keluarga dan teman secara rutin',
                    '💆 Lakukan aktivitas self-care yang Anda sukai',
                    '📝 Pantau perubahan mood atau perilaku untuk deteksi dini',
                    '🤝 Dukung orang lain yang mungkin sedang kesulitan',
                    '😴 Pertahankan jadwal tidur yang konsisten',
                    '🎨 Luangkan waktu untuk hobi dan aktivitas yang menyenangkan'
                ];
            }
            
            const resultHTML = `
                <div class="risk-badge ${riskClass}">${riskLevel}</div>
                <p style="margin: 20px 0; color: #666; line-height: 1.6;">
                    Berdasarkan jawaban Anda, tingkat risiko kesehatan mental Anda termasuk dalam kategori <strong>${riskLevel}</strong>. 
                    Berikut adalah rekomendasi yang dapat membantu Anda:
                </p>
                <div class="recommendations">
                    <h3>💡 Rekomendasi untuk Anda</h3>
                    <ul>
                        ${recommendations.map(rec => `<li>${rec}</li>`).join('')}
                    </ul>
                </div>
                <p style="margin-top: 25px; padding: 15px; background: #fff3cd; border-radius: 10px; color: #856404; line-height: 1.6;">
                    <strong>⚠️ Penting:</strong> Ini adalah skrining awal berbasis machine learning, bukan diagnosis medis resmi. 
                    Untuk evaluasi yang lebih akurat, konsultasikan dengan profesional kesehatan mental yang berkualifikasi.
                </p>
            `;
            
            document.getElementById('resultContent').innerHTML = resultHTML;
        }
    </script>
</body>
</html>
