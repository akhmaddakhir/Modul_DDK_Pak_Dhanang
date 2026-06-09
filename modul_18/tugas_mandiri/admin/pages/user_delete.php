<?php
session_start();
include "../cek.php";
include "../koneksi.php";
cek_level('admin');

$id = (int) ($_GET['id'] ?? 0);

if ($id === (int) $_SESSION['id_user']) {
  header("Location: ../index.php?page=user&pesan=delete_self");
  exit();
}

$stmt = $conn->prepare("DELETE FROM tb_user WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../index.php?page=user&pesan=delete");
exit();
?>