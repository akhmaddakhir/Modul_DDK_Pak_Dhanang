<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: login.php");
  exit;
}

require "koneksi.php";

$nis_param = $_GET["nis"] ?? "";

if (empty($nis_param)) {
  header("Location: index.php");
  exit;
}

$res = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nis='" . mysqli_real_escape_string($conn, $nis_param) . "'");
$data = mysqli_fetch_assoc($res);

if (!$data) {
  header("Location: index.php?msg=Data tidak ditemukan.");
  exit;
}

$isAdmin = ($_SESSION["level"] ?? "") === "admin";

$hobiList  = array_filter(array_map("trim", explode(",", $data["hobi"])));
$ekskulList = array_filter(array_map("trim", explode(",", $data["ekskul"])));
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Detail Siswa</title>
  <style>
    /* ===== Base (sama persis dengan login.php) ===== */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px 20px;
    }

    /* ===== Card — diperlebar sedikit untuk detail ===== */
    .detail-box {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 28px 32px;
      width: 480px;
    }

    .detail-box h2 {
      text-align: center;
      color: #10b981;
      font-size: 16px;
      margin-bottom: 6px;
    }

    .detail-box .subtitle {
      text-align: center;
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 18px;
    }

    hr {
      border: none;
      border-top: 1px solid #e5e7eb;
      margin-bottom: 16px;
    }

    /* ===== Row detail ===== */
    .detail-row {
      display: flex;
      font-size: 13px;
      margin-bottom: 10px;
      align-items: flex-start;
    }

    .detail-label {
      width: 130px;
      flex-shrink: 0;
      color: #6b7280;
      font-size: 13px;
    }

    .detail-colon {
      width: 14px;
      flex-shrink: 0;
      color: #6b7280;
    }

    .detail-value {
      color: #111827;
      font-size: 13px;
      flex: 1;
    }

    /* ===== Badge (sama dengan index.php) ===== */
    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 500;
      margin: 2px 2px 2px 0;
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

    /* ===== Foto avatar placeholder ===== */
    .avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: #d1fae5;
      color: #065f46;
      font-size: 26px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px auto;
      border: 2px solid #10b981;
      letter-spacing: 1px;
    }

    /* ===== Tombol (sama dengan login.php) ===== */
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

    .button-back {
      width: 100%;
      padding: 8px;
      background: #10b981;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      display: block;
      margin-top: 6px;
    }

    .button-back:hover {
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

    /* ===== Topbar kecil ===== */
    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 8px;
      margin-bottom: 14px;
      font-size: 13px;
      color: #6b7280;
    }

    .button-logout {
      background: #ef4444;
      color: #fff;
      padding: 5px 12px;
      font-size: 12px;
    }

    .button-logout:hover {
      background: #dc2626;
    }

    .admin-actions {
      display: flex;
      gap: 8px;
      margin-top: 10px;
    }

    .admin-actions .button {
      flex: 1;
      text-align: center;
      padding: 7px 10px;
      font-size: 13px;
    }

    .divider-label {
      font-size: 11px;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
      margin-top: 2px;
    }
  </style>
</head>

<body>
  <div style="width:480px; margin: 0 auto;">
    <!-- Topbar -->
    <div class="topbar">
      <span>Halo, <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></span>
      <span class="<?= $isAdmin ? 'badge-level-admin' : 'badge-level-user' ?>">
        <?= $isAdmin ? 'Admin' : 'User' ?>
      </span>
      <a href="logout.php" class="button button-logout">Logout</a>
    </div>

    <!-- Card Detail -->
    <div class="detail-box">
      <!-- Avatar inisial -->
      <div class="avatar">
        <?= strtoupper(mb_substr($data["nama"], 0, 2)) ?>
      </div>

      <h2>Detail Data Siswa</h2>
      <p class="subtitle">Pendaftaran Ekstrakurikuler</p>
      <hr />

      <!-- Baris-baris data -->
      <div class="detail-row">
        <span class="detail-label">NIS</span>
        <span class="detail-colon">:</span>
        <span class="detail-value"><strong><?= htmlspecialchars($data["nis"]) ?></strong></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Nama</span>
        <span class="detail-colon">:</span>
        <span class="detail-value"><?= htmlspecialchars($data["nama"]) ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kelas</span>
        <span class="detail-colon">:</span>
        <span class="detail-value"><?= htmlspecialchars($data["kelas"]) ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Tgl Lahir</span>
        <span class="detail-colon">:</span>
        <span class="detail-value">
          <?= $data["ttl"] ? date("d/m/Y", strtotime($data["ttl"])) : "<span style='color:#9ca3af'>-</span>" ?>
        </span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jenis Kelamin</span>
        <span class="detail-colon">:</span>
        <span class="detail-value"><?= $data["jk"] === "L" ? "Laki-Laki" : "Perempuan" ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Alamat</span>
        <span class="detail-colon">:</span>
        <span class="detail-value" style="white-space:pre-line"><?= htmlspecialchars($data["alamat"]) ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kota</span>
        <span class="detail-colon">:</span>
        <span class="detail-value"><?= htmlspecialchars($data["kota"]) ?></span>
      </div>

      <!-- Hobi -->
      <div class="detail-row" style="align-items:flex-start;">
        <span class="detail-label">Hobi</span>
        <span class="detail-colon">:</span>
        <span class="detail-value">
          <?php if ($hobiList): ?>
            <?php foreach ($hobiList as $h): ?>
              <span class="badge badge-hobi"><?= htmlspecialchars($h) ?></span>
            <?php endforeach; ?>
          <?php else: ?>
            <span style="color:#9ca3af">-</span>
          <?php endif; ?>
        </span>
      </div>

      <!-- Ekskul -->
      <div class="detail-row" style="align-items:flex-start;">
        <span class="detail-label">Pilihan Ekskul</span>
        <span class="detail-colon">:</span>
        <span class="detail-value">
          <?php if ($ekskulList): ?>
            <?php foreach ($ekskulList as $e): ?>
              <span class="badge badge-ekskul"><?= htmlspecialchars($e) ?></span>
            <?php endforeach; ?>
          <?php else: ?>
            <span style="color:#9ca3af">-</span>
          <?php endif; ?>
        </span>
      </div>

      <hr style="margin-top:14px;" />

      <!-- Tombol kembali (semua level) -->
      <a href="index.php" class="button-back">&larr; Kembali ke Daftar</a>

      <!-- Tombol aksi admin -->
      <?php if ($isAdmin): ?>
        <p class="divider-label" style="margin-top:14px;">Aksi Admin</p>
        <div class="admin-actions">
          <a href="edit.php?nis=<?= urlencode($data["nis"]) ?>" class="button button-warning">&#9998; Edit</a>
          <a href="hapus.php?nis=<?= urlencode($data["nis"]) ?>"
            class="button button-danger"
            onclick="return confirm('Hapus data <?= htmlspecialchars(addslashes($data["nama"])) ?>?')">
            &#128465; Hapus
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>
