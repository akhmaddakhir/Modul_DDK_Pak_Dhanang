<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: login.php");
  exit;
}
if (($_SESSION["level"] ?? "") !== "admin") {
  header("Location: index.php?msg=Akses ditolak! Hanya admin yang dapat mengedit data.");
  exit;
}

require "koneksi.php";
$error     = "";
$nis_param = $_GET["nis"] ?? "";

$res  = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nis='" . mysqli_real_escape_string($conn, $nis_param) . "'");
$data = mysqli_fetch_assoc($res);

if (!$data) {
  header("Location: index.php?msg=Data tidak ditemukan.");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama   = trim($_POST["nama"]);
  $kelas  = $_POST["kelas"];
  $tgl    = $_POST["tgl"];
  $bln    = $_POST["bln"];
  $thn    = $_POST["thn"];
  $alamat = trim($_POST["alamat"]);
  $kota   = trim($_POST["kota"]);
  $jk     = $_POST["jk"] ?? "";
  $hobi   = isset($_POST["hobi"])   ? implode(",", $_POST["hobi"])   : "";
  $ekskul = isset($_POST["ekskul"]) ? implode(",", $_POST["ekskul"]) : "";
  $ttl    = ($thn && $bln && $tgl) ? "$thn-$bln-$tgl" : null;

  $sql = "UPDATE tb_siswa SET
          nama   = '" . mysqli_real_escape_string($conn, $nama)   . "',
          kelas  = '" . mysqli_real_escape_string($conn, $kelas)  . "',
          ttl    = "  . ($ttl ? "'" . mysqli_real_escape_string($conn, $ttl) . "'" : "NULL") . ",
          alamat = '" . mysqli_real_escape_string($conn, $alamat) . "',
          kota   = '" . mysqli_real_escape_string($conn, $kota)   . "',
          jk     = '" . mysqli_real_escape_string($conn, $jk)     . "',
          hobi   = '" . mysqli_real_escape_string($conn, $hobi)   . "',
          ekskul = '" . mysqli_real_escape_string($conn, $ekskul) . "'
        WHERE nis = '" . mysqli_real_escape_string($conn, $nis_param) . "'";

  if (mysqli_query($conn, $sql)) {
    header("Location: index.php?msg=Data berhasil diperbarui");
    exit;
  } else {
    $error = "Gagal update: " . mysqli_error($conn);
    $data = [
      "nis"    => $nis_param,
      "nama"   => $_POST["nama"]   ?? "",
      "kelas"  => $_POST["kelas"]  ?? "",
      "ttl"    => "",
      "tgl"    => $_POST["tgl"]    ?? "",
      "bln"    => $_POST["bln"]    ?? "",
      "thn"    => $_POST["thn"]    ?? "",
      "alamat" => $_POST["alamat"] ?? "",
      "kota"   => $_POST["kota"]   ?? "",
      "jk"     => $_POST["jk"]     ?? "",
      "hobi"   => isset($_POST["hobi"])   ? implode(",", $_POST["hobi"])   : "",
      "ekskul" => isset($_POST["ekskul"]) ? implode(",", $_POST["ekskul"]) : "",
    ];
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Edit Siswa</title>
  <style>
    /* ===== Base — identik dengan login.php ===== */
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
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 32px 20px;
    }

    /* ===== Topbar ===== */
    .topbar {
      width: 520px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
      font-size: 13px;
      color: #6b7280;
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

    /* ===== Card — sama seperti login-box ===== */
    .form-card {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 28px 32px;
      width: 520px;
    }

    .form-card h2 {
      text-align: center;
      color: #10b981;
      font-size: 16px;
      margin-bottom: 6px;
    }

    .form-card .subtitle {
      text-align: center;
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 16px;
    }

    hr {
      border: none;
      border-top: 1px solid #e5e7eb;
      margin-bottom: 14px;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 12px;
      font-size: 13px;
      color: #10b981;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
      padding: 8px 12px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 14px;
    }

    /* ===== Form elements ===== */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    td {
      padding: 5px 4px;
      vertical-align: top;
    }

    td:first-child { width: 120px; }
    td:nth-child(2) { width: 12px; }

    input[type="text"],
    input[type="email"],
    select,
    textarea {
      border: 1px solid #d1d5db;
      padding: 4px 7px;
      font-size: 13px;
      border-radius: 3px;
      font-family: Arial, sans-serif;
    }

    input[type="text"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #10b981;
    }

    input[readonly] {
      background: #f3f4f6;
      color: #6b7280;
      cursor: not-allowed;
    }

    .input-wide    { width: 260px; }
    .input-small   { width: 70px; }
    .date-input    { width: 46px; }
    .year-input    { width: 60px; }
    .input-listbox { width: 150px; }
    textarea       { resize: vertical; }

    .date-row  { display: flex; align-items: center; gap: 4px; }
    .radio-row { display: flex; gap: 14px; }
    .radio-row label { font-size: 13px; }

    .required { color: red; margin-left: 3px; }
    .note     { font-size: 12px; color: red; margin-top: 8px; }

    /* ===== Buttons ===== */
    .button-row {
      margin-top: 16px;
      display: flex;
      gap: 8px;
    }

    .button {
      display: inline-block;
      padding: 7px 14px;
      font-size: 13px;
      font-weight: 600;
      border: 1px solid transparent;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
    }

    .button-submit {
      flex: 1;
      background: #10b981;
      color: #fff;
      border: none;
      font-size: 14px;
    }

    .button-submit:hover { background: #059669; }

    .button-light {
      background: #f3f4f6;
      color: #374151;
      border: 1px solid #d1d5db;
      padding: 7px 20px;
    }

    .button-light:hover { background: #e5e7eb; }

    .button-logout {
      background: #ef4444;
      color: #fff;
      padding: 5px 12px;
      font-size: 12px;
    }

    .button-logout:hover { background: #dc2626; }
  </style>
</head>

<body>
  <!-- Topbar -->
  <div class="topbar">
    <span>Halo, <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></span>
    <span class="badge-level-admin">Admin</span>
    <a href="logout.php" class="button button-logout">Logout</a>
  </div>

  <!-- Card -->
  <div class="form-card">
    <a href="index.php" class="back-link">&larr; Kembali</a>

    <?php if ($error): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h2>Edit Data Siswa</h2>
    <p class="subtitle">NIS: <strong><?= htmlspecialchars($nis_param) ?></strong></p>
    <hr />

    <form method="POST" action="">
      <?php
      $readonly = "readonly";   // NIS tidak bisa diubah
      include "form.php";
      ?>
    </form>
  </div>
</body>

</html>
