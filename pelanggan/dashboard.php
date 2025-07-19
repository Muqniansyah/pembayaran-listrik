<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'pelanggan') {
  header("Location: ../login_pelanggan.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pelanggan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #e0f2fe, #f8fafc);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .card:hover {
      transform: translateY(-3px);
      transition: 0.3s ease;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="text-center mb-4">
      <h2>Selamat Datang, <?= $_SESSION['nama'] ?></h2>
      <p class="text-muted">Menu Aplikasi Pembayaran Listrik Pascabayar</p>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-4 mb-3">
        <a href="penggunaan.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4 class="text-primary">Input Penggunaan</h4>
          </div>
        </a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="tagihan.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4 class="text-success">Lihat Tagihan</h4>
          </div>
        </a>
      </div>
      <div class="col-md-4 mb-3">
        <a href="../logout.php" class="text-decoration-none">
          <div class="card p-4 text-center">
            <h4 class="text-danger">Logout</h4>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
