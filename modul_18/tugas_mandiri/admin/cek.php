<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

function cek_level($level_akses)
{
  if ($_SESSION['level'] !== $level_akses) {
    header("Location: index.php?page=dashboard&pesan=akses");
    exit();
  }
}
?>