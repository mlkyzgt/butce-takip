<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Tüm işlemleri çek (gelir + gider)
$sql = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Gelir ve Gider Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f6;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gelir ve Giderler</h2>
        <div>
            <a href="index.php" class="btn btn-outline-secondary me-2">Ana Sayfa</a>
            <a href="create_transaction.php" class="btn btn-success">Yeni Kayıt Ekle</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered bg-white rounded shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Tür</th>
                    <th scope="col">Tutar (TL)</th>
                    <th scope="col">Açıklama</th>
                    <th scope="col">Tarih</th>
                    <th scope="col" style="width: 160px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($transactions): ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td>
                                <span class="badge <?= $t['type'] === 'gelir' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars(ucfirst($t['type'])) ?>
                                </span>
                            </td>
                            <td><?= number_format($t['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($t['description']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($t['created_at'])) ?></td>
                            <td>
                                <a href="edit_transaction.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                <a href="delete_transaction.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bu işlemi silmek istediğinizden emin misiniz?');">
                                    Sil
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Kayıt bulunamadı.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
