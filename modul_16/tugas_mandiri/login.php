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

$cekAdmin = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='admin'");
if (mysqli_num_rows($cekAdmin) === 0) {
  $hashAdmin = password_hash("1234", PASSWORD_DEFAULT);
  mysqli_query($conn, "INSERT INTO tb_users (username, password, level) VALUES ('admin', '$hashAdmin', 'admin')");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $password = trim($_POST["password"] ?? "");

  if (empty($username) || empty($password)) {
    $error = "Username dan password wajib diisi!";
  } else {
    $res = mysqli_query($conn, "SELECT * FROM tb_users WHERE username='" . mysqli_real_escape_string($conn, $username) . "'");
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($password, $user["password"])) {
      $_SESSION["login"] = true;
      $_SESSION["username"] = $user["username"];
      $_SESSION["level"] = $user["level"];
      header("Location: index.php");
      exit;
    } else {
      $error = "Username atau password salah!";
    }
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Login - Ekstrakurikuler</title>
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
      width: 320px;
    }

    .login-box h2 {
      text-align: center;
      color: #10b981;
      font-size: 16px;
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

    .hint {
      font-size: 11px;
      color: #9ca3af;
      text-align: center;
      margin-top: 12px;
    }

    .register-link {
      font-size: 12px;
      color: #6b7280;
      text-align: center;
      margin-top: 12px;
    }

    .register-link a {
      color: #10b981;
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <h2>Halaman Login</h2>

    <?php if ($error): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>" autofocus />
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" />
      </div>
      <button type="submit" class="button-login">MASUK</button>
    </form>

    <p class="hint">Admin: username <strong>admin</strong> | password <strong>1234</strong></p>
    <p class="register-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
  </div>
</body>

</html>