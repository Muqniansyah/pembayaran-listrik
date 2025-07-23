<?php
session_start();
include '../../config/database.php';

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
    .action-btn {
      display: flex;
      gap: 5px;
    }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar position-fixed">
    <h4>⚡ Admin Panel</h4>
    <hr style="border-color:white;">
    <a href="../dashboard.php">🏠 Dashboard</a>
    <a href="./pelanggan.php">👤 Data Pelanggan</a>
    <a href="../tagihan/tagihan.php">📄 Data Tagihan</a>
    <a href="../tarif/tarif.php">⚙️ Kelola Tarif</a>
    <a href="../pembayaran/pembayaran.php">💰 Data Pembayaran</a>
    <a href="../../auth/logout.php" class="btn btn-danger mt-4">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content w-100">
    <h2 class="text-primary mb-4">📋 Data Pelanggan</h2>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Nomor KWH</th>
            <th>Alamat</th>
            <th>Daya</th>
            <th>Tarif / kWh</th>
            <th>Aksi</th>
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
            <td class="action-btn">
              <a href="edit_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="hapus_pelanggan.php?id=<?= $row['id_pelanggan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
