<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Gelir - Gider - Bakiye
$sqlGelir = "SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'gelir'";
$stmtGelir = $pdo->prepare($sqlGelir);
$stmtGelir->execute([$user_id]);
$toplamGelir = $stmtGelir->fetchColumn() ?? 0;

$sqlGider = "SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'gider'";
$stmtGider = $pdo->prepare($sqlGider);
$stmtGider->execute([$user_id]);
$toplamGider = $stmtGider->fetchColumn() ?? 0;

$bakiye = $toplamGelir - $toplamGider;

// Son 5 işlem
$sqlSonIslemler = "SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $pdo->prepare($sqlSonIslemler);
$stmt->execute([$user_id]);
$sonIslemler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ana Sayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border: none;
            border-radius: 1rem;
        }
        .card-header {
            font-weight: bold;
        }
        .btn {
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold">Hoşgeldin, <?= htmlspecialchars($username) ?></h2>
        <p class="text-muted">Finansal özetin aşağıda listelenmiştir.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-header">Toplam Gelir</div>
                <div class="card-body">
                    <h3 class="card-title"><?= number_format($toplamGelir, 2) ?> TL</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-header">Toplam Gider</div>
                <div class="card-body">
                    <h3 class="card-title"><?= number_format($toplamGider, 2) ?> TL</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-header">Bakiye</div>
                <div class="card-body">
                    <h3 class="card-title"><?= number_format($bakiye, 2) ?> TL</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h4>Son 5 İşlem</h4>
        <?php if ($sonIslemler): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered bg-white mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Tarih</th>
                            <th>Tür</th>
                            <th>Tutar</th>
                            <th>Açıklama</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sonIslemler as $islem): ?>
                            <tr>
                                <td><?= date('d.m.Y H:i', strtotime($islem['created_at'])) ?></td>
                                <td>
                                    <span class="badge <?= $islem['type'] === 'gelir' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= htmlspecialchars(ucfirst($islem['type'])) ?>
                                    </span>
                                </td>
                                <td><?= number_format($islem['amount'], 2) ?> TL</td>
                                <td><?= htmlspecialchars($islem['description']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Henüz işlem kaydınız yok.</p>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="create_transaction.php" class="btn btn-success">+ Yeni Kayıt</a>
        <a href="list_transactions.php" class="btn btn-outline-primary">Listeye Git</a>
        <a href="logout.php" class="btn btn-outline-danger ms-auto">Çıkış Yap</a>
    </div>
</div>
</body>
</html>
