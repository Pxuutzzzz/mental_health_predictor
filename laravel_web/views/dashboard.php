<?php
$page = 'dashboard';
$pageTitle = 'Dashboard';

ob_start();
?>

<!-- Hero Carousel Section -->
<div class="position-relative mb-4" style="margin-left: -1.5rem; margin-right: -1.5rem; margin-top: -1.5rem; overflow: hidden;">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3500" data-bs-pause="false">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div style="width: 100%; height: 450px; background: url('assets/images/unnamed.jpg') center center / contain no-repeat;"></div>
                <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">
                            <span data-lang-id="Selamat datang kembali, <?= $_SESSION['user_name'] ?? 'User' ?>!" 
                                  data-lang-en="Welcome back, <?= $_SESSION['user_name'] ?? 'User' ?>!">
                                Selamat datang kembali, <?= $_SESSION['user_name'] ?? 'User' ?>!
                            </span>
                        </h2>
                        <p class="mb-0 fs-5" data-lang-id="Jaga kesehatan mental Anda. Bersama-sama kita dukung kesejahteraan mental." 
                           data-lang-en="Take care of your mental health. Together we support mental wellness.">
                            Jaga kesehatan mental Anda. Bersama-sama kita dukung kesejahteraan mental.
                        </p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div style="width: 100%; height: 450px; background: url('assets/images/unnamed (1).jpg') center center / contain no-repeat;"></div>
                <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Dukungan Kesehatan Mental</h2>
                        <p class="mb-0 fs-5">Kami hadir untuk mendukung perjalanan kesehatan mental Anda.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div style="width: 100%; height: 450px; background: url('assets/images/unnamed (2).jpg') center center / contain no-repeat;"></div>
                <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Kesehatan Mental Adalah Prioritas</h2>
                        <p class="mb-0 fs-5">Mulai perjalanan menuju kesejahteraan mental yang lebih baik.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div style="width: 100%; height: 450px; background: url('assets/images/unnamed (3).jpg') center center / contain no-repeat;"></div>
                <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Bersama Kita Kuat</h2>
                        <p class="mb-0 fs-5">Tidak ada yang sendirian dalam perjalanan kesehatan mental.</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-start border-4 border-primary shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">
                            <i class="bi bi-emoji-smile"></i> 
                            <span data-lang-id="Mulai Perjalanan Kesehatan Mental Anda" 
                                  data-lang-en="Start Your Mental Health Journey">
                                Mulai Perjalanan Kesehatan Mental Anda
                            </span>
                        </h4>
                        <p class="text-muted mb-0" data-lang-id="Jaga kesehatan mental Anda. Baca artikel-artikel di bawah untuk belajar lebih banyak tentang cara menjaga pikiran dan gaya hidup yang sehat." 
                           data-lang-en="Take care of your mental health. Read our articles below to learn more about maintaining a healthy mind and lifestyle.">
                            Jaga kesehatan mental Anda. Baca artikel-artikel di bawah untuk belajar lebih banyak tentang cara menjaga pikiran dan gaya hidup yang sehat.
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="bi bi-heart-pulse text-primary" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card primary h-100 shadow-sm" style="cursor: pointer;" onclick="window.location.href='index.php'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title" data-lang-id="Cek Kesehatan Mental" data-lang-en="Take Assessment">Cek Kesehatan Mental</div>
                        <div class="stat-value" style="font-size: 1.2rem;" data-lang-id="Mulai Sekarang" data-lang-en="Start Now">Mulai Sekarang</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clipboard-heart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card success h-100 shadow-sm" style="cursor: pointer;" onclick="window.location.href='history'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title" data-lang-id="Lihat Riwayat" data-lang-en="View History">Lihat Riwayat</div>
                        <div class="stat-value" style="font-size: 1.2rem;" data-lang-id="Data Saya" data-lang-en="My Records">Data Saya</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card info h-100 shadow-sm" style="cursor: pointer;" onclick="window.location.href='professionals'">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-title" data-lang-id="Cari Bantuan" data-lang-en="Find Help">Cari Bantuan</div>
                        <div class="stat-value" style="font-size: 1.2rem;" data-lang-id="Dekat Anda" data-lang-en="Near You">Dekat Anda</div>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mental Health Articles -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-book"></i> 
                    <span data-lang-id="Artikel Kesehatan Mental" data-lang-en="Mental Health Articles">Artikel Kesehatan Mental</span>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Article 1 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-brain text-primary"></i>
                                </div>
                                <h5 class="card-title">Memahami Kesehatan Mental</h5>
                                <p class="card-text text-muted small">
                                    Informasi lengkap seputar kesehatan mental, gejala gangguan mental, dan cara menjaga kesehatan jiwa.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.alodokter.com/cari-tahu-informasi-seputar-kesehatan-mental-di-sini" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Alodokter - Kesehatan Mental <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 2 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-emoji-smile text-success"></i>
                                </div>
                                <h5 class="card-title">Mengelola Stres & Kecemasan</h5>
                                <p class="card-text text-muted small">
                                    Ternyata tidak sulit mengatasi stres. Pelajari berbagai cara efektif untuk mengelola stres dalam kehidupan sehari-hari.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.alodokter.com/ternyata-tidak-sulit-mengatasi-stres" target="_blank" class="btn btn-sm btn-outline-success">
                                        Alodokter - Mengatasi Stres <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 3 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-moon-stars text-info"></i>
                                </div>
                                <h5 class="card-title">Tidur & Kesehatan Mental</h5>
                                <p class="card-text text-muted small">
                                    Kualitas tidur yang baik sangat penting untuk kesehatan mental. Pahami hubungan tidur dengan kesejahteraan psikologis.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.rssansani.co.id/detail-artikel/27" target="_blank" class="btn btn-sm btn-outline-info">
                                        RS Sansani - Kualitas Tidur <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 4 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-heart-pulse text-danger"></i>
                                </div>
                                <h5 class="card-title">Olahraga & Kesehatan Mental</h5>
                                <p class="card-text text-muted small">
                                    Alasan olahraga baik untuk menjaga kesehatan mental. Ketahui manfaat aktivitas fisik bagi kesejahteraan psikologis.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.halodoc.com/artikel/alasan-olahraga-baik-untuk-menjaga-kesehatan-mental" target="_blank" class="btn btn-sm btn-outline-danger">
                                        Halodoc - Olahraga <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 5 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-people text-warning"></i>
                                </div>
                                <h5 class="card-title">Hubungan Sosial & Mental</h5>
                                <p class="card-text text-muted small">
                                    Kesehatan mental dan hubungan sosial: bagaimana menjaga keseimbangan dalam interaksi sosial untuk kesejahteraan.
                                </p>
                                <div class="mt-3">
                                    <a href="https://rsjrw.id/artikel/kesehatan-mental-dan-hubungan-sosial-bagaimana-menjaga-keseimbangan" target="_blank" class="btn btn-sm btn-outline-warning">
                                        RSJ RW - Hubungan Sosial <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 6 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-apple text-success"></i>
                                </div>
                                <h5 class="card-title">Makanan & Kesehatan Mental</h5>
                                <p class="card-text text-muted small">
                                    3 makanan untuk kesehatan mental lebih baik. Kenali nutrisi yang dapat membantu meningkatkan mood dan fungsi otak.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.halodoc.com/artikel/3-makanan-untuk-kesehatan-mental-lebih-baik" target="_blank" class="btn btn-sm btn-outline-success">
                                        Halodoc - Makanan Sehat <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 7 - Indonesia -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-flag text-danger"></i>
                                </div>
                                <h5 class="card-title">Kesehatan Mental Remaja</h5>
                                <p class="card-text text-muted small">
                                    Riset kesehatan mental remaja Indonesia terus meningkat. Data dan fakta penting dari penelitian Universitas Airlangga.
                                </p>
                                <div class="mt-3">
                                    <a href="https://unair.ac.id/riset-kesehatan-mental-remaja-indonesia-terus-meningkat/" target="_blank" class="btn btn-sm btn-outline-danger">
                                        Unair - Riset Remaja <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 8 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-heart text-primary"></i>
                                </div>
                                <h5 class="card-title">Mengenal Depresi</h5>
                                <p class="card-text text-muted small">
                                    Informasi lengkap tentang depresi: gejala, penyebab, diagnosis, dan cara mengatasinya secara medis dan mandiri.
                                </p>
                                <div class="mt-3">
                                    <a href="https://www.alodokter.com/depresi" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Alodokter - Depresi <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Article 9 -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 article-card shadow-sm">
                            <div class="card-body">
                                <div class="article-icon mb-3">
                                    <i class="bi bi-clipboard-heart text-info"></i>
                                </div>
                                <h5 class="card-title">Self Care & Mindfulness</h5>
                                <p class="card-text text-muted small">
                                    Kesehatan mental dan mindfulness: bagaimana praktik mindfulness dapat membantu menjaga kesejahteraan mental Anda.
                                </p>
                                <div class="mt-3">
                                    <a href="https://selfcare.id/news/kesehatan-mental-dan-mindfulness" target="_blank" class="btn btn-sm btn-outline-info">
                                        Selfcare.id - Mindfulness <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mental Health Tips -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-lightbulb"></i> Tips Kesehatan Mental Harian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-sunrise"></i>
                            </div>
                            <h6>Mulai Hari dengan Baik</h6>
                            <p class="small text-muted mb-0">Mulailah dengan 5 menit meditasi atau pernapasan dalam untuk mengatur nada positif sepanjang hari.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-water"></i>
                            </div>
                            <h6>Tetap Terhidrasi</h6>
                            <p class="small text-muted mb-0">Minum banyak air sepanjang hari. Dehidrasi dapat mempengaruhi suasana hati dan fungsi kognitif.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-phone-vibrate"></i>
                            </div>
                            <h6>Detoks Digital</h6>
                            <p class="small text-muted mb-0">Istirahat dari layar. Batasi penggunaan media sosial dan tetapkan batasan untuk teknologi.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <h6>Buat Jurnal</h6>
                            <p class="small text-muted mb-0">Tuliskan pikiran dan perasaan Anda. Menulis jurnal dapat membantu memproses emosi dan mengurangi stres.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-music-note"></i>
                            </div>
                            <h6>Dengarkan Musik</h6>
                            <p class="small text-muted mb-0">Musik dapat meningkatkan suasana hati dan mengurangi kecemasan. Buat playlist yang membangkitkan semangat Anda.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="tip-card">
                            <div class="tip-icon">
                                <i class="bi bi-chat-heart"></i>
                            </div>
                            <h6>Bicara dengan Seseorang</h6>
                            <p class="small text-muted mb-0">Jangan ragu untuk menghubungi. Berbicara dengan teman, keluarga, atau profesional sangat membantu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Resources -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-danger">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="bi bi-exclamation-triangle"></i> Butuh Bantuan Segera?
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-telephone-fill text-danger fs-3 mb-2"></i>
                            <div class="fw-bold">Hotline Krisis</div>
                            <a href="tel:500454" class="text-danger fw-bold fs-5">500-454</a>
                            <div class="small text-muted">Tersedia 24/7</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-hospital-fill text-danger fs-3 mb-2"></i>
                            <div class="fw-bold">Darurat</div>
                            <a href="tel:119" class="text-danger fw-bold fs-5">119</a>
                            <div class="small text-muted">Darurat Medis</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-chat-dots-fill text-info fs-3 mb-2"></i>
                            <div class="fw-bold">Into The Light</div>
                            <a href="tel:+6281287877788" class="text-info fw-bold" style="font-size: 0.9rem;">+62 812-8787-7788</a>
                            <div class="small text-muted">Pencegahan Bunuh Diri</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="bi bi-heart-pulse-fill text-success fs-3 mb-2"></i>
                            <div class="fw-bold">Yayasan Pulih</div>
                            <a href="tel:+62217880015" class="text-success fw-bold" style="font-size: 0.9rem;">+62 21 788-0015</a>
                            <div class="small text-muted">Pemulihan Trauma</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.article-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.article-icon i {
    font-size: 3rem;
}

.tip-card {
    padding: 1.5rem;
    background: #f8f9fc;
    border-radius: 0.5rem;
    height: 100%;
    transition: all 0.2s;
}

.tip-card:hover {
    background: #e2e6ea;
    transform: translateY(-2px);
}

.tip-icon i {
    font-size: 2.5rem;
    color: #1cc88a;
    margin-bottom: 1rem;
}

.tip-card h6 {
    color: #4e73df;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
</style>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
