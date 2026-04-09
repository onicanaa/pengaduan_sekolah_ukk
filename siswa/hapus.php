<?php

if (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php';
} else {
    die("Error: File koneksi.php tidak ditemukan.");
}


if (!isset($conn)) $conn = $koneksi;


if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $delete = mysqli_query($conn, "DELETE FROM laporan WHERE id = '$id'");

    if ($delete) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location='simpan_pengaduan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>