<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

$id = $_GET['id'] ?? 0;

// 1. Ambil id_penggunaan yang berelasi dengan pelanggan
$penggunaan = $conn->query("SELECT id_penggunaan FROM penggunaan WHERE id_pelanggan = $id");
while ($row = $penggunaan->fetch_assoc()) {
  $id_penggunaan = $row['id_penggunaan'];

  // 2. Ambil dan hapus pembayaran yang terkait dengan tagihan-tagihan penggunaan ini
  $tagihan = $conn->query("SELECT id_tagihan FROM tagihan WHERE id_penggunaan = $id_penggunaan");
  while ($t = $tagihan->fetch_assoc()) {
    $id_tagihan = $t['id_tagihan'];
    $conn->query("DELETE FROM pembayaran WHERE id_tagihan = $id_tagihan");
  }

  // 3. Hapus tagihan yang terkait dengan penggunaan
  $conn->query("DELETE FROM tagihan WHERE id_penggunaan = $id_penggunaan");
}

// 4. Hapus penggunaan yang terkait dengan pelanggan
// Sebelumnya
// $conn->query("DELETE FROM penggunaan WHERE id_pelanggan = $id");

// Best practice (menggunakan prepared statement untuk keamanan)
$stmtHapusPenggunaan = $conn->prepare("DELETE FROM penggunaan WHERE id_pelanggan = ?");
$stmtHapusPenggunaan->bind_param("i", $id);
$stmtHapusPenggunaan->execute();

// 5. Hapus data pelanggan
$hapus = $conn->query("DELETE FROM pelanggan WHERE id_pelanggan = $id");

if ($hapus) {
  header("Location: pelanggan.php");
  exit;
} else {
  echo "Gagal menghapus data pelanggan.";
}
?>
