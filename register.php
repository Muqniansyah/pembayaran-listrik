<?php
include 'config/database.php';

if (isset($_POST['register'])) {
  $username   = $_POST['username'];
  $password   = $_POST['password'];
  $nama       = $_POST['nama'];
  $alamat     = $_POST['alamat'];
  $nomor_kwh  = $_POST['nomor_kwh'];
  $id_tarif   = $_POST['id_tarif'];

  // Cek apakah username atau nomor KWH sudah digunakan
  $cek = $conn->query("SELECT * FROM pelanggan WHERE username='$username' OR nomor_kwh='$nomor_kwh'");
  if ($cek->num_rows > 0) {
    $error = "Username atau Nomor KWH sudah terdaftar!";
  } else {
    $sql = "INSERT INTO pelanggan (username, password, nomor_kwh, nama_pelanggan, alamat, id_tarif)
            VALUES ('$username', '$password', '$nomor_kwh', '$nama', '$alamat', $id_tarif)";
    if ($conn->query($sql)) {
      $success = "Registrasi berhasil! Silakan login.";
    } else {
      $error = "Gagal registrasi: " . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi Pelanggan</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #dbeafe, #eff6ff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .register-container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 450px;
    }

    .register-container h2 {
      text-align: center;
      color: #1e3a8a;
      margin-bottom: 20px;
    }

    .register-container input,
    .register-container select {
      width: 100%;
      padding: 12px 4px;
      margin-bottom: 15px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      background-color: #f8fafc;
      font-size: 14px;
    }

    .register-container button {
      width: 100%;
      padding: 12px;
      background-color: #2563eb;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .register-container button:hover {
      background-color: #1d4ed8;
    }

    .register-container p.error {
      color: #ef4444;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .register-container p.success {
      color: #16a34a;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .register-container a {
      text-decoration: none;
      color: #2563eb;
      font-weight: bold;
    }

    .register-container a:hover {
      color: #1d4ed8;
    }

    .login-link {
      text-align: center;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <h2>Registrasi Pelanggan</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="text" name="nama" placeholder="Nama Lengkap" required>
      <input type="text" name="alamat" placeholder="Alamat" required>
      <input type="text" name="nomor_kwh" placeholder="Nomor KWH" required>

      <label for="id_tarif">Daya Listrik:</label>
      <select name="id_tarif" required>
        <option value="">-- Pilih Daya Listrik --</option>
        <?php
        $tarif = $conn->query("SELECT * FROM tarif");
        while ($row = $tarif->fetch_assoc()) {
          echo "<option value='" . $row['id_tarif'] . "'>" . $row['daya'] . " VA - Rp " . $row['tarifperkwh'] . "/kWh</option>";
        }
        ?>
      </select>

      <button type="submit" name="register">Daftar</button>
    </form>

    <div class="login-link">
      <p>Sudah punya akun? <a href="login_pelanggan.php">Login di sini</a></p>
    </div>
  </div>

</body>
</html>
