<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
  header("Location: index.php");
  exit;
}

require "koneksi.php";

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tb_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  level ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = trim($_POST["password"] ?? "");
  $konfirmasi = trim($_POST["konfirmasi"] ?? "");
  $level = "user";

  if (empty($username) || empty($password) || empty($konfirmasi)) {
    $error = "Semua field wajib diisi!";
  } elseif (strlen($username) < 3) {
    $error = "Username minimal 3 karakter!";
  } elseif (strlen($password) < 4) {
    $error = "Password minimal 4 karakter!";
  } elseif ($password !== $konfirmasi) {
    $error = "Konfirmasi password tidak cocok!";
  } else {
    $cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='" . mysqli_real_escape_string($conn, $username) . "'");
    if (mysqli_num_rows($cek) > 0) {
      $error = "Username sudah digunakan, pilih username lain!";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO tb_users (username, password, level) VALUES (
        '" . mysqli_real_escape_string($conn, $username) . "',
        '" . mysqli_real_escape_string($conn, $hash) . "',
        'user'
      )";
      if (mysqli_query($conn, $sql)) {
        $success = "Registrasi berhasil! Silakan login.";
      } else {
        $error = "Gagal mendaftar: " . mysqli_error($conn);
      }
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Register - Ekstrakurikuler</title>
  <style>
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
    }

    .login-box {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 28px 32px;
      width: 340px;
    }

    .login-box h2 {
      text-align: center;
      color: #10b981;
      font-size: 16px;
      margin-bottom: 6px;
    }

    .login-box p.subtitle {
      text-align: center;
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 20px;
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 4px;
      margin-bottom: 12px;
    }

    .field label {
      font-size: 13px;
      color: #374151;
    }

    .field input {
      padding: 7px 10px;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      font-size: 13px;
      font-family: Arial, sans-serif;
    }

    .field input:focus {
      outline: none;
      border-color: #10b981;
    }

    .button-login {
      width: 100%;
      padding: 8px;
      background: #10b981;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 6px;
    }

    .button-login:hover {
      background: #059669;
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

    .alert-success {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #bbf7d0;
      padding: 8px 12px;
      border-radius: 4px;
      font-size: 13px;
      margin-bottom: 14px;
    }

    .hint {
      font-size: 12px;
      color: #6b7280;
      text-align: center;
      margin-top: 14px;
    }

    .hint a {
      color: #10b981;
      text-decoration: none;
      font-weight: 600;
    }

    .hint a:hover {
      text-decoration: underline;
    }

    .badge-level {
      display: inline-block;
      padding: 2px 8px;
      background: #dbeafe;
      color: #1e40af;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 6px;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <h2>Daftar Akun Baru</h2>
    <p class="subtitle">Pendaftaran Ekstrakurikuler Siswa</p>

    <?php if ($error): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
      <form method="POST" action="">
        <div class="field">
          <label>Username</label>
          <input type="text" name="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" autofocus
            placeholder="Minimal 3 karakter" />
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Minimal 4 karakter" />
        </div>
        <div class="field">
          <label>Konfirmasi Password</label>
          <input type="password" name="konfirmasi" placeholder="Ulangi password" />
        </div>
        <div class="field">
          <label>Level Akun <span class="badge-level">user</span></label>
          <input type="text" value="User (View Only)" readonly
            style="background:#f3f4f6;color:#6b7280;cursor:not-allowed;" />
        </div>
        <button type="submit" class="button-login">DAFTAR</button>
      </form>
    <?php endif; ?>

    <p class="hint">Sudah punya akun? <a href="login.php">Login di sini</a></p>
  </div>
</body>

</html>