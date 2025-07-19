<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

$pelanggan = $conn->query("SELECT p.*, t.daya, t.tarifperkwh FROM pelanggan p JOIN tarif t ON p.id_tarif = t.id_tarif");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f9ff;
    }
    .table-container {
      margin-top: 40px;
    }
    .btn-back {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="text-center mb-4 text-primary">📋 Data Pelanggan</h2>

    <div class="table-responsive table-container">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Nomor KWH</th>
            <th>Alamat</th>
            <th>Daya</th>
            <th>Tarif / kWh</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $pelanggan->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['nama_pelanggan'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['nomor_kwh'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['daya'] ?> VA</td>
            <td>Rp <?= number_format($row['tarifperkwh']) ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="text-center btn-back">
      <a href="dashboard.php" class="btn btn-outline-primary">⬅ Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
