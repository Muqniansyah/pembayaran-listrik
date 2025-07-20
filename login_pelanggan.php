<?php
session_start();
include 'config/database.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM pelanggan WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['role'] = 'pelanggan';
    $_SESSION['nama'] = $user['nama_pelanggan'];
    $_SESSION['id_pelanggan'] = $user['id_pelanggan'];
    header("Location: pelanggan/dashboard.php");
    exit;
  } else {
    $error = "Login gagal, periksa username dan password pelanggan.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Pelanggan</title>
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      /* background: linear-gradient(135deg, #dbeafe, #eff6ff); */
      background-image: url("./assets/img/login.jpg");
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
      text-align: center;
      color: #1e3a8a;
      margin-bottom: 20px;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px 4px;
      margin-bottom: 15px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      background-color: #f8fafc;
      font-size: 14px;
    }

    .login-container button {
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

    .login-container button:hover {
      background-color: #1d4ed8;
    }

    .login-container p.error {
      color: #ef4444;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .login-container p.success {
      color: #16a34a;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .login-container a {
      text-decoration: none;
      color: #2563eb;
      font-weight: bold;
    }

    .login-container a:hover {
      color: #1d4ed8;
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Masuk</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Masuk</button>
    </form>

    <div class="register-link">
      <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
  </div>

</body>
</html>
