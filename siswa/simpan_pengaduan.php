<?php 

date_default_timezone_set('Asia/Makassar');


if (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php'; 
} else {
    die("Error: File koneksi.php tidak ditemukan.");
}

if (!isset($conn)) {
    $conn = isset($koneksi) ? $koneksi : die("Error: Variabel koneksi tidak ditemukan.");
}

$update_status_js = "";
if (isset($_GET['id']) && isset($_GET['status']) && isset($_GET['trigger'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $query = mysqli_query($conn, "UPDATE laporan SET status='$status' WHERE id='$id'");

    if ($query) {
        $update_status_js = "
            Swal.fire({
                title: 'Berhasil!',
                text: 'Status laporan diperbarui menjadi $status.',
                icon: 'success',
                background: '#0a0a0a',
                color: '#f1f5f9',
                confirmButtonColor: '#3b82f6'
            }).then(() => { window.location.href = 'simpan_pengaduan.php'; });";
    }
}

if (isset($_GET['delete_id'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $query_hapus = mysqli_query($conn, "DELETE FROM laporan WHERE id='$id_hapus'");
    if ($query_hapus) {
        header("Location: simpan_pengaduan.php");
        exit();
    }
}

$total   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan"))['total'];
$proses  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status='Diproses'"))['total'];
$selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status='Selesai'"))['total'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status IN ('Pending', '', 'Menunggu')"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
     @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');


*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary:      #3b82f6;
    --primary-dark: #2563eb;
    --secondary:    #64748b;
    --success:      #10b981;
    --warning:      #f59e0b;
    --danger:       #ef4444;
    --info:         #06b6d4;
    --light:        #1e293b;
    --dark:         #0f172a;
    --border:       #1e293b;
    --text:         #f1f5f9;
    --text-muted:   #94a3b8;
    --bg-dark:      #0a0a0a;
    --bg-card:      #111111;
    --sidebar-w:    260px;

 
    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI',
            Roboto, Helvetica, Arial, sans-serif;
}

html {
    font-size: 16px;
    -webkit-text-size-adjust: 100%;
}

body {
    font-family: var(--font);
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

.sidebar-brand i {
    font-size: 24px;
    color: var(--primary);
}

.sidebar-menu {
    padding: 20px 0;
    flex: 1;
}

.menu-section {
    margin-bottom: 24px;
}

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
    font-family: var(--font);
}

.menu-item i {
    width: 20px;
    margin-right: 12px;
    text-align: center;
    flex-shrink: 0;
}

.menu-item:hover {
    background: var(--light);
    color: var(--primary);
}

.menu-item.active {
    background: var(--primary);
    color: #ffffff;
    font-weight: 600;
}

.menu-item.active i {
    color: #ffffff;
}


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
    gap: 16px;
}


.search-box {
    position: relative;
    width: 400px;
    flex-shrink: 0;
}

.search-box input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    font-family: var(--font);
    transition: border-color 0.2s, box-shadow 0.2s;
    background: var(--bg-dark);
    color: var(--text);
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-box input::placeholder {
    color: var(--text-muted);
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
}


.header-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.admin-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.admin-info:hover {
    background: var(--light);
}

.admin-details {
    text-align: right;
}

.admin-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
}

.admin-role {
    font-size: 12px;
    color: var(--text-muted);
}


.page-header {
    padding: 32px 32px 24px;
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 8px;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-muted);
}


