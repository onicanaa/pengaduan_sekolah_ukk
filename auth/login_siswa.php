<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['login'])){
    $nis = $_POST['nis'];
    $p   = md5($_POST['password']);

    $q = mysqli_query($conn,"SELECT * FROM siswa WHERE nis='$nis' AND password='$p'");
    $data = mysqli_fetch_assoc($q);

    if($data){
        $_SESSION['siswa'] = $data;
        header("Location: ../siswa/dashboard.php");
    } else {
        echo "Login gagal";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login Siswa</title>
<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h3>Login Siswa</h3>
<form method="post">
<input class="form-control mb-2" name="nis" placeholder="NIS">
<input type="password" class="form-control mb-2" name="password" placeholder="Password">
<button class="btn btn-success" name="login">Login</button>
</form>
</body>
</html>
