<?php
session_start();
include '../config/database.php';
$id_pelanggan = $_SESSION['id_pelanggan'];

// Hapus data
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM penggunaan WHERE id_penggunaan='$id' AND id_pelanggan='$id_pelanggan'");
  $conn->query("DELETE FROM tagihan WHERE id_penggunaan='$id'");
  header("Location: penggunaan.php");
  exit;
}

// Edit data
if (isset($_POST['update'])) {
  $id = $_POST['id_penggunaan'];
  $bulan = $_POST['bulan'];
  $tahun = $_POST['tahun'];
  $meter_awal = $_POST['meter_awal'];
  $meter_akhir = $_POST['meter_akhir'];

  $conn->query("UPDATE penggunaan SET bulan='$bulan', tahun='$tahun', meter_awal='$meter_awal', meter_akhir='$meter_akhir' WHERE id_penggunaan='$id' AND id_pelanggan='$id_pelanggan'");
  $jumlah_meter = $meter_akhir - $meter_awal;
  $conn->query("UPDATE tagihan SET jumlah_meter='$jumlah_meter' WHERE id_penggunaan='$id'");

  header("Location: penggunaan.php");
  exit;
}

// Tambah data penggunaan
if (isset($_POST['tambah'])) {
  $bulan = $_POST['bulan'];
  $tahun = $_POST['tahun'];
  $meter_awal = $_POST['meter_awal'];
  $meter_akhir = $_POST['meter_akhir'];

  $conn->query("INSERT INTO penggunaan (id_pelanggan, bulan, tahun, meter_awal, meter_akhir) VALUES ('$id_pelanggan', '$bulan', '$tahun', '$meter_awal', '$meter_akhir')");

  $id_penggunaan = $conn->insert_id;
  $jumlah_meter = $meter_akhir - $meter_awal;
  $conn->query("INSERT INTO tagihan (id_penggunaan, id_pelanggan, bulan, tahun, jumlah_meter, status) VALUES ('$id_penggunaan', '$id_pelanggan', '$bulan', '$tahun', '$jumlah_meter', 'belum dibayar')");
}

$penggunaan = $conn->query("SELECT * FROM penggunaan WHERE id_pelanggan='$id_pelanggan'");

$edit = false;
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
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary">Penggunaan Listrik</h2>
    </div>

    <form method="post" class="mb-5">
      <?php if ($edit) { echo "<input type='hidden' name='id_penggunaan' value='{$data_edit['id_penggunaan']}'>"; } ?>
      <div class="row mb-3">
        <div class="col-md-3"><input type="text" name="bulan" placeholder="Bulan" class="form-control" value="<?= $edit ? $data_edit['bulan'] : '' ?>" required></div>
        <div class="col-md-3"><input type="number" name="tahun" placeholder="Tahun" class="form-control" value="<?= $edit ? $data_edit['tahun'] : '' ?>" required></div>
        <div class="col-md-3"><input type="number" name="meter_awal" placeholder="Meter Awal" class="form-control" value="<?= $edit ? $data_edit['meter_awal'] : '' ?>" required></div>
        <div class="col-md-3"><input type="number" name="meter_akhir" placeholder="Meter Akhir" class="form-control" value="<?= $edit ? $data_edit['meter_akhir'] : '' ?>" required></div>
      </div>
      <button type="submit" name="<?= $edit ? 'update' : 'tambah' ?>" class="btn btn-<?= $edit ? 'warning' : 'success' ?> w-100">
        <?= $edit ? 'Update Data' : 'Simpan Penggunaan' ?>
      </button>
    </form>

    <h4 class="mb-3">Riwayat Penggunaan</h4>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr><th>Bulan</th><th>Tahun</th><th>Meter Awal</th><th>Meter Akhir</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php while ($row = $penggunaan->fetch_assoc()) { ?>
        <tr>
          <td><?= $row['bulan'] ?></td>
          <td><?= $row['tahun'] ?></td>
          <td><?= $row['meter_awal'] ?></td>
          <td><?= $row['meter_akhir'] ?></td>
          <td>
            <a href="penggunaan.php?edit=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="penggunaan.php?hapus=<?= $row['id_penggunaan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
          </td>
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
