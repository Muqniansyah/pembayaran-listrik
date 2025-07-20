<?php
session_start();
include 'config/database.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $_SESSION['login'] = true;
    $_SESSION['role'] = 'admin';
    $_SESSION['nama'] = $admin['nama_admin'];
    header("Location: admin/dashboard.php");
    exit;
  } else {
    $error = "Login gagal, periksa kembali username dan password admin.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      /* background: linear-gradient(135deg, #e0f2fe, #f8fafc); */
      background-image: url("./assets/img/login2.jpg");
      background-size: cover;
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
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
      background-color: #1e3a8a;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-container button:hover {
      background-color: #2563eb;
    }

    .login-container p.error {
      color: #dc2626;
      text-align: center;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Login Admin</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Masuk Admin</button>
    </form>
  </div>

</body>
</html>
