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
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Detail Berita</h3>
  <a href="index.php?page=berita" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<div class="card border-0 shadow-sm" style="max-width:700px;">
  <div class="card-body">
    <?php if ($row['image']): ?>
      <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="hero-img mb-3"
        alt="<?= htmlspecialchars($row['title']) ?>">
    <?php endif; ?>
    <h4 class="fw-bold"><?= htmlspecialchars($row['title']) ?></h4>
    <div class="text-muted small mb-3">
      Oleh <strong><?= htmlspecialchars($row['author']) ?></strong> &middot;
      <?= date('d M Y, H:i', strtotime($row['date'])) ?>
    </div>
    <div style="line-height:1.8;"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
    <?php if ($_SESSION['level'] === 'admin'): ?>
      <div class="mt-4 d-flex gap-2">
        <a href="index.php?page=berita_update&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="pages/berita_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
          onclick="return confirm('Hapus berita ini?')">Hapus</a>
      </div>
    <?php endif; ?>
  </div>
</div>