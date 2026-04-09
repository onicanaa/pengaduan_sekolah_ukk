<?php
session_start();

$koneksi_file = __DIR__ . '/../config/koneksi.php';

if (file_exists($koneksi_file)) {
    include $koneksi_file;
} elseif (file_exists('config/koneksi.php')) {
    include 'config/koneksi.php';
} else {
    die("Fatal Error: File koneksi tidak ditemukan.");
}

$db = isset($conn) ? $conn : (isset($koneksi) ? $koneksi : null);

$message = '';
$message_type = '';

if(isset($_POST['register'])){
    $nis  = mysqli_real_escape_string($db, $_POST['nis']);
    $nama = mysqli_real_escape_string($db, $_POST['nama']);
    $pass = md5($_POST['password']); 

    $cek_user = mysqli_query($db, "SELECT * FROM siswa WHERE nis = '$nis'");
    
    if(mysqli_num_rows($cek_user) > 0){
        $message = "NIS sudah terdaftar!";
        $message_type = "danger";
    } else {
        $insert = mysqli_query($db, "INSERT INTO siswa (nis, nama, password) VALUES ('$nis', '$nama', '$pass')");
        if($insert){
            $message = "Akun berhasil dibuat! Silahkan login.";
            $message_type = "success";
        } else {
            $message = "Terjadi kesalahan. Coba lagi.";
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-gradient: radial-gradient(circle at center, #0004ff 0%, #000033 70%, #000000 100%);
            --primary-blue: #0004ff;
            --input-bg: #eef2ff;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .welcome-header {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-header h1 {
            font-weight: 900;
            font-size: 2.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        
        .main-container {
            display: flex;
            width: 1000px;
            max-width: 95%;
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
        }

        
        .side-image {
            flex: 1.2;
            background-image: url('https://file.data.kemendikdasmen.go.id/sekolahkita/50/5010/50103880-14.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .side-image::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
        }

        
        .register-side {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .register-header-box {
            background: var(--primary-blue);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .register-header-box h2 {
            font-weight: 800;
            margin: 0;
            letter-spacing: 2px;
            font-size: 1.5rem;
        }

        .register-form-body {
            padding: 30px 35px;
        }

        .label-custom {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
            display: block;
        }

        .input-group {
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #d1d9ff;
        }

        .input-group-text {
            background: var(--input-bg);
            border: none;
            color: var(--primary-blue);
            width: 45px;
            justify-content: center;
        }

        .form-control {
            background: var(--input-bg) !important;
            border: none;
            padding: 10px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            background: #fff !important;
        }

        .btn-register {
            background: var(--primary-blue);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background: #0003cc;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 4, 255, 0.3);
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
        }

        .footer-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; width: 90%; }
            .side-image { display: none; }
            .welcome-header h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <div class="welcome-header">
        <h1>DAFTAR AKUN</h1>
    </div>

    <div class="main-container">
        <div class="side-image">
             <div style="position: absolute; bottom: 30px; left: 30px; color: white; z-index: 2;">
                <h3 style="font-weight: 800; text-shadow: 2px 2px 5px rgba(0,0,0,0.5);">
            </div>
        </div>

        <div class="register-side">
            <div class="register-header-box">
                <h2>REGISTRASI</h2>
            </div>

            <div class="register-form-body">
                <?php if($message): ?>
                    <div class="alert alert-<?= $message_type ?> py-2 small text-center"><?= $message ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-1">
                        <label class="label-custom">NIS (Nomor Induk Siswa)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nis" class="form-control" placeholder="" required>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="label-custom">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Anda" required>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="label-custom">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" name="register" class="btn-register">DAFTAR SEKARANG</button>

                    <div class="footer-link">
                        Sudah punya akun? <a href="login_admin.php">Login di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>