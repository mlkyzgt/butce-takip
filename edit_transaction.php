<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

if (!isset($_GET['id'])) {
    header("Location: list_transactions.php");
    exit;
}

$id = intval($_GET['id']);

// Kayıt var mı ve kullanıcıya ait mi kontrol et
$sql = "SELECT * FROM transactions WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $user_id]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    header("Location: list_transactions.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);

    if ($amount <= 0 || empty($description)) {
        $message = "Lütfen geçerli bir tutar ve açıklama girin.";
    } else {
        $sql = "UPDATE transactions SET type = ?, amount = ?, description = ? WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$type, $amount, $description, $id, $user_id])) {
            $message = "Kayıt başarıyla güncellendi.";
            $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $message = "Güncelleme sırasında hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Gelir/Gider Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f1f3f6, #e2eafc);
        }
        .card {
            max-width: 600px;
            margin: auto;
            margin-top: 60px;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }
    </style>
</head>
<body>

<div class="card p-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Gelir / Gider Düzenle</h3>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="type" class="form-label">Tür</label>
            <select name="type" id="type" class="form-select" required>
                <option value="gelir" <?= $transaction['type'] === 'gelir' ? 'selected' : '' ?>>Gelir</option>
                <option value="gider" <?= $transaction['type'] === 'gider' ? 'selected' : '' ?>>Gider</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Tutar (TL)</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required min="0.01" value="<?= htmlspecialchars($transaction['amount']) ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($transaction['description']) ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="list_transactions.php" class="btn btn-outline-secondary">Geri Dön</a>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
    </form>
</div>

</body>
</html>
