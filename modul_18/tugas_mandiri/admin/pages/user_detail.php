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
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Detail User</h3>
  <a href="index.php?page=user" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<div class="card border-0 shadow-sm" style="max-width:500px;">
  <div class="card-body">
    <table class="table table-borderless mb-0">
      <tr>
        <th style="width:140px;color:#526071;">ID User</th>
        <td><?= $row['id_user'] ?></td>
      </tr>
      <tr>
        <th style="color:#526071;">Nama Lengkap</th>
        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
      </tr>
      <tr>
        <th style="color:#526071;">Username</th>
        <td><?= htmlspecialchars($row['username']) ?></td>
      </tr>
      <tr>
        <th style="color:#526071;">Level</th>
        <td>
          <span class="badge <?= $row['level'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
            <?= $row['level'] ?>
          </span>
        </td>
      </tr>
    </table>
    <?php if ($_SESSION['level'] === 'admin'): ?>
      <div class="mt-3 d-flex gap-2">
        <a href="index.php?page=user_update&id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="pages/user_delete.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm"
          onclick="return confirm('Hapus user ini?')">Hapus</a>
      </div>
    <?php endif; ?>
  </div>
</div>