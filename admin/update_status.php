<?php
include '../config/koneksi.php';
$id = $_GET['id'];
$s  = $_GET['s'];
mysqli_query($conn,"UPDATE pengaduan SET status='$s' WHERE id='$id'");
header("Location: dashboard.php");
