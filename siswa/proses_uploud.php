<?php
include '../config/koneksi.php';

if(isset($_POST['submit'])) {

    $nama      = $_POST['nama_pelapor'];
    $judul     = $_POST['judul_laporan'];
    $isi       = $_POST['isi_laporan'];
    $kategori  = $_POST['kategori'];
    $tanggal   = date('Y-m-d');

    $namaFoto = "";

    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){

        $folder = "uploads/";

        
        $fileName = $_FILES['foto']['name'];
        $tmpName  = $_FILES['foto']['tmp_name'];
        $size     = $_FILES['foto']['size'];

        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    
        $allowed = ['jpg','jpeg','png','webp'];

        if(in_array($ext, $allowed)){

        
            $namaFoto = time().'_'.rand(1000,9999).'.'.$ext;

            move_uploaded_file($tmpName, $folder.$namaFoto);

        }
    }

  
    $query = mysqli_query($conn,"
        INSERT INTO laporan 
        (nama_pelapor, judul_laporan, isi_laporan, kategori, tanggal, foto, status)
        VALUES
        ('$nama','$judul','$isi','$kategori','$tanggal','$namaFoto','Pending')
    ");

    if($query){
        header("Location: simpan_pengaduan.php");
    } else {
        echo "Gagal simpan data";
    }

}
?>
