<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: login.php");
  exit;
}
$isAdmin = ($_SESSION["level"] ?? "") === "admin";
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Data Ekstrakurikuler Siswa</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      padding: 32px 20px;
    }

    .wrap {
      max-width: 980px;
      margin: 0 auto;
    }

    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
      font-size: 13px;
      color: #6b7280;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .page-header h2 {
      font-size: 18px;
      color: #10b981;
    }

    .button {
      display: inline-block;
      padding: 6px 14px;
      font-size: 13px;
      font-weight: 500;
      border: 1px solid transparent;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
    }

    .button-primary {
      background: #10b981;
      color: #fff;
    }

    .button-primary:hover {
      background: #059669;
    }

    .button-warning {
      background: #f59e0b;
      color: #fff;
    }

    .button-warning:hover {
      background: #d97706;
    }

    .button-danger {
      background: #ef4444;
      color: #fff;
    }

    .button-danger:hover {
      background: #dc2626;
    }

    .button-secondary {
      background: #e5e7eb;
      color: #374151;
      border: 1px solid #d1d5db;
    }

    .button-secondary:hover {
      background: #d1d5db;
    }

    .button-logout {
      background: #ef4444;
      color: #fff;
    }

    .button-logout:hover {
      background: #dc2626;
    }

    .alert {
      padding: 10px 14px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 14px;
    }

    .alert-success {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #bbf7d0;
    }

    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    thead th {
      background: #10b981;
      color: #fff;
      padding: 10px;
      text-align: left;
      font-weight: 600;
    }

    tbody td {
      padding: 9px 10px;
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    .badge {
      display: inline-block;
      padding: 2px 7px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 500;
      margin: 1px 2px;
    }

    .badge-hobi {
      background: #dbeafe;
      color: #1e40af;
    }

    .badge-ekskul {
      background: #ede9fe;
      color: #6d28d9;
    }

    .badge-level-admin {
      background: #fef9c3;
      color: #92400e;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 600;
      border: 1px solid #fde68a;
    }

    .badge-level-user {
      background: #dbeafe;
      color: #1e40af;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 600;
      border: 1px solid #bfdbfe;
    }

    .aksi-group {
      display: flex;
      gap: 5px;
    }

    .empty {
      text-align: center;
      color: #9ca3af;
      padding: 20px;
    }

    .info-user {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #1e40af;
      padding: 8px 14px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 14px;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="topbar">
      <span>Halo, <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></span>
      <span class="<?= $isAdmin ? 'badge-level-admin' : 'badge-level-user' ?>">
        <?= $isAdmin ? 'Admin' : 'User' ?>
      </span>
      <a href="logout.php" class="button button-logout">Logout</a>
    </div>

    <div class="page-header">
      <h2>Data Pendaftaran Ekstrakurikuler</h2>
      <?php if ($isAdmin): ?>
        <a href="tambah.php" class="button button-primary">+ Tambah Siswa</a>
      <?php endif; ?>
    </div>

    <?php if (!$isAdmin): ?>
      <div class="info-user">
        &#9432; Anda login sebagai <strong>User</strong>. Hanya dapat melihat data. Hubungi admin untuk mengelola data.
      </div>
    <?php endif; ?>

    <?php if (isset($_GET["msg"])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_GET["msg"]) ?></div>
    <?php endif; ?>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Tgl Lahir</th>
            <th>Kota</th>
            <th>JK</th>
            <th>Hobi</th>
            <th>Ekskul</th>
            <?php if ($isAdmin): ?>
              <th>Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          require "koneksi.php";
          $result = mysqli_query($conn, "SELECT * FROM tb_siswa ORDER BY nama ASC");
          $no = 1;
          if (mysqli_num_rows($result) === 0):
            ?>
            <tr>
              <td colspan="<?= $isAdmin ? '10' : '9' ?>" class="empty">Belum ada data.</td>
            </tr>
          <?php else:
            while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row["nis"]) ?></td>
                <td><?= htmlspecialchars($row["nama"]) ?></td>
                <td><?= htmlspecialchars($row["kelas"]) ?></td>
                <td><?= $row["ttl"] ? date("d/m/Y", strtotime($row["ttl"])) : "-" ?></td>
                <td><?= htmlspecialchars($row["kota"]) ?></td>
                <td><?= $row["jk"] === "L" ? "Laki-Laki" : "Perempuan" ?></td>
                <td>
                  <?php foreach (explode(",", $row["hobi"]) as $h): ?>
                    <span class="badge badge-hobi"><?= htmlspecialchars(trim($h)) ?></span>
                  <?php endforeach; ?>
                </td>
                <td>
                  <?php foreach (explode(",", $row["ekskul"]) as $e): ?>
                    <span class="badge badge-ekskul"><?= htmlspecialchars(trim($e)) ?></span>
                  <?php endforeach; ?>
                </td>
                <?php if ($isAdmin): ?>
                  <td>
                    <div class="aksi-group">
                      <a href="edit.php?nis=<?= urlencode($row["nis"]) ?>" class="button button-warning">Edit</a>
                      <a href="hapus.php?nis=<?= urlencode($row["nis"]) ?>" class="button button-danger"
                        onclick="return confirm('Hapus data <?= htmlspecialchars($row["nama"]) ?>?')">Hapus</a>
                    </div>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>