<?php
$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
  echo '<div class="alert alert-danger">Berita tidak ditemukan.</div>';
  echo '<a href="index.php?page=berita" class="btn btn-secondary btn-sm">← Kembali</a>';
  return;
}

$errors = [];
$title = $row['title'];
$content_val = $row['content'];
$image = $row['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $title = trim($_POST['title']);
  $content_val = trim($_POST['content']);

  if ($title === '')
    $errors[] = 'Judul wajib diisi.';
  if ($content_val === '')
    $errors[] = 'Konten wajib diisi.';

  // Upload gambar baru (opsional)
  if (!empty($_FILES['image']['name'])) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
      $errors[] = 'Format gambar tidak didukung (jpg, jpeg, png, gif, webp).';
    } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
      $errors[] = 'Ukuran gambar maksimal 2MB.';
    } else {
      // Hapus gambar lama
      if ($row['image'] && file_exists('../uploads/' . $row['image'])) {
        unlink('../uploads/' . $row['image']);
      }
      $image = uniqid('img_') . '.' . $ext;
      move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }
  }

  if (isset($_POST['hapus_gambar']) && $row['image']) {
    if (file_exists('../uploads/' . $row['image']))
      unlink('../uploads/' . $row['image']);
    $image = null;
  }

  if (empty($errors)) {
    $upd = $conn->prepare("UPDATE news SET title=?, content=?, image=? WHERE id=?");
    $upd->bind_param("sssi", $title, $content_val, $image, $id);
    $upd->execute();
    header("Location: index.php?page=berita&pesan=update");
    exit();
  }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Edit Berita</h3>
  <a href="index.php?page=berita" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e)
      echo "<li>$e</li>"; ?></ul>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="max-width:600px;">
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Judul Berita</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Konten</label>
        <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($content_val) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Gambar</label>
        <?php if ($image): ?>
          <div class="mb-2">
            <img src="uploads/<?= htmlspecialchars($image) ?>" class="thumb" alt="current">
            <label class="ms-2 small">
              <input type="checkbox" name="hapus_gambar"> Hapus gambar ini
            </label>
          </div>
        <?php endif; ?>
        <input type="file" name="image" class="form-control" accept="image/*">
        <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar.</div>
      </div>
      <button type="submit" name="update" class="btn btn-warning">Update</button>
    </form>
  </div>
</div>