<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id_user, nama_lengkap, username, password, level FROM tb_user WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();

  if ($data && password_verify($password, $data['password'])) {
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['level'] = $data['level'];
    header("Location: index.php?page=dashboard");
    exit();
  }
}

header("Location: login.php?pesan=gagal");
exit();
?>