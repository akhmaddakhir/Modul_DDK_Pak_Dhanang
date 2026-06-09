<?php $active = $_GET['page'] ?? 'dashboard'; ?>
<aside class="sidebar">
    <h2>Admin Panel</h2>
    <a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">Dashboard</a>
    <a class="<?= str_starts_with($active, 'user') ? 'active' : '' ?>" href="index.php?page=user">Halaman User</a>
    <a class="<?= str_starts_with($active, 'berita') ? 'active' : '' ?>" href="index.php?page=berita">Halaman Berita</a>
</aside>