<?php
session_start(); 
// [Prosedur] Memulai session PHP agar bisa menyimpan data login antar halaman

include '../config/database.php'; 
// [Data] Menyertakan file koneksi database agar bisa digunakan untuk query SQL

// [Logika] Jika form login disubmit (tombol login ditekan)
if (isset($_POST['login'])) {
  $username = $_POST['username']; // [Data] Ambil input username dari form
  $password = $_POST['password']; // [Data] Ambil input password dari form

  // [Proses] Coba cari user di tabel admin terlebih dahulu
  $adminQ = $conn->query("SELECT * FROM user WHERE username='$username' AND password='$password'");
  // [Query SQL] Mencocokkan username dan password pada tabel user (admin)

  if ($adminQ->num_rows > 0) {
    // [Logika] Jika ditemukan data admin
    $admin = $adminQ->fetch_assoc(); // [Data] Ambil data admin yang cocok
    $_SESSION['login'] = true; // [Session] Tandai bahwa user sudah login
    $_SESSION['role'] = 'admin'; // [Session] Simpan role sebagai admin
    $_SESSION['nama'] = $admin['nama_admin']; // [Session] Simpan nama admin
    header("Location: ../admin/dashboard.php"); // [Prosedur] Redirect ke dashboard admin
    exit; // [Kontrol] Hentikan script setelah redirect
  }

  // [Proses] Jika bukan admin, cek di tabel pelanggan
  $pelangganQ = $conn->query("SELECT * FROM pelanggan WHERE username='$username' AND password='$password'");
  // [Query SQL] Cek kecocokan username dan password di tabel pelanggan

  if ($pelangganQ->num_rows > 0) {
    // [Logika] Jika ditemukan user pelanggan
    $user = $pelangganQ->fetch_assoc(); // [Data] Ambil data pelanggan
    $_SESSION['login'] = true; // [Session] Tandai login berhasil
    $_SESSION['role'] = 'pelanggan'; // [Session] Simpan role sebagai pelanggan
    $_SESSION['nama'] = $user['nama_pelanggan']; // [Session] Simpan nama pelanggan
    $_SESSION['id_pelanggan'] = $user['id_pelanggan']; // [Session] Simpan ID pelanggan
    header("Location: ../pelanggan/dashboard.php"); // [Prosedur] Redirect ke dashboard pelanggan
    exit;
  }

  // [Validasi] Jika tidak ditemukan di admin maupun pelanggan
  $error = "Login gagal. Periksa kembali username dan password."; 
  // [Feedback] Simpan pesan error untuk ditampilkan ke user
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
      background-image: url("../assets/img/login.jpg");
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
