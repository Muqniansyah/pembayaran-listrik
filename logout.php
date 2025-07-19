<?php
session_start();
session_destroy();
header("Location: login_pelanggan.php"); // atau login_admin.php sesuai asal login
exit;
?>