.stats-section {
    padding: 24px 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.stat-icon.blue   { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
.stat-icon.orange { background: rgba(245, 158, 11, 0.15);  color: #fbbf24; }
.stat-icon.cyan   { background: rgba(6, 182, 212, 0.15);   color: #22d3ee; }
.stat-icon.green  { background: rgba(16, 185, 129, 0.15);  color: #34d399; }

.stat-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
}


.content-section {
    padding: 24px 32px 40px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
}


.table-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead th {
    background: rgba(255, 255, 255, 0.02);
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

.data-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.2s;
}

.data-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.02);
}


.data-table tbody td {
    padding: 16px;
    font-size: 14px;
    color: var(--text);
    vertical-align: top; 
}


.reporter-name {
    font-weight: 600;
    color: var(--text);
}

.report-title {
    font-weight: 600;
    color: var(--text);
    margin-bottom: 4px;
    display: block;
}

.report-desc {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.5;
}


.attachment-img {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: transform 0.2s;
    display: block;
    margin: 0 auto;
}

.attachment-img:hover {
    transform: scale(1.1);
}

.no-attachment {
    width: 55px;
    height: 55px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    margin: 0 auto;
}


.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.status-pending { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
.status-proses  { background: rgba(6, 182, 212, 0.15);  color: #22d3ee; }
.status-selesai { background: rgba(16, 185, 129, 0.15); color: #34d399; }


.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
    align-items: center;
}

.action-btn {
    width: 34px;
    height: 34px;
    border: 1px solid var(--border);
    background: var(--bg-dark);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border-color 0.2s, transform 0.2s;
    color: var(--text);
    text-decoration: none;
    flex-shrink: 0;
    font-family: var(--font);
}

.action-btn i {
    font-size: 14px;
    pointer-events: none;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-btn.view:hover   { background: var(--primary); color: white; border-color: var(--primary); }
.action-btn.status:hover { background: var(--info);    color: white; border-color: var(--info); }
.action-btn.edit:hover   { background: var(--warning); color: white; border-color: var(--warning); }
.action-btn.delete:hover { background: var(--danger);  color: white; border-color: var(--danger); }


::-webkit-scrollbar        { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: var(--bg-dark); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: var(--secondary); }


@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .sidebar.active {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .search-box {
        width: 100%;
    }

    .header-content {
        flex-wrap: wrap;
    }
}

@media (max-width: 768px) {
    .header        { padding: 16px; }
    .page-header    { padding: 24px 16px; }
    .stats-section  { padding: 16px; }
    .content-section { padding: 16px 16px 40px; }
    .stats-grid     { grid-template-columns: repeat(2, 1fr); }
    .admin-details  { display: none; }
}
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class=""></i>
            <span>Selamat Datang</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-label">Menu Utama</div>
            <a href="#" class="menu-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-label">Tools</div>
            <a href="export_excel.php" class="menu-item">
                <i class="fas fa-file-excel"></i>
                <span>Export Data</span>
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

<main class="main-content">
    <header class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari laporan, nama pelapor, atau status...">
                </div>
                
                <div id="digital-clock" style="font-family: 'Inter', sans-serif; font-weight: 600; color: var(--primary); background: rgba(59, 130, 246, 0.1); padding: 8px 15px; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2); min-width: 100px; text-align: center; white-space: nowrap;">
                    00:00:00
                </div>
            </div>

            <div class="header-right">
                <div class="admin-info">
                    <div class="admin-details">
                        <div class="admin-name">Admin</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="page-header">
        <h1 class="page-title">Halo Admin</h1>
        <p class="page-subtitle">Kelola dan pantau semua laporan pengaduan</p>
    </div>

    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Total Laporan</div>
                    </div>
                </div>
                <div class="stat-value"><?= $total ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Pending</div>
                    </div>
                </div>
                <div class="stat-value"><?= $pending ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Diproses</div>
                    </div>
                </div>
                <div class="stat-value"><?= $proses ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Selesai</div>
                    </div>
                </div>
                <div class="stat-value"><?= $selesai ?></div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-header">
            <h2 class="section-title">Data Pengaduan</h2>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelapor</th>
                            <th>Detail Laporan</th>
                            <th style="text-align: center;">Lampiran</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: center; width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id DESC");
                        while ($data = mysqli_fetch_array($sql)) {
                            $st = $data['status'] ?: 'Pending';
                            $statusClass = ($st == "Diproses") ? "status-proses" : (($st == "Selesai") ? "status-selesai" : "status-pending");
                            
                            $tanggal = isset($data['tanggal']) && !empty($data['tanggal']) ? $data['tanggal'] : date('Y-m-d H:i:s');
                            $timestamp = strtotime($tanggal);
                            $tanggal_format = date('d M Y', $timestamp);
                            $waktu_format = date('H:i', $timestamp) . ' WITA';
                        ?>
                        <tr>
                            <td style="vertical-align: top;">
                                <div style="font-weight: 600;"><?= $tanggal_format ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?= $waktu_format ?></div>
                            </td>
                            <td style="vertical-align: top;">
                                <div class="reporter-name"><?= htmlspecialchars($data['nama_pelapor']) ?></div>
                            </td>
                            <td style="max-width: 350px; vertical-align: top;">
                                <div class="report-title"><?= htmlspecialchars($data['judul_laporan']) ?></div>
                                <div class="report-desc"><?= htmlspecialchars(substr($data['isi_laporan'], 0, 100)) ?>...</div>
                            </td>
                            <td style="text-align: center; vertical-align: top;">
                                <?php if (!empty($data['foto'])): ?>
                                    <img src="uploads/<?= $data['foto'] ?>" class="attachment-img" onclick="viewFoto('uploads/<?= $data['foto'] ?>')" alt="Lampiran">
                                <?php else: ?>
                                    <div class="no-attachment">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center; vertical-align: top;">
                                <span class="status-badge <?= $statusClass ?>"><?= $st ?></span>
                            </td>
                            <td style="text-align: center; vertical-align: top;">
                                <div class="action-buttons">
                                    <button class="action-btn view" title="Lihat Detail" onclick="viewDetail('<?= $data['id'] ?>', '<?= addslashes(htmlspecialchars($data['nama_pelapor'])) ?>', '<?= addslashes(htmlspecialchars($data['judul_laporan'])) ?>', '<?= addslashes(htmlspecialchars($data['isi_laporan'])) ?>', '<?= $data['foto'] ?>', '<?= $tanggal_format ?>', '<?= $waktu_format ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button class="action-btn status" title="Ubah Status" onclick="changeStatus(<?= $data['id'] ?>, '<?= $st ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <a href="edit.php?id=<?= $data['id'] ?>" class="action-btn edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="action-btn delete" title="Hapus" onclick="confirmDelete(<?= $data['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?= $update_status_js ?>
    
   
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('digital-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    //  Photo
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

    //  Status
    function changeStatus(id, currentStatus) {
        Swal.fire({
            title: 'Update Status',
            html: `Status saat ini: <strong>${currentStatus}</strong>`,
            background: '#0a0a0a',
            color: '#f1f5f9',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Selesai',
            denyButtonText: 'Diproses',
            cancelButtonText: 'Pending',
            confirmButtonColor: '#10b981',
            denyButtonColor: '#06b6d4',
            cancelButtonColor: '#f59e0b'
        }).then((result) => {
            let newStatus = "";
            if (result.isConfirmed) newStatus = "Selesai";
            else if (result.isDenied) newStatus = "Diproses";
            else if (result.dismiss === Swal.DismissReason.cancel) newStatus = "Pending";
            
            if (newStatus !== "") {
                window.location.href = `simpan_pengaduan.php?id=${id}&status=${newStatus}&trigger=update`;
            }
        });
    }

    // View Detail
    function viewDetail(id, nama, judul, deskripsi, foto, tanggal, waktu) {
        let fotoHtml = foto ? `<div class="mt-3"><img src="uploads/${foto}" style="width: 100%; border-radius: 8px;"></div>` : '';
        
        let content = `
            <div style="text-align: left; padding: 20px;">
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 4px;">TANGGAL LAPORAN</div>
                    <div style="font-size: 16px; font-weight: 600;">${tanggal} ${waktu}</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 4px;">PELAPOR</div>
                    <div style="font-size: 18px; font-weight: 700;">${nama}</div>
                </div>
                
                <div style="background: rgba(255, 255, 255, 0.03); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 8px;">DETAIL LAPORAN</div>
                    <p style="margin: 0; line-height: 1.6; white-space: pre-wrap;">${deskripsi}</p>
                </div>
                
                ${fotoHtml}
            </div>`;

        Swal.fire({
            title: judul,
            html: content,
            width: '700px',
            background: '#0a0a0a',
            color: '#f1f5f9',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Tutup'
        });
    }

    // Confirm Delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Laporan?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            background: '#0a0a0a',
            color: '#f1f5f9',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `simpan_pengaduan.php?delete_id=${id}`;
            }
        });
    }

    // Search Functionality
    const searchInput = document.querySelector('.search-box input');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

</body>
</html>
