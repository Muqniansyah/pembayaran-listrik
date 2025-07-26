<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'pelanggan') {
  header("Location: ../auth/login.php");
  exit;
}

require '../../config/database.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil tagihan belum dibayar
function getTagihanBelumBayar($conn, $id_pelanggan) {
    $sql = "SELECT t.id_tagihan, t.bulan, t.tahun, t.jumlah_meter,
                   tr.tarifperkwh, (t.jumlah_meter * tr.tarifperkwh) AS total
            FROM tagihan t
            JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
            JOIN tarif tr ON tr.id_tarif = p.id_tarif
            WHERE t.id_pelanggan = ? AND t.status = 'belum dibayar'
            ORDER BY t.tahun DESC, t.bulan DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pelanggan);
    $stmt->execute();
    return $stmt->get_result();
}

$tagihanList = getTagihanBelumBayar($conn, $id_pelanggan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Tagihan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <style>
    body {
      background: #f5f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 25px;
    }
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
    .btn-bayar {
      font-size: 0.9rem;
    }
    .fixed-bottom-left {
      position: fixed;
      bottom: 20px;
      left: 20px;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h3 class="text-center text-primary mb-4">
    <i class="bi bi-receipt-cutoff me-2"></i>Daftar Tagihan Belum Dibayar
  </h3>

  <div class="card">
    <h5 class="mb-3">Riwayat Tagihan Anda</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>kWh</th>
            <th>Tarif/kWh</th>
            <th>Total (Rp)</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($tagihanList->num_rows === 0) {
              echo '<tr><td colspan="7" class="text-center text-muted">Tidak ada tagihan tertunda.</td></tr>';
          } else {
              $no = 1;
              while ($row = $tagihanList->fetch_assoc()):
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['bulan']) ?></td>
            <td><?= htmlspecialchars($row['tahun']) ?></td>
            <td><?= htmlspecialchars($row['jumlah_meter']) ?></td>
            <td><?= number_format($row['tarifperkwh'], 2, ',', '.') ?></td>
            <td><?= number_format($row['total'], 2, ',', '.') ?></td>
            <td>
              <a href="konfirmasi_bayar.php?id_tagihan=<?= $row['id_tagihan'] ?>" class="btn btn-sm btn-primary btn-bayar">
                <i class="bi bi-credit-card"></i> Bayar
              </a>
            </td>
          </tr>
          <?php endwhile; } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Tombol kembali -->
<a href="../dashboard.php" class="btn btn-outline-secondary fixed-bottom-left">
  <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
