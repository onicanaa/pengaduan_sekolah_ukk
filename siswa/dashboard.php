<?php 
session_start(); 
date_default_timezone_set('Asia/Makassar');

$koneksi_file = __DIR__ . '/../config/koneksi.php';
if (file_exists($koneksi_file)) {
    include $koneksi_file;
}

// Ambil nama user dari session atau default
$nama_user = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Siswa';

// Hitung statistik laporan jika ada koneksi database
$total_laporan = 0;
$pending = 0;
$diproses = 0;
$selesai = 0;

if (isset($conn)) {
    $nama_pelapor = mysqli_real_escape_string($conn, $nama_user);
    $total_laporan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE nama_pelapor='$nama_pelapor'"))['total'];
    $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE nama_pelapor='$nama_pelapor' AND status IN ('Pending', '', 'Menunggu')"))['total'];
    $diproses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE nama_pelapor='$nama_pelapor' AND status='Diproses'"))['total'];
    $selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE nama_pelapor='$nama_pelapor' AND status='Selesai'"))['total'];
}

// Cek halaman yang sedang diakses
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$show_form = isset($_GET['action']) && $_GET['action'] == 'create';

if ($show_form) {
    $current_page = 'create';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        if ($current_page == 'create') echo 'Buat Laporan';
        elseif ($current_page == 'riwayat') echo 'Riwayat Laporan';
        else echo 'Dashboard Siswa';
        ?> - Sistem Pengaduan
    </title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
  
*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


:root {
    --primary:       #3b82f6;
    --primary-dark:  #2563eb;
    --secondary:     #64748b;
    --success:       #10b981;
    --warning:       #f59e0b;
    --danger:        #ef4444;
    --info:          #06b6d4;
    --bg-dark:       #0a0a0a;
    --bg-card:       #111111;
    --border:        #1e293b;
    --text:          #f1f5f9;
    --text-muted:    #94a3b8;
    --sidebar-w:     260px;

   
    --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI',
                 Roboto, Helvetica, Arial, sans-serif;
}

html {
    font-size: 16px;
    -webkit-text-size-adjust: 100%;
}

body {
    font-family: var(--font-body);
    background-color: var(--bg-dark);
    color: var(--text);
    line-height: 1.6;
    min-height: 100vh;
    overflow-y: scroll;
}


.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: var(--sidebar-w);
    height: 100vh;
    background: var(--bg-card);
    border-right: 1px solid var(--border);
    z-index: 1000;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}

