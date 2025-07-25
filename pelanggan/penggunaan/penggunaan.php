<?php
session_start();
include '../../config/database.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

// Inisialisasi variabel edit
$edit = false;

// Fungsi hapus data penggunaan dan tagihan terkait
function hapusPenggunaan($conn, $id, $id_pelanggan) {
  $conn->query("DELETE FROM tagihan WHERE id_penggunaan='$id'");
  $conn->query("DELETE FROM penggunaan WHERE id_penggunaan='$id' AND id_pelanggan='$id_pelanggan'");
}

// Fungsi update data penggunaan dan tagihan
function updatePenggunaan($conn, $id, $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir) {
  $conn->query("UPDATE penggunaan SET bulan='$bulan', tahun='$tahun', meter_awal='$meter_awal', meter_akhir='$meter_akhir' WHERE id_penggunaan='$id' AND id_pelanggan='$id_pelanggan'");
  $jumlah_meter = $meter_akhir - $meter_awal;
  $conn->query("UPDATE tagihan SET jumlah_meter='$jumlah_meter' WHERE id_penggunaan='$id'");
}

// Fungsi tambah data penggunaan dan tagihan
function tambahPenggunaan($conn, $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir) {
  $conn->query("INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_akhir) VALUES ('$id_pelanggan', '$bulan', '$tahun', '$meter_awal', '$meter_akhir')");
  $id_penggunaan = $conn->insert_id;
  $jumlah_meter = $meter_akhir - $meter_awal;
  $conn->query("INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status) VALUES ('$id_penggunaan', '$id_pelanggan', '$bulan', '$tahun', '$jumlah_meter', 'belum dibayar')");
}

// Proses hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  hapusPenggunaan($conn, $id, $id_pelanggan);
  header("Location: penggunaan.php");
  exit;
}

// Proses update
if (isset($_POST['update'])) {
  $id = $_POST['id_penggunaan'];
  updatePenggunaan($conn, $id, $id_pelanggan, $_POST['bulan'], $_POST['tahun'], $_POST['meter_awal'], $_POST['meter_akhir']);
  header("Location: penggunaan.php");
  exit;
}

// Proses tambah
if (isset($_POST['tambah'])) {
  tambahPenggunaan($conn, $id_pelanggan, $_POST['bulan'], $_POST['tahun'], $_POST['meter_awal'], $_POST['meter_akhir']);
  header("Location: penggunaan.php");
  exit;
}

// Ambil data penggunaan
$penggunaan = $conn->query("SELECT * FROM penggunaan WHERE id_pelanggan='$id_pelanggan'");

// Cek jika edit
if (isset($_GET['edit'])) {
  $edit = true;
  $id_edit = $_GET['edit'];
  $data_edit = $conn->query("SELECT * FROM penggunaan WHERE id_penggunaan='$id_edit' AND id_pelanggan='$id_pelanggan'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Penggunaan Listrik</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: linear-gradient(135deg, #e6f0ff, #f8f9fa);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      padding-bottom: 70px; /* space untuk tombol bawah */
    }

    .btn-action {
      margin-right: 5px;
    }

    .fixed-back {
      position: fixed;
      bottom: 20px;
      left: 20px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="mb-4 text-center">
      <h2 class="text-primary"><i class="bi bi-lightning-charge-fill"></i> Penggunaan Listrik</h2>
    </div>

    <form method="post" class="mb-5">
      <?php if ($edit): ?>
        <input type="hidden" name="id_penggunaan" value="<?= $data_edit['id_penggunaan'] ?>">
      <?php endif; ?>
      <div class="row mb-3">
        <div class="col-md-3">
          <input type="text" name="bulan" placeholder="Bulan" class="form-control" value="<?= $edit ? $data_edit['bulan'] : '' ?>" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="tahun" placeholder="Tahun" class="form-control" value="<?= $edit ? $data_edit['tahun'] : '' ?>" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="meter_awal" placeholder="Meter Awal" class="form-control" value="<?= $edit ? $data_edit['meter_awal'] : '' ?>" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="meter_akhir" placeholder="Meter Akhir" class="form-control" value="<?= $edit ? $data_edit['meter_akhir'] : '' ?>" required>
        </div>
      </div>
      <button type="submit" name="<?= $edit ? 'update' : 'tambah' ?>" class="btn btn-<?= $edit ? 'warning' : 'success' ?> w-100">
        <i class="bi <?= $edit ? 'bi-pencil-square' : 'bi-save' ?>"></i>
        <?= $edit ? 'Update Data' : 'Simpan Penggunaan' ?>
      </button>
    </form>

    <h4 class="mb-3 text-secondary">Riwayat Penggunaan</h4>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>Bulan</th>
          <th>Tahun</th>
          <th>Meter Awal</th>
          <th>Meter Akhir</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $penggunaan->fetch_assoc()): ?>
          <tr>
            <td><?= $row['bulan'] ?></td>
            <td><?= $row['tahun'] ?></td>
            <td><?= $row['meter_awal'] ?></td>
            <td><?= $row['meter_akhir'] ?></td>
            <td>
              <a href="penggunaan.php?edit=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-warning btn-action"><i class="bi bi-pencil-square"></i></a>
              <a href="penggunaan.php?hapus=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus data ini?')">
                <i class="bi bi-trash3-fill"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Tombol kembali ke dashboard di kiri bawah -->
  <div class="fixed-back">
    <a href="../dashboard.php" class="btn btn-outline-primary">
      <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
    </a>
  </div>
</body>
</html>
