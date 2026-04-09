<?php

if (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php'; 
} else {
    die("Error: File koneksi.php tidak ditemukan.");
}

if (!isset($conn)) {
    $conn = isset($koneksi) ? $koneksi : die("Error: Variabel koneksi tidak ditemukan.");
}


header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pengaduan_".date('Y-m-d').".xls");

?>

<center>
    <h3>DATA LAPORAN PENGADUAN ONLINE</h3>
</center>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>ID Laporan</th>
            <th>Nama Pelapor</th>
            <th>Tanggal</th>
            <th>Klasifikasi</th>
            <th>Judul Laporan</th>
            <th>Isi Laporan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $sql = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id DESC");
        while ($data = mysqli_fetch_array($sql)) {
            $status = $data['status'] ?: 'Pending';
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['id'] ?></td>
            <td><?= htmlspecialchars($data['nama_pelapor']) ?></td>
            <td><?= date('d-m-Y', strtotime($data['tanggal'])) ?></td>
            <td><?= $data['kategori'] ?? 'Lainnya' ?></td>
            <td><?= htmlspecialchars($data['judul_laporan']) ?></td>
            <td><?= htmlspecialchars($data['isi_laporan']) ?></td>
            <td><?= strtoupper($status) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>