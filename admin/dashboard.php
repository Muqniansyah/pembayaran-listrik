<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

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
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background-color: #0d6efd;
      padding: 20px;
      color: white;
    }
    .sidebar a {
      color: white;
      display: block;
      margin: 15px 0;
      text-decoration: none;
    }
    .sidebar a:hover {
      text-decoration: underline;
    }
    .main-content {
      margin-left: 220px;
      padding: 30px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar position-fixed">
    <h4>⚡ Admin Panel</h4>
    <hr style="border-color:white;">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="./pelanggan/pelanggan.php">👤 Data Pelanggan</a>
    <a href="./tagihan/tagihan.php">📄 Data Tagihan</a>
    <a href="./tarif/tarif.php">⚙️ Kelola Tarif</a>
    <a href="./pembayaran/pembayaran.php">💰 Data Pembayaran</a>
    <a href="../auth/logout.php" class="btn btn-danger mt-4">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content w-100">
    <h2 class="mb-4">Dashboard Pembayaran Listrik</h2>

    <div class="row text-center">
      <div class="col-md-3 mb-3">
        <div class="card p-4">
          <h6 class="text-muted">Total Pelanggan</h6>
          <h3 class="text-primary fw-bold"><?= $total_pelanggan ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-4">
          <h6 class="text-muted">Total Tagihan</h6>
          <h3 class="text-dark fw-bold"><?= $total_tagihan ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-4">
          <h6 class="text-muted">Belum Dibayar</h6>
          <h3 class="text-danger fw-bold"><?= $belum_bayar ?></h3>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card p-4">
          <h6 class="text-muted">Total Pemakaian (kWh)</h6>
          <h3 class="text-success fw-bold"><?= $penggunaan_meter ?? 0 ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
