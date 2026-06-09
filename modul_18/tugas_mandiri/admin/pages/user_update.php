<?php
$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM tb_user WHERE id_user = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
  echo '<div class="alert alert-danger">User tidak ditemukan.</div>';
  echo '<a href="index.php?page=user" class="btn btn-secondary btn-sm">← Kembali</a>';
  return;
}

$errors = [];
$nama = $row['nama_lengkap'];
$level_val = $row['level'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $nama = trim($_POST['nama_lengkap']);
  $level_val = $_POST['level'];
  $password = $_POST['password'];

  if ($nama === '')
    $errors[] = 'Nama lengkap wajib diisi.';
  if (!in_array($level_val, ['admin', 'user']))
    $errors[] = 'Level tidak valid.';

  if (empty($errors)) {
    if ($password !== '') {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $upd = $conn->prepare("UPDATE tb_user SET nama_lengkap=?, level=?, password=? WHERE id_user=?");
      $upd->bind_param("sssi", $nama, $level_val, $hash, $id);
    } else {
      $upd = $conn->prepare("UPDATE tb_user SET nama_lengkap=?, level=? WHERE id_user=?");
      $upd->bind_param("ssi", $nama, $level_val, $id);
    }
    $upd->execute();
    header("Location: index.php?page=user&pesan=update");
    exit();
  }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Edit User</h3>
  <a href="index.php?page=user" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e)
      echo "<li>$e</li>"; ?></ul>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" disabled>
        <div class="form-text">Username tidak dapat diubah.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password Baru <span class="text-muted small">(kosongkan jika tidak
            diubah)</span></label>
        <input type="password" name="password" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Level</label>
        <select name="level" class="form-select">
          <option value="user" <?= $level_val === 'user' ? 'selected' : '' ?>>User</option>
          <option value="admin" <?= $level_val === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <button type="submit" name="update" class="btn btn-warning">Update</button>
    </form>
  </div>
</div>