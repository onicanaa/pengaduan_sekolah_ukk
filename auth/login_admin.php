<?php
session_start();

$koneksi_file = __DIR__ . '/../config/koneksi.php';
if (file_exists($koneksi_file)) { include $koneksi_file; } 
elseif (file_exists('config/koneksi.php')) { include 'config/koneksi.php'; }
$db = isset($conn) ? $conn : (isset($koneksi) ? $koneksi : null);
$error_message = '';

if(isset($_POST['login'])){
    $u = mysqli_real_escape_string($db, $_POST['username']);
    $p = md5($_POST['password']); 
    $role = $_POST['role'];

    if($role == 'admin') {
        $q = mysqli_query($db, "SELECT * FROM admin WHERE username='$u' AND password='$p'");
        if(mysqli_num_rows($q) > 0){
            $data = mysqli_fetch_assoc($q);
            $_SESSION['admin'] = $data['username'];
            header("Location: ../siswa/simpan_pengaduan.php"); exit();
        } else { $error_message = "Username/Password Admin salah!"; }
    } else {
        $q = mysqli_query($db, "SELECT * FROM siswa WHERE nis = '$u' AND password = '$p'");
        if($q && mysqli_num_rows($q) > 0){
            $data = mysqli_fetch_assoc($q);
            $_SESSION['siswa'] = $data['nis'];
            $_SESSION['nama'] = $data['nama'];
            header("Location: ../siswa/dashboard.php"); exit();
        } else { $error_message = "Login Gagal! Cek NIS & Password."; }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-gradient: radial-gradient(circle at center, #0012ff 0%, #000033 70%, #000000 100%);
            --primary-blue: #0044ff;
            --input-bg: #f0f4ff;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
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
            font-size: 2.5rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-shadow: 0 5px 15px rgba(0,0,0,0.5);
        }

        .main-container {
            display: flex;
            width: 1000px;
            max-width: 100%;
            min-height: 550px;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        
        .side-image {
            flex: 1.3;
            
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

        .login-side {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .login-header-box {
            background: var(--primary-blue);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .login-header-box h2 {
            font-weight: 800;
            margin: 0;
            letter-spacing: 3px;
            font-size: 1.8rem;
        }

        .login-form-body {
            padding: 40px;
        }

        .label-custom {
            font-size: 0.8rem;
            font-weight: 700;
            color: #444;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            margin-bottom: 20px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #edf2ff;
            transition: 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--primary-blue);
        }

        .input-group-text {
            background: var(--input-bg);
            border: none;
            color: var(--primary-blue);
            width: 45px;
            justify-content: center;
        }

        .form-control, .form-select {
            background: white !important;
            border: none;
            padding: 12px;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: none;
        }

        .btn-login {
            background: var(--primary-blue);
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: 0.4s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(0, 68, 255, 0.3);
        }

        .btn-login:hover {
            background: #0033cc;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 68, 255, 0.4);
        }

        .footer-link {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #777;
        }

        .footer-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 700;
        }


        @media (max-width: 992px) {
            .main-container { width: 95%; flex-direction: column; }
            .side-image { display: none; }
            .welcome-header h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <div class="welcome-header">
        <h1>SELAMAT DATANG</h1>
    </div>

    <div class="main-container">
        <div class="side-image"></div>

        <div class="login-side">
            <div class="login-header-box">
                <h2>LOGIN</h2>
            </div>

            <div class="login-form-body">
                <?php if($error_message): ?>
                    <div class="alert alert-danger py-2 small text-center border-0 mb-4" style="border-radius:10px;">
                        <i class="fas fa-exclamation-circle me-1"></i> <?= $error_message ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-1">
                        <label class="label-custom">Masuk Sebagai</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users-cog"></i></span>
                            <select name="role" class="form-select" required>
                                <option value="siswa">Siswa</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="label-custom">Username / NIS</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan Username/NIS" required>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="label-custom">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn-login">
                        LOGIN SEKARANG <i class="fas fa-arrow-right ms-2"></i>
                    </button>

                    <div class="footer-link">
                        Belum punya akun? <a href="register.php">Daftar Akun Baru</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>