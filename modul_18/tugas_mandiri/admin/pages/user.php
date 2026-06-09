<?php
$result = $conn->query("SELECT * FROM tb_user ORDER BY id_user ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Manajemen User</h3>
  <?php if ($_SESSION['level'] === 'admin'): ?>
    <a href="index.php?page=user_insert" class="btn btn-primary btn-sm">+ Tambah User</a>
  <?php endif; ?>
</div>

<?php if (($_GET['pesan'] ?? '') === 'insert'): ?>
  <div class="alert alert-success alert-dismissible fade show">User berhasil ditambahkan. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif (($_GET['pesan'] ?? '') === 'update'): ?>
  <div class="alert alert-success alert-dismissible fade show">User berhasil diperbarui. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif (($_GET['pesan'] ?? '') === 'delete'): ?>
  <div class="alert alert-success alert-dismissible fade show">User berhasil dihapus. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif (($_GET['pesan'] ?? '') === 'delete_self'): ?>
  <div class="alert alert-warning alert-dismissible fade show">Tidak dapat menghapus akun Anda sendiri. <button
      type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead style="background:#17324d;color:white;">
        <tr>
          <th class="ps-3">#</th>
          <th>Nama Lengkap</th>
          <th>Username</th>
          <th>Level</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1;
        while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td class="ps-3"><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td>
              <span class="badge <?= $row['level'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                <?= $row['level'] ?>
              </span>
            </td>
            <td class="text-center">
              <a href="index.php?page=user_detail&id=<?= $row['id_user'] ?>"
                class="btn btn-info btn-sm text-white">Detail</a>
              <?php if ($_SESSION['level'] === 'admin'): ?>
                <a href="index.php?page=user_update&id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="pages/user_delete.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm"
                  onclick="return confirm('Hapus user ini?')">Hapus</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if ($conn->query("SELECT COUNT(*) AS c FROM tb_user")->fetch_assoc()['c'] == 0): ?>
          <tr>
            <td colspan="5" class="text-center py-3 text-muted">Belum ada data user.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>