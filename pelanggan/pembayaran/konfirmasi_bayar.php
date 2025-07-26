<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['level'] !== 'pelanggan') {
  header("Location: ../auth/login.php");
  exit;
}

require '../../config/database.php';

if (!isset($_GET['id_tagihan'])) {
  die("Tagihan tidak dipilih.");
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_tagihan = (int)$_GET['id_tagihan'];

// Ambil detail tagihan yang masih belum dibayar
$sql = "SELECT t.id_tagihan, t.bulan, t.tahun, t.jumlah_meter,
               tr.tarifperkwh, (t.jumlah_meter * tr.tarifperkwh) AS total
        FROM tagihan t
        JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
        JOIN tarif tr ON tr.id_tarif = p.id_tarif
        WHERE t.id_tagihan = ? AND t.id_pelanggan = ? AND t.status = 'belum dibayar'
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_tagihan, $id_pelanggan);
$stmt->execute();
$detail = $stmt->get_result()->fetch_assoc();

if (!$detail) {
  die("Tagihan tidak ditemukan atau sudah dibayar.");
}

$success = $error = "";

// Proses submit bukti pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi ulang
    $stmt->execute();
    $detail = $stmt->get_result()->fetch_assoc();
    if (!$detail) {
        $error = "Tagihan tidak valid.";
    } else {
        $upload_ok = true;
        $path_upload = $_SERVER['DOCUMENT_ROOT'] . "/pembayaran-listrik/uploads/";
        if (!is_dir($path_upload)) {
            mkdir($path_upload, 0775, true);
        }

        $namaFile = null;

        if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
            $extValid = ['jpg','jpeg','png','pdf'];
            $original = $_FILES['bukti']['name'];
            $tmp = $_FILES['bukti']['tmp_name'];
            $size = $_FILES['bukti']['size'];

            $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
            if (!in_array($ext, $extValid)) {
                $upload_ok = false;
                $error = "Format file tidak didukung. Gunakan jpg, jpeg, png, atau pdf.";
            } elseif ($size > 2 * 1024 * 1024) {
                $upload_ok = false;
                $error = "Ukuran file terlalu besar (maks 2MB).";
            } else {
                $namaFile = "bukti_" . $id_tagihan . "_" . time() . "." . $ext;
                if (!move_uploaded_file($tmp, $path_upload . $namaFile)) {
                    $upload_ok = false;
                    $error = "Gagal mengunggah file.";
                }
            }
        } else {
            $upload_ok = false;
            $error = "Harap unggah bukti pembayaran.";
        }

        if ($upload_ok) {
            $conn->begin_transaction();
            try {
                $sqlIns = "INSERT INTO pembayaran (
                            id_tagihan, tanggal_pembayaran, bulan_bayar, total_bayar,
                            bukti_bayar, status_bayar, id_user
                          ) VALUES (?, NOW(), ?, ?, ?, 'menunggu verifikasi', NULL)";
                $stmtIns = $conn->prepare($sqlIns);
                $bulan_bayar = $detail['bulan'];
                $jumlah_bayar = $detail['total'];
                $stmtIns->bind_param("iids", $id_tagihan, $bulan_bayar, $jumlah_bayar, $namaFile);
                $stmtIns->execute();

                $conn->query("UPDATE tagihan SET status='menunggu verifikasi' 
                              WHERE id_tagihan=$id_tagihan AND status='belum dibayar'");

                $conn->commit();
                $success = "Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.";
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Gagal menyimpan pembayaran: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Konfirmasi Pembayaran</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
 body { background:#f1f5f9; font-family:'Segoe UI', sans-serif; }
 .card { border-radius:18px; box-shadow:0 4px 14px rgba(0,0,0,.08); }
 .qr-box { background:#fff; padding:16px; border-radius:12px; text-align:center; }
 .qr-box img { max-width:220px; }
</style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between mb-4">
    <h3>Konfirmasi Pembayaran</h3>
    <div>
      <a href="pembayaran.php" class="btn btn-outline-secondary btn-sm">← Kembali</a>
      <a href="../dashboard.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
    </div>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <!-- Detail Tagihan -->
    <div class="col-lg-6">
      <div class="card p-4">
        <h5 class="mb-3">Detail Tagihan</h5>
        <table class="table table-sm">
          <tr><th>Periode</th><td><?= htmlspecialchars($detail['bulan']) ?>/<?= htmlspecialchars($detail['tahun']) ?></td></tr>
            <tr><th>Pemakaian (kWh)</th><td><?= htmlspecialchars($detail['jumlah_meter']) ?></td></tr>
          <tr><th>Tarif / kWh</th><td>Rp <?= number_format($detail['tarifperkwh'],2,',','.') ?></td></tr>
          <tr><th>Total Bayar</th><td><strong>Rp <?= number_format($detail['total'],2,',','.') ?></strong></td></tr>
          <tr><th>Status Saat Ini</th><td>
            <?php
              $st = $conn->query("SELECT status FROM tagihan WHERE id_tagihan=$id_tagihan")->fetch_assoc();
              echo htmlspecialchars($st['status']);
            ?>
          </td></tr>
        </table>
      </div>
    </div>

    <!-- QR & Upload -->
    <div class="col-lg-6">
      <div class="card p-4 h-100 d-flex flex-column">
        <h5 class="mb-3">Pembayaran via QRIS</h5>
        <div class="qr-box mb-3">
          <?php
            // Contoh payload / link QRIS statis
            $qris_payload = "https://contoh-domain.com/qris?id_tagihan=".$detail['id_tagihan']."&total=".$detail['total'];
            // Untuk QR sementara bisa pakai API quick chart (jika online) atau gambar statis lokal 'qris.png'
            // Di local offline, simpan file qris.png di folder yang sama.
          ?>
          <!-- Jika punya file qris.png -->
          <!-- <img src="qris.png" alt="QRIS"> -->

          <!-- Dinamis (online) gunakan QuickChart (jika server punya akses internet) -->
          <img src="https://quickchart.io/qr?text=<?= urlencode($qris_payload) ?>&size=220" alt="QRIS">

          <p class="small text-muted mt-2 mb-0">
            Scan QR ini dengan aplikasi e-wallet (OVO/DANA/GoPay/ShopeePay) lalu upload bukti pembayaran.
          </p>
        </div>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-2">
            <label class="form-label">Upload Bukti (jpg/png/pdf, maks 2MB)</label>
            <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success w-100 mt-2">Kirim Bukti Pembayaran</button>
        </form>

        <div class="mt-3">
          <p class="small text-muted mb-1">Setelah upload, admin akan memverifikasi pembayaran kamu.</p>
          <p class="small text-muted">Status tagihan akan berubah menjadi <em>“sudah dibayar”</em> setelah diverifikasi.</p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
