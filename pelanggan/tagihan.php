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
</head>
<body>
  <div class="container py-5">
    <h2 class="text-center mb-4 text-success">Tagihan Listrik</h2>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
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
          <td><?= $row['bulan'] ?></td>
          <td><?= $row['tahun'] ?></td>
          <td><?= $row['jumlah_meter'] ?></td>
          <td><span class="badge bg-<?= $row['status'] == 'sudah dibayar' ? 'success' : 'warning' ?>"><?= $row['status'] ?></span></td>
          <td><?= number_format($row['total']) ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div class="text-center mt-4">
      <a href="dashboard.php" class="btn btn-outline-primary">⬅ Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
