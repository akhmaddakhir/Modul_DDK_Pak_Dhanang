<?php
$level_akses = "admin";
include "cek.php";

?>

<h2>Dashboard Halaman Admin</h2>
<p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
<i>ISI MENU  DAN KONTEN HALAMAN ADMIN DISINI</i>

<a href="logout.php">Logout</a>