<?php
session_start();
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Lütfen kullanıcı adı ve şifreyi giriniz.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre yanlış!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: linear-gradient(to right, #f7f8fc, #dbe4f0);
    }
    .card {
        border: none;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
  <div class="card shadow p-4 rounded-4" style="width: 100%; max-width: 400px;">
    <h2 class="text-center mb-4 fw-semibold">Giriş Yap</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Kullanıcı Adı</label>
        <input type="text" class="form-control form-control-lg rounded-3" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <input type="password" class="form-control form-control-lg rounded-3" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 rounded-3">Giriş</button>
    </form>
    <p class="mt-3 text-center">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
  </div>
</body>
</html>
