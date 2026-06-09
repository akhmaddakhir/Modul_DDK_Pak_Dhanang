<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_berita";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal. Buat database '$db' terlebih dahulu di phpMyAdmin.");
}

$conn->query("CREATE TABLE IF NOT EXISTS tb_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    level ENUM('admin','user') NOT NULL DEFAULT 'user'
)");

$conn->query("CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$cek_user = $conn->query("SELECT COUNT(*) AS total FROM tb_user")->fetch_assoc();
if ((int) $cek_user['total'] === 0) {
  $admin_pass = password_hash('admin', PASSWORD_DEFAULT);
  $user_pass = password_hash('user', PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO tb_user (nama_lengkap, username, password, level) VALUES (?, ?, ?, ?)");

  $nama = "Administrator";
  $username = "admin";
  $level = "admin";
  $stmt->bind_param("ssss", $nama, $username, $admin_pass, $level);
  $stmt->execute();

  $nama = "User Berita";
  $username = "user";
  $level = "user";
  $stmt->bind_param("ssss", $nama, $username, $user_pass, $level);
  $stmt->execute();
}
?>