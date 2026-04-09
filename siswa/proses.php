<?php
// Set timezone Indonesia WITA (Waktu Indonesia Tengah)
date_default_timezone_set('Asia/Makassar');

include '../config/koneksi.php';

if (!isset($conn)) {
    $conn = isset($koneksi) ? $koneksi : die("Error: Variabel koneksi database tidak ditemukan.");
}

// Set timezone untuk MySQL juga (WITA = GMT+8)
mysqli_query($conn, "SET time_zone = '+08:00'");

if (isset($_POST['submit'])) {
    
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi      = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal  = date('Y-m-d H:i:s'); // Sekarang akan menggunakan waktu Indonesia WITA (Waktu Indonesia Tengah)
    $status   = "Pending";

    
    $nama_baru = ""; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_name = $_FILES['foto']['name'];
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $ekstensi  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        
        // Nama file baru dengan timestamp Indonesia
        $nama_baru = date('YmdHis') . "_" . uniqid() . "." . $ekstensi;
        
        // Buat folder uploads jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        // Upload file
        move_uploaded_file($foto_tmp, 'uploads/' . $nama_baru);
    }

    // Simpan ke database
    $query = "INSERT INTO laporan (nama_pelapor, judul_laporan, isi_laporan, kategori, status, tanggal, foto) 
              VALUES ('$nama', '$judul', '$isi', '$kategori', '$status', '$tanggal', '$nama_baru')";
    
    $simpan = mysqli_query($conn, $query);

    if ($simpan) {
        echo "<script>alert('Laporan Berhasil Terkirim!'); window.location.href='dashboard.php';</script>";
    } else {
        $error_msg = mysqli_error($conn);
        echo "Error Database: " . $error_msg;
    }
}
?>
