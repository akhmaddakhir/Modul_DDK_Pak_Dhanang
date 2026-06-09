<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: login.php");
  exit;
}
// Hanya admin yang boleh akses
if (($_SESSION["level"] ?? "") !== "admin") {
  header("Location: index.php?msg=Akses ditolak! Hanya admin yang dapat menghapus data.");
  exit;
}

require "koneksi.php";

$nis = $_GET["nis"] ?? "";

if (empty($nis)) {
  header("Location: index.php");
  exit;
}

$sql = "DELETE FROM tb_siswa WHERE nis='" . mysqli_real_escape_string($conn, $nis) . "'";

if (mysqli_query($conn, $sql)) {
  header("Location: index.php?msg=Data berhasil dihapus");
} else {
  header("Location: index.php?msg=Gagal menghapus data");
}
exit;
