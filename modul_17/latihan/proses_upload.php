<?php
include "koneksi.php";

$title = $_POST['title'];
$content = $_POST['content'];
$author = $_POST['author'];
// Upload gambar
$image = $_FILES['image']['name'];
$image_baru = time() . "_" . basename($image);
$tmp = $_FILES['image']['tmp_name'];
$target = "upload/" . $image_baru;
// Validasi ekstensi
$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowed)) {
  die("Format gambar tidak valid!");
}

// Pindahkan file
if (move_uploaded_file($tmp, $target)) {
  // Simpan ke database
  $sql = "INSERT INTO news (title, content, author, image)
            VALUES ('$title', '$content', '$author', '$image_baru')";
  if ($conn->query($sql) === TRUE) {
    header("Location: tampil.php");
  } else {
    echo "Error: " . $conn->error;
  }
} else {
  echo "Gagal upload gambar!";
}
?>