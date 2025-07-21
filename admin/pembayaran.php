<?php
session_start();
include '../config/database.php';
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
  header("Location: ../login_admin.php");
  exit;
}

// Jika ada perubahan status
if (isset($_GET['verifikasi'])) {
  $id = (int) $_GET['verifikasi'];
  $conn->query("UPDATE pembayaran SET status_bayar='sudah diverifikasi' WHERE id_pembayaran = $id");

  // Sinkronkan status tagihan juga
  $tagihan = $conn->query("SELECT id_tagihan FROM pembayaran WHERE id_pembayaran = $id")->fetch_assoc();
  if ($tagihan) {
    $conn->query("UPDATE tagihan SET status='sudah dibayar' WHERE id_tagihan = " . $tagihan['id_tagihan']);
  }

  header("Location: pembayaran.php");
  exit;
}

// Ambil data pembayaran
$pembayaran = $conn->query("
  SELECT p.id_pembayaran, pel.nama_pelanggan, t.bulan, t.tahun, 
         p.total_bayar, p.bukti_bayar, p.status_bayar, p.tanggal_pembayaran
  FROM pembayaran p
  JOIN tagihan t ON t.id_tagihan = p.id_tagihan
  JOIN pelanggan pel ON pel.id_pelanggan = t.id_pelanggan
  ORDER BY p.tanggal_pembayaran DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pembayaran</title>
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
    .thumbnail {
      max-height: 80px;
      object-fit: contain;
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
    <h2 class="text-primary mb-4">💰 Data Pembayaran Pelanggan</h2>

    <div class="table-responsive">
      <table class="table table-bordered align-middle table-striped">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Periode</th>
            <th>Tanggal Bayar</th>
            <th>Total Bayar</th>
            <th>Bukti</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = $pembayaran->fetch_assoc()) {
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_pelanggan'] ?></td>
            <td><?= $row['bulan'] . '/' . $row['tahun'] ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_pembayaran'])) ?></td>
            <td>Rp <?= number_format($row['total_bayar'], 2, ',', '.') ?></td>
            <td>
              <?php if ($row['bukti_bayar']) { ?>
                <a href="../pelanggan/uploads/<?= $row['bukti_bayar'] ?>" target="_blank">
                  <img src="../pelanggan/uploads/<?= $row['bukti_bayar'] ?>" class="thumbnail border rounded">
                </a>
              <?php } else { echo '<span class="text-muted">-</span>'; } ?>
            </td>
            <td>
              <span class="badge bg-<?= $row['status_bayar'] == 'sudah diverifikasi' ? 'success' : 'warning' ?>">
                <?= $row['status_bayar'] ?>
              </span>
            </td>
            <td>
              <?php if ($row['status_bayar'] == 'menunggu verifikasi') { ?>
                <a href="?verifikasi=<?= $row['id_pembayaran'] ?>" class="btn btn-sm btn-success"
                  onclick="return confirm('Yakin verifikasi pembayaran ini?')">✔ Verifikasi</a>
              <?php } else { echo '<span class="text-muted small">Sudah diverifikasi</span>'; } ?>
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
