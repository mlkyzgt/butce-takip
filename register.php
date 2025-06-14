<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = "Lütfen tüm alanları doldurun.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Geçerli bir e-posta girin.";
    } else {
        // E-posta veya kullanıcı adı daha önce kullanılmış mı kontrol et
        $checkSql = "SELECT COUNT(*) FROM users WHERE email = ? OR username = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$email, $username]);
        $existing = $checkStmt->fetchColumn();

        if ($existing > 0) {
            $message = "Bu e-posta veya kullanıcı adı zaten kullanılıyor.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([$username, $email, $password_hash]);
                $message = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } catch (PDOException $e) {
                $message = "Bir hata oluştu: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f7f8fc, #dbe4f0);
        }
        .card {
            border: none;
            border-radius: 1rem;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card shadow p-4" style="width: 100%; max-width: 420px;">
    <h2 class="text-center mb-4 fw-bold">Kayıt Ol</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" name="username" id="username" class="form-control form-control-lg rounded-3" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" name="email" id="email" class="form-control form-control-lg rounded-3" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Parola</label>
            <input type="password" name="password" id="password" class="form-control form-control-lg rounded-3" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">Kayıt Ol</button>
    </form>

    <p class="text-center mt-3">
        Zaten hesabınız var mı? <a href="login.php" class="text-decoration-none">Giriş Yap</a>
    </p>
</div>

</body>
</html>
