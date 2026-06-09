<?php
include "cek.php";
include "koneksi.php";

$page = $_GET['page'] ?? 'dashboard';
$allowed = [
  'dashboard',
  'user',
  'user_detail',
  'user_insert',
  'user_update',
  'berita',
  'berita_detail',
  'berita_insert',
  'berita_update',
];

if (!in_array($page, $allowed, true)) {
  $page = 'dashboard';
}

$admin_only = ['user_insert', 'user_update', 'berita_insert', 'berita_update'];
if (in_array($page, $admin_only, true)) {
  cek_level('admin');
}
?>

<?php include 'templates/header.php'; ?>
<?php include 'templates/sidebar.php'; ?>

<main class="main">
  <div class="topbar">
    <div>
      <strong>
        <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Guest') ?>
      </strong>
      <span>
        <?= htmlspecialchars($_SESSION['level'] ?? 'user') ?>
      </span>
    </div>
    <a class="btn btn-danger" href="logout.php">Logout</a>
  </div>

  <section class="content">
    <?php include "pages/$page.php"; ?>
  </section>

  <?php include 'templates/footer.php'; ?>
</main>