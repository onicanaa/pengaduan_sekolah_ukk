<?php

if (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php';
} else {
    die("Error: File koneksi.php tidak ditemukan.");
}

if (!isset($conn)) {
    $conn = isset($koneksi) ? $koneksi : die("Error: Variabel koneksi tidak ditemukan.");
}


if (!isset($_GET['id'])) {
    header("Location: simpan_pengaduan.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM laporan WHERE id = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='edit.php';</script>";
    exit;
}


if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul_laporan']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi_laporan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = mysqli_query($conn, "UPDATE laporan SET 
        nama_pelapor = '$nama', 
        judul_laporan = '$judul', 
        isi_laporan = '$isi', 
        kategori = '$kategori', 
        status = '$status' 
        WHERE id = '$id'");

    if ($update) {
        echo "<script>
                alert('Data berhasil diperbarui!'); 
                window.location.href='edit.php';
              </script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

body {
    /* Latar belakang hitam pekat dengan gradien halus */
    background-color: #0f172a;
    background-image: 
        radial-gradient(circle at 50% -20%, #1e293b, transparent),
        linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
    background-size: 100% 100%, 30px 30px, 30px 30px;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.edit-card {
    /* Kartu berwarna abu-abu sangat gelap */
    background: #1a1a1a; 
    border-radius: 24px;
    padding: 40px;
    width: 100%;
    max-width: 700px;
    position: relative;
    border: 1px solid #333333;
    /* Glow putih tipis untuk estetika */
    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
}

.form-title {
    color: #ffffff;
    font-weight: 700;
    text-align: center;
    margin-bottom: 30px;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.form-label {
    color: #e2e8f0;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 8px;
    margin-left: 4px;
}

.form-control, .form-select {
    /* Input field berwarna hitam doff */
    background: #2d2d2d;
    border: 1px solid #444444;
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: none !important; 
    padding-right: 16px; 
}

.form-control:focus, .form-select:focus {
    /* Highlight saat fokus menggunakan warna putih/abu terang */
    background: #333333;
    border-color: #ffffff;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
    color: #ffffff;
    outline: none;
}

.btn-update {
    /* Tombol hitam glossy / kontras putih */
    background: linear-gradient(90deg, #ffffff, #e2e8f0);
    border: none;
    padding: 14px 30px;
    border-radius: 14px;
    font-weight: 700;
    color: #000000; /* Teks hitam di tombol putih */
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
}

.btn-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
    filter: brightness(0.9);
}

.btn-back {
    background: transparent;
    border: 1px solid #444444;
    color: #ffffff;
    padding: 14px 25px;
    border-radius: 14px;
    font-weight: 600;
    transition: 0.3s;
}

.btn-back:hover {
    background: #333333;
    color: #ffffff;
    border-color: #666666;
}

textarea.form-control {
    resize: none;
}
    </style>
</head>
<body>

<div class="edit-card">
    <h3 class="form-title">
        Edit Laporan <i class=""></i>
    </h3>
    
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Nama Pelapor</label>
            <input type="text" name="nama_pelapor" class="form-control" value="<?= htmlspecialchars($data['nama_pelapor']); ?>" required>
        </div>
        
        <div class="mb-4">
            <label class="form-label">Judul Laporan</label>
            <input type="text" name="judul_laporan" class="form-control" value="<?= htmlspecialchars($data['judul_laporan']); ?>" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Isi Laporan</label>
            <textarea name="isi_laporan" class="form-control" rows="4" required><?= htmlspecialchars($data['isi_laporan']); ?></textarea>
        </div>

        <div class="row g-4">
            <div class="col-md-6 mb-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="Sarana" <?= $data['kategori'] == 'Sarana' ? 'selected' : ''; ?>>Sarana</option>
                    <option value="Kebersihan" <?= $data['kategori'] == 'Kebersihan' ? 'selected' : ''; ?>>Kebersihan</option>
                    <option value="Keamanan" <?= $data['kategori'] == 'Keamanan' ? 'selected' : ''; ?>>Keamanan</option>
                    <option value="Lainnya" <?= $data['kategori'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label">Status Sistem</label>
                <select name="status" class="form-select">
                    <option value="Pending" <?= $data['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Diproses" <?= $data['status'] == 'Diproses' ? 'selected' : ''; ?>>Diproses</option>
                    <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="dashboard_admin.php" class="btn btn-back">
                <i class="fas fa-chevron-left me-2"></i> Kembali
            </a>
            <button type="submit" name="update" class="btn btn-update">
                Simpan Perubahan <i class=""></i>
            </button>
        </div>
    </form>
</div>

</body>
</html>