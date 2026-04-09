<?php
include '../config/koneksi.php';


if (!isset($conn)) {
    $conn = isset($koneksi) ? $koneksi : die("Koneksi gagal.");
}

echo '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { 
            font-family: "Inter", sans-serif; 
            background-color: #000000; /* Background dasar hitam agar tidak flash putih */
            margin: 0;
        }
    
        .swal2-title, .swal2-html-container {
            color: #ffffff !important;
        }
    </style>
</head>
<body>';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $query = mysqli_query($conn, "UPDATE laporan SET status='$status' WHERE id='$id'");

    if ($query) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Status laporan berhasil diperbarui menjadi $status.',
                icon: 'success',
                background: '#000000',      
                color: '#ffffff',           
                confirmButtonColor: '#6366f1', 
                confirmButtonText: 'OK',
                iconColor: '#22c55e',       
                backdrop: `rgba(0,0,0,0.9)` 
            }).then(() => {
                window.location.href = 'simpan_pengaduan.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat update data.',
                icon: 'error',
                background: '#000000',
                color: '#ffffff',
                confirmButtonColor: '#ef4444'
            }).then(() => {
                window.location.href = 'simpan_pengaduan.php';
            });
        </script>";
    }
} else {
    
    header("Location: simpan_pengaduan.php");
}

echo '</body></html>';
?>