<?php
$errors = [];
$title = $content_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
  $title = trim($_POST['title']);
  $content_val = trim($_POST['content']);
  $author = htmlspecialchars($_SESSION['nama_lengkap']);
  $image = null;

  if ($title === '')
    $errors[] = 'Judul wajib diisi.';
  if ($content_val === '')
    $errors[] = 'Konten wajib diisi.';

  if (!empty($_FILES['image']['name'])) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
      $errors[] = 'Format gambar tidak didukung (jpg, jpeg, png, gif, webp).';
    } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
      $errors[] = 'Ukuran gambar maksimal 2MB.';
    } else {
      $image = uniqid('img_') . '.' . $ext;
      move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
    }
  }

  if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO news (title, content, author, image) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $title, $content_val, $author, $image);
    $stmt->execute();
    header("Location: index.php?page=berita&pesan=insert");
    exit();
  }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Tambah Berita</h3>
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
        <label class="form-label">Gambar <span class="text-muted small">(opsional, maks 2MB)</span></label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>