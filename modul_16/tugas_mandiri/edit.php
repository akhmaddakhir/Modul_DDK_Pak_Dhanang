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
$error = "";
$nis_param = $_GET["nis"] ?? "";

$res = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nis='" . mysqli_real_escape_string($conn, $nis_param) . "'");
$data = mysqli_fetch_assoc($res);

if (!$data) {
  header("HTTP/1.0 404 Not Found");
  echo "Data tidak ditemukan.";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = trim($_POST["nama"]);
  $kelas = $_POST["kelas"];
  $tgl = $_POST["tgl"];
  $bln = $_POST["bln"];
  $thn = $_POST["thn"];
  $alamat = trim($_POST["alamat"]);
  $kota = trim($_POST["kota"]);
  $jk = $_POST["jk"] ?? "";
  $hobi = isset($_POST["hobi"]) ? implode(",", $_POST["hobi"]) : "";
  $ekskul = isset($_POST["ekskul"]) ? implode(",", $_POST["ekskul"]) : "";
  $ttl = ($thn && $bln && $tgl) ? "$thn-$bln-$tgl" : null;

  $sql = "UPDATE tb_siswa SET
          nama   = '" . mysqli_real_escape_string($conn, $nama) . "',
          kelas  = '" . mysqli_real_escape_string($conn, $kelas) . "',
          ttl    = " . ($ttl ? "'" . mysqli_real_escape_string($conn, $ttl) . "'" : "NULL") . ",
          alamat = '" . mysqli_real_escape_string($conn, $alamat) . "',
          kota   = '" . mysqli_real_escape_string($conn, $kota) . "',
          jk     = '" . mysqli_real_escape_string($conn, $jk) . "',
          hobi   = '" . mysqli_real_escape_string($conn, $hobi) . "',
          ekskul = '" . mysqli_real_escape_string($conn, $ekskul) . "'
        WHERE nis = '" . mysqli_real_escape_string($conn, $nis_param) . "'";

  if (mysqli_query($conn, $sql)) {
    header("Location: index.php?msg=Data berhasil diperbarui");
    exit;
  } else {
    $error = "Gagal update: " . mysqli_error($conn);
    $data = [
      "nis" => $_POST["nis"] ?? "",
      "nama" => $_POST["nama"] ?? "",
      "kelas" => $_POST["kelas"] ?? "",
      "ttl" => "",
      "tgl" => $_POST["tgl"] ?? "",
      "bln" => $_POST["bln"] ?? "",
      "thn" => $_POST["thn"] ?? "",
      "alamat" => $_POST["alamat"] ?? "",
      "kota" => $_POST["kota"] ?? "",
      "jk" => $_POST["jk"] ?? "",
      "hobi" => isset($_POST["hobi"]) ? implode(",", $_POST["hobi"]) : "",
      "ekskul" => isset($_POST["ekskul"]) ? implode(",", $_POST["ekskul"]) : ""
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
      max-width: 520px;
      margin: 0 auto;
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

    .button-secondary {
      background: #6b7280;
      color: #fff;
    }

    .button-secondary:hover {
      background: #4b5563;
    }

    .button-light {
      background: #f3f4f6;
      color: #374151;
      border: 1px solid #d1d5db;
    }

    .button-light:hover {
      background: #e5e7eb;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 16px;
      font-size: 13px;
      color: #10b981;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .alert {
      padding: 10px 14px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 14px;
    }

    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    .form-box {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 20px 24px;
    }

    .form-box h2 {
      text-align: center;
      color: #10b981;
      font-size: 16px;
      margin-bottom: 8px;
    }

    hr {
      border: none;
      border-top: 1px solid #e5e7eb;
      margin-bottom: 14px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    td {
      padding: 5px 4px;
      vertical-align: top;
    }

    td:first-child {
      width: 120px;
    }

    td:nth-child(2) {
      width: 12px;
    }

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

    input[readonly] {
      background: #f3f4f6;
      color: #6b7280;
      cursor: not-allowed;
    }

    .input-wide {
      width: 260px;
    }

    .input-small {
      width: 70px;
    }

    .date-input {
      width: 46px;
    }

    .year-input {
      width: 60px;
    }

    .input-listbox {
      width: 150px;
    }

    textarea {
      resize: vertical;
    }

    .date-row {
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .radio-row {
      display: flex;
      gap: 14px;
    }

    .radio-row label,
    .checkbox-group label {
      font-size: 13px;
    }

    .required {
      color: red;
      margin-left: 3px;
    }

    .button-row {
      margin-top: 14px;
      display: flex;
      gap: 8px;
    }

    .note {
      font-size: 12px;
      color: red;
      margin-top: 8px;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <a href="index.php" class="back-link">&larr; Kembali</a>

    <?php if ($error): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <?php $readonly = "readonly";
      include "form.php"; ?>
    </form>
  </div>
</body>

</html>