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
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar position-fixed">
    <h4>⚡ Admin Panel</h4>
    <hr style="border-color:white;">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="pelanggan.php">👤 Data Pelanggan</a>
    <a href="tagihan.php">📄 Data Tagihan</a>
    <a href="tarif.php">⚙️ Kelola Tarif</a>
    <a href="pembayaran.php">💰 Data Pembayaran</a>
    <a href="../logout.php" class="btn btn-danger mt-4">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content w-100">
    <h2 class="text-success mb-4">📄 Data Tagihan Pelanggan</h2>

    <div class="table-responsive">
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
            <td>
              <span class="badge bg-<?= $row['status'] == 'sudah dibayar' ? 'success' : ($row['status'] == 'belum dibayar' ? 'warning' : 'secondary') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td>Rp <?= number_format($row['total']) ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
