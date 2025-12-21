<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Predictor - Cek Kesehatan Mental Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-login {
            background: white;
            color: #667eea;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            overflow: hidden;
        }
        
        .carousel-item {
            height: 600px;
        }
        
        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            bottom: auto;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .btn-hero {
            background: white;
            color: #667eea;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            background: #f8f9fc;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            font-size: 2rem;
        }
        
        .feature-icon.primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .feature-icon.success { background: linear-gradient(135deg, #1cc88a, #17a673); color: white; }
        .feature-icon.warning { background: linear-gradient(135deg, #f6c23e, #dda20a); color: white; }
        .feature-icon.info { background: linear-gradient(135deg, #36b9cc, #2c9faf); color: white; }
        
        .feature-card h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .stat-number i {
            font-size: 2.5rem;
            opacity: 0.9;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.95;
            font-weight: 500;
        }
        
        /* CTA Section */
        .cta {
            padding: 80px 0;
            background: white;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        
        .cta p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0 20px 0;
        }
        
        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }
        
        footer a:hover {
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .carousel-item {
                height: 500px;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .stat-item {
                margin-bottom: 2rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .stat-number i {
                font-size: 2rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats {
                padding: 40px 0;
            }
            
            .stat-number {
                font-size: 1.3rem;
            }
            
            .stat-number i {
                font-size: 1.8rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="home">
                <i class="bi bi-heart-pulse me-2"></i>
                Mental Health Predictor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#about">Tentang</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-login ms-2" href="dashboard">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-login ms-2" href="login">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div style="width: 100%; height: 600px; background: linear-gradient(rgba(102, 126, 234, 0.7), rgba(118, 75, 162, 0.7)), url('assets/images/unnamed.jpg') center center / cover no-repeat;"></div>
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mx-auto text-center">
                                    <h1 class="display-4 fw-bold mb-4">
                                        Jaga Kesehatan Mental Anda
                                    </h1>
                                    <p class="lead mb-4">
                                        Platform prediksi kesehatan mental berbasis AI yang membantu Anda memahami dan menjaga kesehatan mental dengan lebih baik.
                                    </p>
                                    <a href="<?= isset($_SESSION['user_id']) ? 'assessment' : 'login' ?>" class="btn btn-hero">
                                        <i class="bi bi-clipboard-heart me-2"></i>Mulai Tes Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div style="width: 100%; height: 600px; background: linear-gradient(rgba(102, 126, 234, 0.7), rgba(118, 75, 162, 0.7)), url('assets/images/unnamed (1).jpg') center center / cover no-repeat;"></div>
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mx-auto text-center">
                                    <h1 class="display-4 fw-bold mb-4">
                                        Dukungan Kesehatan Mental
                                    </h1>
                                    <p class="lead mb-4">
                                        Kami hadir untuk mendukung perjalanan kesehatan mental Anda dengan teknologi terkini.
                                    </p>
                                    <a href="<?= isset($_SESSION['user_id']) ? 'professionals' : 'login' ?>" class="btn btn-hero">
                                        <i class="bi bi-geo-alt me-2"></i>Cari Psikolog
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div style="width: 100%; height: 600px; background: linear-gradient(rgba(102, 126, 234, 0.7), rgba(118, 75, 162, 0.7)), url('assets/images/unnamed (2).jpg') center center / cover no-repeat;"></div>
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mx-auto text-center">
                                    <h1 class="display-4 fw-bold mb-4">
                                        Kesehatan Mental Adalah Prioritas
                                    </h1>
                                    <p class="lead mb-4">
                                        Mulai perjalanan menuju kesejahteraan mental yang lebih baik hari ini.
                                    </p>
                                    <a href="<?= isset($_SESSION['user_id']) ? 'dashboard' : 'register' ?>" class="btn btn-hero">
                                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slide 4 -->
                <div class="carousel-item">
                    <div style="width: 100%; height: 600px; background: linear-gradient(rgba(102, 126, 234, 0.7), rgba(118, 75, 162, 0.7)), url('assets/images/unnamed (3).jpg') center center / cover no-repeat;"></div>
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8 mx-auto text-center">
                                    <h1 class="display-4 fw-bold mb-4">
                                        Bersama Kita Kuat
                                    </h1>
                                    <p class="lead mb-4">
                                        Tidak ada yang sendirian dalam perjalanan kesehatan mental.
                                    </p>
                                    <a href="#features" class="btn btn-hero">
                                        <i class="bi bi-info-circle me-2"></i>Pelajari Lebih Lanjut
                                    </a>
                                </div>
                            </div>
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
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Fitur Unggulan Kami</h2>
                <p class="text-muted">Platform lengkap untuk membantu kesehatan mental Anda</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card">
                        <div class="feature-icon primary">
                            <i class="bi bi-clipboard-heart"></i>
                        </div>
                        <h4>Tes Kesehatan Mental</h4>
                        <p>Penilaian komprehensif untuk mengukur tingkat stres, kecemasan, dan depresi Anda.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'assessment' : 'login' ?>" class="btn btn-sm btn-outline-primary mt-2">
                            Coba Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card">
                        <div class="feature-icon success">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Prediksi AI</h4>
                        <p>Algoritma machine learning untuk memberikan prediksi akurat tentang kondisi mental Anda.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card">
                        <div class="feature-icon warning">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Cari Psikolog</h4>
                        <p>Temukan psikolog profesional terdekat untuk konsultasi lebih lanjut.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'professionals' : 'login' ?>" class="btn btn-sm btn-outline-warning mt-2">
                            Cari Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card">
                        <div class="feature-icon info">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h4>Riwayat Tes</h4>
                        <p>Pantau perkembangan kesehatan mental Anda dari waktu ke waktu.</p>
                        <a href="<?= isset($_SESSION['user_id']) ? 'history' : 'login' ?>" class="btn btn-sm btn-outline-info mt-2">
                            Lihat Riwayat <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 col-sm-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="bi bi-shield-check"></i>
                            <span>100% Aman</span>
                        </div>
                        <div class="stat-label">Data Terenkripsi</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Akurat</span>
                        </div>
                        <div class="stat-label">Prediksi AI Terpercaya</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="bi bi-clock-history"></i>
                            <span>24/7</span>
                        </div>
                        <div class="stat-label">Akses Kapan Saja</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="cta" id="about">
        <div class="container text-center">
            <h2>Tentang Mental Health Predictor</h2>
            <p>Platform ini dikembangkan untuk membantu masyarakat dalam memantau dan menjaga kesehatan mental mereka. Menggunakan teknologi Machine Learning, kami memberikan prediksi yang akurat dan rekomendasi yang personal.</p>
            
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 fw-bold">Privasi Terjamin</h5>
                        <p class="text-muted">Data Anda aman dan terenkripsi dengan standar keamanan tinggi.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-cpu text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 fw-bold">Teknologi AI</h5>
                        <p class="text-muted">Menggunakan algoritma machine learning terkini untuk prediksi akurat.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-heart text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 fw-bold">Peduli Kesehatan</h5>
                        <p class="text-muted">Komitmen kami untuk mendukung kesehatan mental masyarakat.</p>
                    </div>
                </div>
            </div>
            
            <a href="<?= isset($_SESSION['user_id']) ? 'dashboard' : 'register' ?>" class="btn btn-hero mt-4">
                <i class="bi bi-person-plus me-2"></i><?= isset($_SESSION['user_id']) ? 'Ke Dashboard' : 'Daftar Sekarang' ?>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-heart-pulse me-2"></i>Mental Health Predictor
                    </h5>
                    <p class="text-white-50">Platform prediksi kesehatan mental berbasis AI untuk membantu Anda menjaga kesehatan mental.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="home">Beranda</a></li>
                        <li class="mb-2"><a href="<?= isset($_SESSION['user_id']) ? 'dashboard' : 'login' ?>">Dashboard</a></li>
                        <li class="mb-2"><a href="<?= isset($_SESSION['user_id']) ? 'assessment' : 'login' ?>">Tes Kesehatan</a></li>
                        <li class="mb-2"><a href="<?= isset($_SESSION['user_id']) ? 'professionals' : 'login' ?>">Cari Psikolog</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>support@mentalhealthpredictor.com</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>+62 xxx-xxxx-xxxx</li>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0 text-white-50">&copy; 2025 Mental Health Predictor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
