<?php
session_start();
include '../config/koneksi.php';
$data = mysqli_query($conn,"
SELECT pengaduan.*, siswa.nama 
FROM pengaduan 
JOIN siswa ON pengaduan.siswa_id = siswa.id
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>
<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h3>Dashboard Admin</h3>
<table class="table table-striped">
<tr>
<th>Nama</th>
<th>Judul</th>
<th>Isi</th>
<th>Status</th>
<th>Aksi</th>
</tr>
<?php while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $d['nama']; ?></td>
<td><?= $d['judul']; ?></td>
<td><?= $d['isi']; ?></td>
<td><?= $d['status']; ?></td>
<td>
<a href="update_status.php?id=<?= $d['id']; ?>&s=Selesai" class="btn btn-success btn-sm">Selesai</a>
</td>
</tr>
<?php } ?>
</table>
</body>
</html>
