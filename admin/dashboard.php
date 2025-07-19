<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

// Ambil data ringkasan
$total_pelanggan = $conn->query("SELECT COUNT(*) AS total FROM pelanggan")->fetch_assoc()['total'];
$total_tagihan = $conn->query("SELECT COUNT(*) AS total FROM tagihan")->fetch_assoc()['total'];
$belum_bayar = $conn->query("SELECT COUNT(*) AS total FROM tagihan WHERE status='belum dibayar'")->fetch_assoc()['total'];
$penggunaan_meter = $conn->query("SELECT SUM(jumlah_meter) AS total FROM tagihan")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0f2fe, #f0f9ff);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .card:hover {
      transform: scale(1.02);
    }
    .nav-link {
      font-weight: 500;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="text-primary">Halo Admin, <?= $_SESSION['nama'] ?> 👋</h2>
    <p class="text-muted">Selamat datang di Dashboard Aplikasi Pembayaran Listrik</p>
  </div>

  <div class="row text-center mb-5">
    <div class="col-md-3 mb-3">
      <div class="card p-4 bg-white">
        <h6 class="text-muted">Total Pelanggan</h6>
        <h3 class="text-primary fw-bold"><?= $total_pelanggan ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-4 bg-white">
        <h6 class="text-muted">Total Tagihan</h6>
        <h3 class="text-dark fw-bold"><?= $total_tagihan ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-4 bg-white">
        <h6 class="text-muted">Belum Dibayar</h6>
        <h3 class="text-danger fw-bold"><?= $belum_bayar ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card p-4 bg-white">
        <h6 class="text-muted">Total Pemakaian (kWh)</h6>
        <h3 class="text-success fw-bold"><?= $penggunaan_meter ?? 0 ?></h3>
      </div>
    </div>
  </div>

  <div class="row text-center">
    <div class="col-md-4 mb-3">
      <a href="pelanggan.php" class="btn btn-outline-primary w-100">👤 Data Pelanggan</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="tagihan.php" class="btn btn-outline-success w-100">📄 Data Tagihan</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="tarif.php" class="btn btn-outline-warning w-100">⚙️ Kelola Tarif</a>
    </div>
  </div>
  <div class="text-center mt-4">
    <a href="../logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>
