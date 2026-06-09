<?php
$result = $conn->query("SELECT * FROM news ORDER BY date DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Halaman Berita</h3>
  <?php if ($_SESSION['level'] === 'admin'): ?>
    <a href="index.php?page=berita_insert" class="btn btn-primary btn-sm">+ Tambah Berita</a>
  <?php endif; ?>
</div>

<?php if (($_GET['pesan'] ?? '') === 'insert'): ?>
  <div class="alert alert-success alert-dismissible fade show">Berita berhasil ditambahkan. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif (($_GET['pesan'] ?? '') === 'update'): ?>
  <div class="alert alert-success alert-dismissible fade show">Berita berhasil diperbarui. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php elseif (($_GET['pesan'] ?? '') === 'delete'): ?>
  <div class="alert alert-success alert-dismissible fade show">Berita berhasil dihapus. <button type="button"
      class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead style="background:#17324d;color:white;">
        <tr>
          <th class="ps-3">#</th>
          <th>Thumbnail</th>
          <th>Judul</th>
          <th>Author</th>
          <th>Tanggal</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1;
        $count = 0;
        while ($row = $result->fetch_assoc()):
          $count++; ?>
          <tr>
            <td class="ps-3"><?= $no++ ?></td>
            <td>
              <?php if ($row['image']): ?>
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="thumb" alt="thumb">
              <?php else: ?>
                <div class="thumb d-flex align-items-center justify-content-center bg-light text-muted small"
                  style="width:92px;height:64px;border-radius:8px;">No Image</div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td><?= date('d M Y', strtotime($row['date'])) ?></td>
            <td class="text-center">
              <a href="index.php?page=berita_detail&id=<?= $row['id'] ?>"
                class="btn btn-info btn-sm text-white">Detail</a>
              <?php if ($_SESSION['level'] === 'admin'): ?>
                <a href="index.php?page=berita_update&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="pages/berita_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                  onclick="return confirm('Hapus berita ini?')">Hapus</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if ($count === 0): ?>
          <tr>
            <td colspan="6" class="text-center py-3 text-muted">Belum ada berita.
              <?= $_SESSION['level'] === 'admin' ? '<a href="index.php?page=berita_insert">Tambah sekarang</a>' : '' ?>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>