<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

$tagihan = $conn->query("SELECT t.*, p.nama_pelanggan, tr.tarifperkwh, (t.jumlah_meter * tr.tarifperkwh) AS total
                          FROM tagihan t
                          JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                          JOIN tarif tr ON p.id_tarif = tr.id_tarif
                          ORDER BY t.tahun DESC, t.bulan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Tagihan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8fafc;
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
    <h2 class="text-center mb-4 text-success">📄 Data Tagihan Pelanggan</h2>

    <div class="table-responsive table-container">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Nama Pelanggan</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Jumlah Meter</th>
            <th>Status</th>
            <th>Total Tagihan (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $tagihan->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['nama_pelanggan'] ?></td>
            <td><?= $row['bulan'] ?></td>
            <td><?= $row['tahun'] ?></td>
            <td><?= $row['jumlah_meter'] ?></td>
            <td><span class="badge bg-<?= $row['status'] == 'sudah dibayar' ? 'success' : 'warning' ?>"><?= $row['status'] ?></span></td>
            <td>Rp <?= number_format($row['total']) ?></td>
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
