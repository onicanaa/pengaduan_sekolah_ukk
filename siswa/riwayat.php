<?php
if (!@include('../config/koneksi.php')) {
    die("<div style='color:white; padding:50px; background:#450a0a; font-family:sans-serif; text-align:center;'>
            <h2 style='color:#ffffff;'>Akses Terputus</h2>
            <p>File konfigurasi sistem tidak ditemukan.</p>
         </div>");
}
?>

<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Work+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --bg-deep: #494747; 
        --surface: rgba(131, 15, 15, 0.9); 
        --white: #ffffff;
        --text-dim: rgba(255, 255, 255, 0.8);
    }

    body {
        background-color: var(--bg-deep);
        font-family: 'Work Sans', sans-serif;
        margin: 0;
        padding: 0; 
        color: var(--white);
        min-height: 100vh;
        background-image: 
            linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    
    .back-nav { margin-bottom: 30px; }
    .btn-back {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: var(--white) !important;
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.4rem;
        letter-spacing: 2px;
        transition: 0.3s;
        gap: 10px;
    }
    .btn-back:hover { opacity: 0.7; transform: translateX(-5px); }

    
    .header-section { 
        border-left: 8px solid var(--white); 
        padding-left: 20px; 
        margin-bottom: 40px; 
    }
    .header-section h2 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 3.5rem;
        margin: 0;
        letter-spacing: 2px;
        color: var(--white);
    }
    .header-section p {
        color: var(--white); 
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.3rem;
        margin: 5px 0 0 0;
        letter-spacing: 3px;
        opacity: 0.9;
    }

    
    .data-card {
        background: var(--surface);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 20px;
        border: 1px solid var(--white);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        padding: 15px;
        color: var(--white);
        font-family: 'Bebas Neue', sans-serif;
        font-size: 1.3rem;
        letter-spacing: 1px;
        text-align: left;
        border-bottom: 2px solid var(--white);
    }

    .modern-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modern-table td {
        padding: 20px 15px;
        vertical-align: top;
        color: var(--white);
    }

    
    .id-badge {
        background: var(--white);
        color: #000;
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    
    .status-pill {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--white);
        color: var(--white);
    }

    .status-pending { background: rgba(255, 255, 255, 0.1); }
    .status-diproses { background: rgba(255, 255, 255, 0.2); }
    .status-selesai { background: rgba(255, 255, 255, 0.3); }

    .border-kategori {
        color: var(--white);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .report-title {
        font-weight: 700;
        color: var(--white);
        display: block;
        margin-bottom: 5px;
        font-size: 1.1rem;
    }

    .report-desc {
        font-size: 0.9rem;
        color: var(--text-dim);
        line-height: 1.5;
    }

    .table-responsive { overflow-x: auto; }

</style>

<div class="dashboard-container">
    <nav class="back-nav">
        <a href="../siswa/dashboard.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> KEMBALI KE BERANDA
        </a>
    </nav>

    <header class="header-section">
        <h2>RIWAYAT LAPORAN</h2>
        <p>PENGADUAN DIGITAL SEKOLAH</p>
    </header>

    <div class="data-card">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 200px;">PELAPOR</th>
                        <th style="width: 150px;">KATEGORI</th>
                        <th>ISI PENGADUAN</th>
                        <th style="width: 140px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id DESC");
                    
                    if ($sql && mysqli_num_rows($sql) > 0) {
                        while ($data = mysqli_fetch_array($sql)) {
                            $st = $data['status'] ?: 'Pending';
                            $classStatus = ($st == "Diproses") ? "status-diproses" : (($st == "Selesai") ? "status-selesai" : "status-pending");
                            $iconStatus = ($st == "Diproses") ? "fa-spinner fa-spin" : (($st == "Selesai") ? "fa-check-circle" : "fa-clock");
                    ?>
                    <tr>
                        <td><span class="id-badge"><?= $data['id'] ?></span></td>
                        <td>
                            <div style="font-weight: 700;"><?= htmlspecialchars($data['nama_pelapor'] ?? 'Anonim') ?></div>
                            <div style="font-size: 0.8rem; color: var(--text-dim); margin-top:4px;">
                                <i class="far fa-calendar-alt me-1"></i> <?= isset($data['tanggal']) ? date('d M Y', strtotime($data['tanggal'])) : '-' ?>
                            </div>
                        </td>
                        <td>
                            <div class="border-kategori">
                                <i class="fas fa-tag" style="color: white;"></i>
                                <?= htmlspecialchars($data['kategori'] ?? 'Umum') ?>
                            </div>
                        </td>
                        <td>
                            <div class="wrap-text">
                                <span class="report-title"><?= htmlspecialchars($data['judul_laporan'] ?? 'Tanpa Judul') ?></span>
                                <span class="report-desc"><?= htmlspecialchars($data['isi_laporan'] ?? '-') ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-pill <?= $classStatus ?>">
                                <i class="fas <?= $iconStatus ?>"></i>
                                <?= strtoupper($st) ?>
                            </span>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding:80px; color:white;'>
                                <i class='fas fa-folder-open fa-3x mb-3'></i><br>Belum ada data laporan.
                              </td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>