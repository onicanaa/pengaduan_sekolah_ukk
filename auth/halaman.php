<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Aplikasi Pengaduan Sekolah</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        p {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .btn-login {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Halo!</h1>
        <p>Selamat datang di <strong>Aplikasi Pengaduan Sekolah</strong>. Suara Anda sangat berarti bagi kemajuan sekolah kita.</p>
        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">
        
        <p>Silakan masuk untuk melanjutkan pengaduan.</p>
        
        <?php
            // Variabel untuk menyimpan link halaman login
            $link_login = "login_.php";
            
            // Menampilkan tombol dengan link PHP
            echo "<a href='$link_login' class='btn-login'>Login Sekarang</a>";
        ?>
    </div>

</body>
</html>