.sidebar-brand {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-brand i { font-size: 24px; color: var(--primary); }

.user-profile {
    margin-top: 16px;
    padding: 12px;
    background: rgba(255,255,255,0.03);
    border-radius: 8px;
}

.user-profile-name { font-size: 14px; font-weight: 600; color: var(--text); }
.user-profile-role { font-size: 12px; color: var(--text-muted); }

.sidebar-menu { padding: 20px 0; flex: 1; }

.menu-section { margin-bottom: 24px; }

.menu-label {
    padding: 0 20px 8px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: var(--text);
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
    margin: 2px 12px;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
}

.menu-item i { width: 20px; margin-right: 12px; text-align: center; }

.menu-item:hover { background: #1e293b; color: var(--primary); }

.menu-item.active {
    background: var(--primary);
    color: #ffffff;
    font-weight: 600;
}

.menu-item.active i { color: #ffffff; }


.main-content {
    margin-left: var(--sidebar-w);
    min-height: 100vh;
    width: calc(100% - var(--sidebar-w));
}


.header {
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
    padding: 16px 32px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title { font-size: 18px; font-weight: 600; color: var(--text); }

.header-actions { display: flex; gap: 12px; align-items: center; }

.btn-header {
    padding: 10px 20px;
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    font-family: var(--font-body);
    transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-header:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.btn-header.danger:hover {
    background: var(--danger);
    border-color: var(--danger);
}


.page-header {
    padding: 32px 32px 24px;
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
}

.page-title   { font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
.page-subtitle { font-size: 14px; color: var(--text-muted); }


.stats-section { padding: 24px 32px; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.3);
}

.stat-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }

.stat-icon {
    width: 48px; height: 48px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
}

.stat-icon.blue   { background: rgba(59,130,246,0.15); color: #60a5fa; }
.stat-icon.orange { background: rgba(245,158,11,0.15);  color: #fbbf24; }
.stat-icon.cyan   { background: rgba(6,182,212,0.15);   color: #22d3ee; }
.stat-icon.green  { background: rgba(16,185,129,0.15);  color: #34d399; }

.stat-title {
    font-size: 13px; font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.5px;
}

.stat-value { font-size: 36px; font-weight: 700; color: var(--text); line-height: 1; }


.content-section { padding: 24px 32px 40px; }

.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.section-title  { font-size: 20px; font-weight: 700; color: var(--text); }

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.menu-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 24px;
    text-decoration: none;
    color: var(--text);
    transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
    display: flex;
    align-items: center;
    gap: 20px;
}

.menu-card:hover {
    transform: translateY(-4px);
    border-color: var(--primary);
    box-shadow: 0 12px 24px rgba(59,130,246,0.2);
}

.menu-icon {
    width: 56px; height: 56px;
    background: rgba(59,130,246,0.15);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--primary);
    flex-shrink: 0;
}

.menu-content h3 { font-size: 16px; font-weight: 700; margin-bottom: 4px; color: var(--text); }
.menu-content p  { font-size: 13px; color: var(--text-muted); margin: 0; }


.form-section { padding: 24px 32px 40px; }

.form-container {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 32px;
}

.form-title    { font-size: 24px; font-weight: 700; margin-bottom: 8px; color: var(--text); }
.form-subtitle { font-size: 14px; color: var(--text-muted); margin-bottom: 32px; }
.form-group    { margin-bottom: 24px; }

.form-label {
    display: block;
    font-size: 13px; font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
    text-transform: uppercase; letter-spacing: 0.5px;
}

.form-control {
    width: 100%;
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 12px 16px;
    border-radius: 8px;
    font-family: var(--font-body);
    font-size: 14px;
    transition: border-color 0.3s, background 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(255,255,255,0.08);
    color: #ffffff;
}

.form-control::placeholder { color: var(--text-muted); }

.form-control:read-only {
    background: rgba(255,255,255,0.02);
    cursor: not-allowed;
    color: var(--text-muted);
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
    line-height: 1.6;
}

textarea.form-control:focus { color: #ffffff !important; }


.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    gap: 12px;
}

.category-option { position: relative; }


.category-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.category-label {
    display: block;
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--border);
    padding: 16px 12px;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
    font-weight: 600;
    font-size: 13px;
}

.category-label i { display: block; font-size: 20px; margin-bottom: 8px; color: var(--text-muted); }

.category-option input[type="radio"]:checked + .category-label {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.category-option input[type="radio"]:checked + .category-label i { color: white; }
.category-label:hover { border-color: var(--primary); }


.file-input-wrapper { position: relative; overflow: hidden; display: block; width: 100%; }


.file-input-wrapper input[type="file"] {
    position: absolute;
    left: -9999px;
    opacity: 0;
}

.file-input-label {
    display: flex; align-items: center; gap: 12px;
    background: rgba(255,255,255,0.05);
    border: 1px dashed var(--border);
    padding: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: border-color 0.3s, background 0.3s;
}

.file-input-label:hover {
    border-color: var(--primary);
    background: rgba(59,130,246,0.1);
}

.file-icon {
    width: 40px; height: 40px;
    background: rgba(59,130,246,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); font-size: 18px;
    flex-shrink: 0;
}

.file-text { flex: 1; }
.file-text-main { font-weight: 600; font-size: 14px; color: var(--text); }
.file-text-sub  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }


.btn-submit {
    width: 100%;
    background: var(--primary);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-submit:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(59,130,246,0.3);
}

.btn-cancel {
    width: 100%;
    background: transparent;
    color: var(--text);
    border: 1px solid var(--border);
    padding: 14px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    font-family: var(--font-body);
    cursor: pointer;
    transition: border-color 0.3s, color 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    margin-top: 12px;
}

.btn-cancel:hover { border-color: var(--danger); color: var(--danger); }


.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.table-responsive { overflow-x: auto; }

.data-table { width: 100%; border-collapse: collapse; }

.data-table thead th {
    background: rgba(255,255,255,0.02);
    padding: 16px;
    text-align: left;
    font-size: 12px; font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.5px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

.data-table tbody tr { border-bottom: 1px solid var(--border); }
.data-table tbody td { padding: 16px; font-size: 14px; color: var(--text); vertical-align: middle; }

.id-badge {
    display: inline-block;
    background: var(--primary);
    color: white;
    padding: 4px 12px;
    border-radius: 6px;
    font-weight: 700; font-size: 13px;
}

.reporter-name { font-weight: 600; color: var(--text); }
.reporter-date { font-size: 12px; color: var(--text-muted); }

.kategori-badge {
    display: inline-flex; align-items: center; gap: 8px;
    color: var(--text); font-weight: 600; font-size: 13px;
    text-transform: uppercase;
}

.kategori-badge i { color: var(--primary); }

.report-content { max-width: 400px; }
.report-title   { font-weight: 700; color: var(--text); margin-bottom: 6px; display: block; }
.report-desc    { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px; font-weight: 600;
}

.status-pending { background: rgba(245,158,11,0.15); color: #fbbf24; }
.status-proses  { background: rgba(6,182,212,0.15);  color: #22d3ee; }
.status-selesai { background: rgba(16,185,129,0.15); color: #34d399; }


.attachment-img {
    width: 50px; height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: transform 0.2s;
    display: block;
}

.attachment-img:hover { transform: scale(1.1); }

.no-attachment {
    width: 50px; height: 50px;
    background: rgba(255,255,255,0.03);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted);
}


.empty-state { text-align: center; padding: 80px 20px; color: var(--text-muted); }
.empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; display: block; }
.empty-state p { font-size: 16px; margin: 0; }


::-webkit-scrollbar       { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: var(--bg-dark); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: #334155; }


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

.fade-in { animation: fadeIn 0.5s ease-out both; }


@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .sidebar.active { transform: translateX(0); }

    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .menu-grid             { grid-template-columns: 1fr; }
    .category-grid         { grid-template-columns: repeat(2, 1fr); }
    .stats-grid            { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .header-actions { gap: 8px; }
    .btn-header     { padding: 8px 14px; font-size: 13px; }
    .form-container { padding: 24px; }
    .stats-section,
    .content-section,
    .form-section   { padding-left: 16px; padding-right: 16px; }
    .page-header    { padding: 24px 16px; }
    .header         { padding: 16px; }
}

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}

.sidebar-overlay.active { display: block; }

.mobile-toggle {
    display: none;
    position: fixed;
    bottom: 24px; right: 24px;
    width: 56px; height: 56px;
    background: var(--primary);
    border-radius: 50%;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(59,130,246,0.4);
    z-index: 998;
    align-items: center; justify-content: center;
}

@media (max-width: 992px) {
    .mobile-toggle { display: flex; }
}
    </style>
</head>
<body>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class=""></i>
            <span>Selamat Datang</span>
        </div>
        <div class="user-profile">
            <div class="user-profile-name"><?= htmlspecialchars($nama_user) ?></div>
            <div class="user-profile-role">Siswa</div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-label">Menu Utama</div>
            <a href="dashboard.php" class="menu-item <?= $current_page == 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="?action=create" class="menu-item <?= $current_page == 'create' ? 'active' : '' ?>">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Laporan</span>
            </a>
            <a href="?page=riwayat" class="menu-item <?= $current_page == 'riwayat' ? 'active' : '' ?>">
                <i class="fas fa-history"></i>
                <span>Riwayat Laporan</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-label">Akun</div>
            <a href="../auth/login_admin.php" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>

<!-- Mobile Toggle Button -->
<button class="mobile-toggle" id="mobileToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Main Content -->
<main class="main-content">
    <?php if ($current_page == 'dashboard'): ?>
    <!-- Dashboard View -->
    
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-title">Dashboard Siswa</div>
            <div class="header-actions">
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Halo, <?= htmlspecialchars($nama_user) ?>! </h1>
    </div>

  

    <?php elseif ($current_page == 'create'): ?>
    <!-- Form View -->
    
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-title">Buat Laporan Baru</div>
            <div class="header-actions">
                <a href="../auth/login_admin.php" class="btn-header">
                    <i class=""></i>
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Formulir Laporan</h1>
        <p class="page-subtitle">Isi formulir di bawah untuk mengajukan pengaduan</p>
    </div>

    <!-- Form Container -->
    <section class="form-section">
        <div class="form-container">
            <form action="proses.php" method="POST" enctype="multipart/form-data">
                
                <!-- Nama Pelapor -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Nama Pelapor
                    </label>
                    <input type="text" class="form-control" name="nama" 
                           value="<?= htmlspecialchars($nama_user) ?>" readonly>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Kategori Laporan
                    </label>
                    <div class="category-grid">
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Sarana" id="cat1" required>
                            <label class="category-label" for="cat1">
                                <i class=""></i>
                                Sarana
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Kebersihan" id="cat2">
                            <label class="category-label" for="cat2">
                                <i class=""></i>
                                Kebersihan
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Keamanan" id="cat3">
                            <label class="category-label" for="cat3">
                                <i class=""></i>
                                Keamanan
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" value="Lainnya" id="cat4">
                            <label class="category-label" for="cat4">
                                <i class=""></i>
                                Lainnya
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Judul Laporan
                    </label>
                    <input type="text" class="form-control" name="judul" 
                           placeholder="Judul laporan" required>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left me-2"></i>Deskripsi
                    </label>
                    <textarea class="form-control" name="isi" 
                              placeholder="Deskripsi" required></textarea>
                </div>

                <!-- Upload Foto -->
                <div class="form-group">
                    <label class="form-label">
                        <i class=""></i>Upload Foto (Opsional)
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file" name="foto" id="fotoInput" accept="image/*">
                        <label for="fotoInput" class="file-input-label">
                            <div class="">
                                <i class=""></i>
                            </div>
                            <div class="file-text">
                                <div class="file-text-main">Pilih foto atau drag & drop</div>
                                <div class="file-text-sub">PNG, JPG, JPEG (Max. 5MB)</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="submit" class="btn-submit">
                    <i class=""></i>Kirim Laporan
                </button>

                <a href="dashboard_siswa.php" class="btn-cancel">Batal</a>
            </form>
        </div>
    </section>

    <?php elseif ($current_page == 'riwayat'): ?>
    <!-- Riwayat View -->
    
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-title">Riwayat Laporan</div>
            <div class="header-actions">
                <a href="?action=create" class="btn-header">
                  
                </a>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Riwayat Laporan Anda</h1>
        <p class="page-subtitle">Lihat semua laporan pengaduan yang pernah Anda buat</p>
    </div>

    <!-- Content Section -->
    <section class="content-section">
        <div class="table-card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th style="width: 180px;">Pelapor</th>
                            <th style="width: 140px;">Kategori</th>
                            <th>Detail Laporan</th>
                            <th style="width: 100px; text-align: center;">Lampiran</th>
                            <th style="width: 140px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($conn)) {
                            // Query SEMUA laporan, tidak difilter berdasarkan user
                            $sql = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id DESC");
                            
                            if ($sql && mysqli_num_rows($sql) > 0) {
                                while ($data = mysqli_fetch_array($sql)) {
                                    $st = $data['status'] ?: 'Pending';
                                    $classStatus = ($st == "Diproses") ? "status-proses" : (($st == "Selesai") ? "status-selesai" : "status-pending");
                                    $iconStatus = ($st == "Diproses") ? "fa-spinner fa-spin" : (($st == "Selesai") ? "fa-check-circle" : "fa-clock");
                                    
                                    $tanggal = isset($data['tanggal']) && !empty($data['tanggal']) ? $data['tanggal'] : date('Y-m-d H:i:s');
                                    $timestamp = strtotime($tanggal);
                                    $tanggal_format = date('d M Y', $timestamp);
                                    $waktu_format = date('H:i', $timestamp) . ' WITA';
                        ?>
                        <tr>
                            <td>
                                <span class="id-badge"><?= $data['id'] ?></span>
                            </td>
                            <td>
                                <div class="reporter-info">
                                    <span class="reporter-name"><?= htmlspecialchars($data['nama_pelapor'] ?? 'Anonim') ?></span>
                                    <span class="reporter-date">
                                        <i class="far fa-calendar-alt"></i> <?= $tanggal_format ?>
                                    </span>
                                    <span class="reporter-date">
                                        <i class="far fa-clock"></i> <?= $waktu_format ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="kategori-badge">
                                    <i class="fas fa-tag"></i>
                                    <?= htmlspecialchars($data['kategori'] ?? 'Umum') ?>
                                </div>
                            </td>
                            <td>
                                <div class="report-content">
                                    <span class="report-title"><?= htmlspecialchars($data['judul_laporan'] ?? 'Tanpa Judul') ?></span>
                                    <span class="report-desc"><?= htmlspecialchars($data['isi_laporan'] ?? '-') ?></span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <?php if (!empty($data['foto'])): ?>
                                    <img src="uploads/<?= $data['foto'] ?>" class="attachment-img" onclick="viewFoto('uploads/<?= $data['foto'] ?>')" alt="Lampiran">
                                <?php else: ?>
                                    <div class="no-attachment">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-badge <?= $classStatus ?>">
                                    <i class="fas <?= $iconStatus ?>"></i>
                                    <?= strtoupper($st) ?>
                                </span>
                            </td>
                        </tr>
                        <?php 
                                } 
                            } else {
                                echo "<tr><td colspan='6' class='empty-state'>
                                        <i class='fas fa-folder-open'></i>
                                        <p>Belum ada data laporan.</p>
                                      </td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='empty-state'>
                                    <i class='fas fa-exclamation-triangle'></i>
                                    <p>Koneksi database tidak tersedia</p>
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php endif; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // View Photo Function
    function viewFoto(url) {
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Lampiran',
            background: '#0a0a0a',
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px'
        });
    }

    // Mobile Menu Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    // File Input Preview
    const fotoInput = document.getElementById('fotoInput');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('.file-text-main');
                if (label) {
                    label.textContent = fileName;
                    label.style.color = 'var(--primary)';
                }
            }
        });
    }
</script>

</body>
</html>
