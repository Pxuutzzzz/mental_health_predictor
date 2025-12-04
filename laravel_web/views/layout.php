<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mental Health Predictor' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
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
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
            padding: 0;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem rgba(33, 40, 50, 0.15);
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
            margin-left: var(--sidebar-width);
            min-height: 100vh;
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
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block !important;
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
    </style>
    
    <?= $extraStyles ?? '' ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="dashboard" class="sidebar-brand">
            <i class="bi bi-heart-pulse me-2"></i>
            <span id="brandName">Cek Kesehatan Mental</span>
        </a>
        
        <div class="sidebar-menu">
            <div class="sidebar-heading">MENU UTAMA</div>
            <a href="dashboard" class="menu-item <?= ($page ?? '') == 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-house-heart"></i>
                <span>Beranda</span>
            </a>
            <a href="index.php" class="menu-item <?= ($page ?? '') == 'assessment' ? 'active' : '' ?>">
                <i class="bi bi-clipboard-heart"></i>
                <span>Cek Kesehatan Mental</span>
            </a>
            <a href="history" class="menu-item <?= ($page ?? '') == 'history' ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat</span>
            </a>
            <a href="professionals" class="menu-item <?= ($page ?? '') == 'professionals' ? 'active' : '' ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Cari Psikolog</span>
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn btn-link mobile-menu-btn" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="topbar-title"><?= $pageTitle ?? 'Dashboard' ?></div>
            <div class="topbar-actions">
                <span class="text-muted small me-3">
                    <i class="bi bi-calendar3"></i>
                    <?= date('d M Y') ?>
                </span>
                <div class="dropdown">
                    <button class="btn btn-link text-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="ms-1"><?= $_SESSION['user_name'] ?? 'User' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header">
                                <strong><?= $_SESSION['user_name'] ?? 'User' ?></strong><br>
                                <small class="text-muted"><?= $_SESSION['user_email'] ?? '' ?></small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="page-content">
            <?= $content ?? '' ?>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="text-dark fw-bold">Processing...</div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
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
