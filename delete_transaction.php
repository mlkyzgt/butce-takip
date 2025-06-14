<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: list_transactions.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id, $user_id]);

header("Location: list_transactions.php");
exit;
