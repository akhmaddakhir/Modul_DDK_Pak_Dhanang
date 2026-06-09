<?php
session_start();
include "../cek.php";
include "../koneksi.php";
cek_level('admin');

$id = (int) ($_GET['id'] ?? 0);

$row = $conn->prepare("SELECT image FROM news WHERE id = ?");
$row->bind_param("i", $id);
$row->execute();
$data = $row->get_result()->fetch_assoc();
if ($data && $data['image'] && file_exists('../uploads/' . $data['image'])) {
  unlink('../uploads/' . $data['image']);
}

$stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../index.php?page=berita&pesan=delete");
exit();
?>