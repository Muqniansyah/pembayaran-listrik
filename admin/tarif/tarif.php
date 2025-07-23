<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

// Tambah tarif
if (isset($_POST['tambah'])) {
  $daya = $_POST['daya'];
  $tarif = $_POST['tarif'];
  $conn->query("INSERT INTO tarif (daya, tarifperkwh) VALUES ('$daya', '$tarif')");
  header("Location: tarif.php");
  exit;
}

// Hapus tarif
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM tarif WHERE id_tarif = '$id'");
  header("Location: tarif.php");
  exit;
}

$tarif = $conn->query("SELECT * FROM tarif ORDER BY daya ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Tarif</title>
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
    <a href="../dashboard.php">🏠 Dashboard</a>
    <a href="../pelanggan/pelanggan.php">👤 Data Pelanggan</a>
    <a href="../tagihan/tagihan.php">📄 Data Tagihan</a>
    <a href="./tarif.php">⚙️ Kelola Tarif</a>
    <a href="../pembayaran/pembayaran.php">💰 Data Pembayaran</a>
    <a href="../../auth/logout.php" class="btn btn-danger mt-4">🚪 Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content w-100">
    <h2 class="text-warning mb-4">⚙️ Kelola Tarif Listrik</h2>

    <div class="mb-4">
      <form method="post" class="row g-3">
        <div class="col-md-6">
          <input type="number" name="daya" placeholder="Daya (VA)" class="form-control" required>
        </div>
        <div class="col-md-6">
          <input type="number" name="tarif" placeholder="Tarif per kWh" class="form-control" required>
        </div>
        <div class="col-12">
          <button type="submit" name="tambah" class="btn btn-warning w-100">+ Tambah Tarif</button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Daya (VA)</th>
            <th>Tarif per kWh</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $tarif->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['daya'] ?> VA</td>
            <td>Rp <?= number_format($row['tarifperkwh']) ?></td>
            <td>
              <a href="?hapus=<?= $row['id_tarif'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
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
