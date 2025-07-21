<?php
session_start();
include 'config/database.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Coba cari di tabel admin dulu
  $adminQ = $conn->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
  if ($adminQ->num_rows > 0) {
    $admin = $adminQ->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['role'] = 'admin';
    $_SESSION['nama'] = $admin['nama_admin'];
    header("Location: admin/dashboard.php");
    exit;
  }

  // Kalau bukan admin, cek di pelanggan
  $pelangganQ = $conn->query("SELECT * FROM pelanggan WHERE username='$username' AND password='$password'");
  if ($pelangganQ->num_rows > 0) {
    $user = $pelangganQ->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['role'] = 'pelanggan';
    $_SESSION['nama'] = $user['nama_pelanggan'];
    $_SESSION['id_pelanggan'] = $user['id_pelanggan'];
    header("Location: pelanggan/dashboard.php");
    exit;
  }

  $error = "Login gagal. Periksa kembali username dan password.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Sistem</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-image: url("./assets/img/login.jpg");
      background-size: cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .login-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2 class="text-center mb-4">Masuk</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
      <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
    </form>
    <div class="text-center mt-3">
      <small>Belum punya akun? <a href="register.php">Daftar di sini</a></small>
    </div>
  </div>
</body>
</html>
