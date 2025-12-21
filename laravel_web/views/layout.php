<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mental Health Predictor' ?></title>
    
    <!-- Preconnect to CDN for faster loading -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary: #4e73df;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --dark: #5a5c69;
            --light: #f8f9fc;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            overflow-x: hidden;
        }
        
        /* Navigation Bar */
        .navbar-custom {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.15);
            padding: 0.75rem 0;
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: var(--topbar-height);
            background: rgba(0, 0, 0, 0.1);
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            padding: 1rem;
        }
        
        .sidebar-brand:hover {
            color: white;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .menu-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        
        .menu-item i {
            font-size: 1.2rem;
            width: 30px;
        }
        
        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.15);
            margin: 1rem 0;
        }
        
        .sidebar-heading {
            padding: 0.5rem 1.5rem;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Main Content */
        .main-content {
            min-height: calc(100vh - 200px);
        }
        
        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: white;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.05);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            justify-content: space-between;
        }
        
        .topbar-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .topbar-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .topbar-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        /* Navigation Links */
        .nav-link-custom {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s;
        }
        
        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.35rem;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 2rem 0 1rem;
            margin-top: 3rem;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: white;
        }
        
        /* Page Content */
        .page-content {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Stats Cards */
        .stat-card {
            border-left: 4px solid;
            padding: 1.25rem;
            background: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.05);
        }
        
        .stat-card.primary { border-left-color: var(--primary); }
        .stat-card.success { border-left-color: var(--success); }
        .stat-card.warning { border-left-color: var(--warning); }
        .stat-card.danger { border-left-color: var(--danger); }
        .stat-card.info { border-left-color: var(--info); }
        
        .stat-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }
        
        /* Forms */
        .form-label {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.35rem;
            padding: 0.6rem 1.25rem;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background: #2e59d9;
            border-color: #2653d4;
        }
        
        /* Range Slider */
        .range-value {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 45px;
            text-align: center;
        }
        
        .form-range::-webkit-slider-thumb {
            background: var(--primary);
        }
        
        .form-range::-moz-range-thumb {
            background: var(--primary);
        }
        
        /* Table */
        .table {
            font-size: 0.875rem;
        }
        
        .table thead th {
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.75rem;
            border-bottom: 2px solid #e3e6f0;
        }
        
        /* Badges */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        /* Loading */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .loading-spinner {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                min-height: calc(100vh - 300px);
            }
        }
    </style>
    
    <?= $extraStyles ?? '' ?>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="dashboard">
                <i class="bi bi-heart-pulse me-2"></i>
                Cek Kesehatan Mental
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($page ?? '') == 'dashboard' ? 'active' : '' ?>" href="dashboard">
                            <i class="bi bi-house-heart me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($page ?? '') == 'assessment' ? 'active' : '' ?>" href="index.php">
                            <i class="bi bi-clipboard-heart me-1"></i> Cek Kesehatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($page ?? '') == 'history' ? 'active' : '' ?>" href="history">
                            <i class="bi bi-clock-history me-1"></i> Riwayat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($page ?? '') == 'professionals' ? 'active' : '' ?>" href="professionals">
                            <i class="bi bi-geo-alt me-1"></i> Cari Psikolog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom <?= ($page ?? '') == 'about' ? 'active' : '' ?>" href="about">
                            <i class="bi bi-info-circle me-1"></i> Tentang Sistem
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link-custom" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= $_SESSION['user_name'] ?? 'User' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-header">
                                    <strong><?= $_SESSION['user_name'] ?? 'User' ?></strong><br>
                                    <small class="text-muted"><?= $_SESSION['user_email'] ?? '' ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="audit-trail">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas
                            </a></li>
                            <li><a class="dropdown-item" href="consent">
                                <i class="bi bi-shield-check me-2"></i>Pengaturan Privasi
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Top Section -->
    <div class="bg-white py-3 border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-primary"><?= $pageTitle ?? 'Dashboard' ?></h4>
                <span class="text-muted small">
                    <i class="bi bi-calendar3"></i>
                    <?= date('d M Y') ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid px-4 py-4">
            <?= $content ?? '' ?>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Mental Health Predictor
                    </h5>
                    <p class="small mb-0 text-white-50">
                        Platform untuk membantu Anda memahami dan menjaga kesehatan mental dengan lebih baik.
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="dashboard"><i class="bi bi-chevron-right me-1"></i> Beranda</a></li>
                        <li class="mb-2"><a href="index.php"><i class="bi bi-chevron-right me-1"></i> Cek Kesehatan Mental</a></li>
                        <li class="mb-2"><a href="history"><i class="bi bi-chevron-right me-1"></i> Riwayat</a></li>
                        <li class="mb-2"><a href="professionals"><i class="bi bi-chevron-right me-1"></i> Cari Psikolog</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <span class="text-white-50">support@mentalhealth.com</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <span class="text-white-50">119 (Layanan Kesehatan Jiwa)</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-globe me-2"></i>
                            <a href="https://sejiwa.com" target="_blank">www.sejiwa.com</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.2);">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="small mb-0 text-white-50">
                        &copy; <?= date('Y') ?> Mental Health Predictor. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-dark fw-bold">Processing...</div>
        </div>
    </div>

    <!-- Bootstrap JS - Deferred loading -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <script>
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }
        
        // Update range value display
        function updateValue(id, value) {
            const element = document.getElementById(id + 'Value');
            if (element) {
                element.textContent = value;
            }
        }
    </script>
    
    <?= $extraScripts ?? '' ?>
</body>
</html>
