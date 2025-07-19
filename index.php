<?php
    // buat session login
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location: login_pelanggan.php");
        exit;
    }

    // memanggil template header
    include './includes/header.php';
?>

<h2>Selamat Datang</h2>

<!-- memanggil template footer -->
<?php include './includes/footer.php'; ?>