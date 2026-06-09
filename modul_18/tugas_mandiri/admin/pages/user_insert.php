<?php
$errors = [];
$nama = $username_val = $level_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
  $nama = trim($_POST['nama_lengkap']);
  $username_val = trim($_POST['username']);
  $password = $_POST['password'];
  $level_val = $_POST['level'];

  if ($nama === '')
    $errors[] = 'Nama lengkap wajib diisi.';
  if ($username_val === '')
    $errors[] = 'Username wajib diisi.';
  if ($password === '')
    $errors[] = 'Password wajib diisi.';
  if (!in_array($level_val, ['admin', 'user']))
    $errors[] = 'Level tidak valid.';

  if (empty($errors)) {
    $cek = $conn->prepare("SELECT id_user FROM tb_user WHERE username = ?");
    $cek->bind_param("s", $username_val);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
      $errors[] = 'Username sudah digunakan.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO tb_user (nama_lengkap, username, password, level) VALUES (?,?,?,?)");
      $stmt->bind_param("ssss", $nama, $username_val, $hash, $level_val);
      $stmt->execute();
      header("Location: index.php?page=user&pesan=insert");
      exit();
    }
  }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Tambah User</h3>
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
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username_val) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Level</label>
        <select name="level" class="form-select">
          <option value="user" <?= $level_val === 'user' ? 'selected' : '' ?>>User</option>
          <option value="admin" <?= $level_val === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>
      <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>