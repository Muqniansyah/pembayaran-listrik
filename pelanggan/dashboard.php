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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #e0f2fe, #f8fafc);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      position: relative;
      padding-bottom: 70px; /* untuk ruang logout */
    }

    .card {
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: .3s ease;
      cursor: pointer;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }

    a.card-link {
      text-decoration: none;
      color: inherit;
    }

    .card h5 {
      margin-top: 10px;
      font-weight: 600;
    }

    .logout-fixed {
      position: fixed;
      bottom: 20px;
      left: 20px;
    }

    .logout-btn {
      padding: 10px 20px;
      background-color: #ffecec;
      color: #dc3545;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 500;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .logout-btn:hover {
      background-color: #f8d7da;
      color: #a71d2a;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="text-center mb-4">
      <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?></h2>
      <p class="text-muted">Menu Aplikasi Pembayaran Listrik Pascabayar</p>
    </div>

    <div class="row g-4 justify-content-center">

      <!-- Input Penggunaan -->
      <div class="col-6 col-md-3">
        <a href="./penggunaan/penggunaan.php" class="card-link">
          <div class="card p-4 text-center border-0" style="background:#eef6ff;">
            <i class="bi bi-pencil-square text-primary fs-1"></i>
            <h5 class="text-primary">Input Penggunaan</h5>
          </div>
        </a>
      </div>

      <!-- Lihat Tagihan -->
      <div class="col-6 col-md-3">
        <a href="./tagihan/tagihan.php" class="card-link">
          <div class="card p-4 text-center border-0" style="background:#e8f9f1;">
            <i class="bi bi-receipt text-success fs-1"></i>
            <h5 class="text-success">Lihat Tagihan</h5>
          </div>
        </a>
      </div>

      <!-- Pembayaran -->
      <div class="col-6 col-md-3">
        <a href="./pembayaran/pembayaran.php" class="card-link">
          <div class="card p-4 text-center border-0" style="background:#fff4e6;">
            <i class="bi bi-credit-card text-warning fs-1"></i>
            <h5 class="text-warning">Pembayaran</h5>
          </div>
        </a>
      </div>

    </div>
  </div>

  <!-- Logout tombol di kiri bawah -->
  <div class="logout-fixed">
    <a href="../auth/logout.php" class="logout-btn">
      <i class="bi bi-box-arrow-left me-2"></i>Logout
    </a>
  </div>
</body>
</html>
