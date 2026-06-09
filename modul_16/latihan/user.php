<?php
$level_akses = "user";
include "cek.php";

?>

<h2>Dashboard Halaman User</h2>
<p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
<i>ISI MENU DAN KONTEN HALAMAN USER DISINI</i>

<a href="logout.php">Logout</a>