<?php
session_start();
include '../config/database.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

$tagihan = $conn->query("
  SELECT t.bulan, t.tahun, t.jumlah_meter, t.status, (t.jumlah_meter * tr.tarifperkwh) AS total
  FROM tagihan t
  JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
  JOIN tarif tr ON p.id_tarif = tr.id_tarif
  WHERE t.id_pelanggan = '$id_pelanggan'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tagihan Listrik</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #f1f5f9, #e2f0ff);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 70px;
    }
    .fixed-back {
      position: fixed;
      bottom: 20px;
      left: 20px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="text-center mb-4">
      <h2 class="text-success"><i class="bi bi-receipt-cutoff"></i> Tagihan Listrik Anda</h2>
      <p class="text-muted">Berikut ini adalah daftar tagihan listrik berdasarkan penggunaan Anda.</p>
    </div>

    <table class="table table-bordered table-striped shadow-sm bg-white">
      <thead class="table-light text-center">
        <tr>
          <th><i class="bi bi-calendar3"></i> Bulan</th>
          <th><i class="bi bi-calendar-event"></i> Tahun</th>
          <th><i class="bi bi-lightning-fill"></i> Jumlah Meter</th>
          <th><i class="bi bi-info-circle"></i> Status</th>
          <th><i class="bi bi-cash-coin"></i> Total Tagihan (Rp)</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $tagihan->fetch_assoc()) { ?>
        <tr class="text-center">
          <td><?= htmlspecialchars($row['bulan']) ?></td>
          <td><?= htmlspecialchars($row['tahun']) ?></td>
          <td><?= number_format($row['jumlah_meter']) ?> kWh</td>
          <td>
            <span class="badge bg-<?= $row['status'] == 'sudah dibayar' ? 'success' : 'danger' ?>">
              <?= ucwords($row['status']) ?>
            </span>
          </td>
          <td class="text-end">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- Tombol kembali ke dashboard di kiri bawah -->
  <div class="fixed-back">
    <a href="dashboard.php" class="btn btn-outline-primary">
      <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
    </a>
  </div>
</body>
</html>
