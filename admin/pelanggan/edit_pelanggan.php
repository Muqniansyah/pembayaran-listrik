<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

// Sebelumnya
// $id = $_GET['id'] ?? 0;

// Perbaikan sesuai guideline
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = $conn->query("SELECT * FROM pelanggan WHERE id_pelanggan = $id");
$pelanggan = $query->fetch_assoc();

// Query ini tidak menggunakan prepared statement karena tidak ada parameter dari user input
// dan hanya menampilkan semua data dari tabel tarif.
$tarif = $conn->query("SELECT * FROM tarif"); 

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $nomor_kwh = $_POST['nomor_kwh'];
  $alamat = $_POST['alamat'];
  $id_tarif = $_POST['id_tarif'];

  $update = $conn->query("UPDATE pelanggan SET 
    nama_pelanggan='$nama',
    username='$username',
    nomor_kwh='$nomor_kwh',
    alamat='$alamat',
    id_tarif='$id_tarif' 
    WHERE id_pelanggan=$id");

  if ($update) {
    header("Location: pelanggan.php");
    exit;
  } else {
    echo "Gagal mengupdate data pelanggan.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="mb-4 text-primary">✏️ Edit Data Pelanggan</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Nama Pelanggan</label>
      <input type="text" name="nama" class="form-control" value="<?= $pelanggan['nama_pelanggan'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Username</label>
      <input type="text" name="username" class="form-control" value="<?= $pelanggan['username'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Nomor KWH</label>
      <input type="text" name="nomor_kwh" class="form-control" value="<?= $pelanggan['nomor_kwh'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control" required><?= $pelanggan['alamat'] ?></textarea>
    </div>
    <div class="mb-3">
      <label>Tarif</label>
      <select name="id_tarif" class="form-select" required>
        <?php while ($t = $tarif->fetch_assoc()) { ?>
          <option value="<?= $t['id_tarif'] ?>" <?= $pelanggan['id_tarif'] == $t['id_tarif'] ? 'selected' : '' ?>>
            <?= $t['daya'] ?> VA - Rp <?= number_format($t['tarifperkwh']) ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <button type="submit" name="simpan" class="btn btn-success">💾 Simpan Perubahan</button>
    <a href="pelanggan.php" class="btn btn-secondary">❌ Batal</a>
  </form>
</div>
</body>
</html>
