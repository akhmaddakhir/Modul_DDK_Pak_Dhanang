<?php
include "koneksi.php";
$sql = "SELECT * FROM news  ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Berita</title>
</head>

<body>
  <h2>Daftar Berita</h2>
  <a href="form_upload.php">+ Tambah Data</a><br><br>
  <br><br>
  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>No</th>
      <th>Judul</th>
      <th>Content</th>
      <th>Gambar</th>
      <th>Tanggal</th>
      <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($row = $result->fetch_assoc()) {
      ?>

      <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['title']; ?></td>
        <td><?= substr($row['content'], 0, 100); ?>...</td>
        <td><?= $row['author']; ?></td>
        <td>
          <img src="upload/<?= $row['image']; ?>" width="100">
        </td>
        <td><?= $row['date']; ?></td>
        <td>
          <a href="detail.php?id=<?= $row['id']; ?>">Detail</a> |
          <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
          <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
        </td>
      </tr>

    <?php } ?>

  </table>
</body>

</html>