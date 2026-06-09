<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: index.php?page=dashboard");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #102a43, #38b2ac);
    }

    .login-card {
      max-width: 440px;
    }
  </style>
</head>

<body class="d-flex align-items-center">
  <div class="container">
    <div class="card login-card border-0 shadow-lg mx-auto">
      <div class="card-body p-4 p-md-5">
        <h2 class="fw-bold mb-2">Login User</h2>
        <p class="text-muted">Masuk sebagai admin atau user.</p>
        <div class="alert alert-info small">
          Admin: <strong>admin</strong> / <strong>admin</strong><br>
          User: <strong>user</strong> / <strong>user</strong>
        </div>
        <?php if (($_GET['pesan'] ?? '') === 'gagal'): ?>
          <div class="alert alert-danger">Username atau password salah.</div>
        <?php endif; ?>
        <?php if (($_GET['pesan'] ?? '') === 'logout'): ?>
          <div class="alert alert-success">Logout berhasil.</div>
        <?php endif; ?>
        <form action="proses_login.php" method="post">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100" type="submit" name="login">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>