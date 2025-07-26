<?php
session_start(); 
// [Prosedur] Memulai session PHP agar bisa menyimpan data login antar halaman

include '../config/database.php'; 
// [Data] Menyertakan file koneksi database agar bisa digunakan untuk query SQL

// [Logika] Jika form login disubmit (tombol login ditekan)
if (isset($_POST['login'])) {
  $username = $_POST['username']; // [Data] Ambil input username dari form
  $password = $_POST['password']; // [Data] Ambil input password dari form

  // [Proses] Coba cari user di tabel admin terlebih dahulu berdasarkan username dan gabungkan dengan level
  $userQ = $conn->query("SELECT u.*, l.nama_level FROM user u 
                         LEFT JOIN level l ON u.id_level = l.id_level 
                         WHERE u.username='$username'");
  // [Query SQL] LEFT JOIN untuk mendapatkan level user dari tabel level berdasarkan id_level

  if ($userQ->num_rows > 0) {
    // [Logika] Jika ditemukan data admin
    $user = $userQ->fetch_assoc(); // [Data] Ambil data user yang cocok

    if ($user['password'] === $password) {
      // [Validasi] Jika password cocok
      $_SESSION['login'] = true; // [Session] Tandai bahwa user sudah login
      $_SESSION['level'] = $user['nama_level']; // [Session] Simpan level user dari tabel level
      $_SESSION['nama'] = $user['nama_admin']; // [Session] Simpan nama admin
      $_SESSION['id_user'] = $user['id_user']; // [Session] Simpan ID user

      // [Prosedur] Redirect ke dashboard admin jika level adalah admin
      if ($_SESSION['level'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit; // [Kontrol] Hentikan script setelah redirect
      } else {
        header("Location: ../index.php"); // [Fallback] Jika level tidak dikenali
        exit;
      }
    } else {
      $error = "Password salah."; 
      // [Feedback] Jika password tidak cocok
    }
  } else {
    // [Proses] Jika bukan admin, cek username di tabel pelanggan (join dengan level juga)
    $pelangganQ = $conn->query("SELECT p.*, l.nama_level FROM pelanggan p
                                LEFT JOIN level l ON p.id_level = l.id_level
                                WHERE p.username='$username'");
    // [Query SQL] Join dengan level untuk mendapatkan nama_level pelanggan

    if ($pelangganQ->num_rows > 0) {
      // [Logika] Jika ditemukan user pelanggan
      $user = $pelangganQ->fetch_assoc(); // [Data] Ambil data pelanggan
      if ($user['password'] === $password) {
        // [Validasi] Jika password cocok
        $_SESSION['login'] = true; // [Session] Tandai login berhasil
        $_SESSION['level'] = $user['nama_level']; // [Session] Simpan level sebagai pelanggan
        $_SESSION['nama'] = $user['nama_pelanggan']; // [Session] Simpan nama pelanggan
        $_SESSION['id_pelanggan'] = $user['id_pelanggan']; // [Session] Simpan ID pelanggan

        // [Prosedur] Redirect ke dashboard pelanggan jika level = pelanggan
        if ($_SESSION['level'] === 'pelanggan') {
          header("Location: ../pelanggan/dashboard.php");
          exit; // [Kontrol] Hentikan script setelah redirect
        } else {
          header("Location: ../index.php"); // [Fallback] Jika level tidak dikenali
          exit;
        }
      } else {
        $error = "Password salah."; 
        // [Feedback] Jika password tidak cocok
      }
    } else {
      $error = "Akun tidak ditemukan."; 
      // [Feedback] Jika username tidak ada di kedua tabel
    }
  }
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
