<?php
session_start();
require 'config.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    if ($amount <= 0 || empty($description)) {
        $message = "Lütfen geçerli bir tutar ve açıklama girin.";
    } else {
        $sql = "INSERT INTO transactions (user_id, type, amount, description) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$user_id, $type, $amount, $description])) {
            $message = "Kayıt başarılı!";
        } else {
            $message = "Kayıt sırasında hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Gelir/Gider Ekle</title>
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
<div class="card shadow p-4" style="width: 100%; max-width: 500px;">
    <h3 class="text-center mb-4 fw-bold">Yeni Gelir/Gider Ekle</h3>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="create_transaction.php">
        <div class="mb-3">
            <label for="type" class="form-label">Tür</label>
            <select name="type" id="type" class="form-select form-select-lg rounded-3" required>
                <option value="gelir">Gelir</option>
                <option value="gider">Gider</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Tutar (TL)</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control form-control-lg rounded-3" required placeholder="Örn: 100.50">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea name="description" id="description" class="form-control form-control-lg rounded-3" rows="3" required placeholder="Açıklama girin..."></textarea>
        </div>
        <div class="d-flex justify-content-between">
            <a href="list_transactions.php" class="btn btn-outline-secondary">Listeye Git</a>
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
    </form>
</div>
</body>
</html>
