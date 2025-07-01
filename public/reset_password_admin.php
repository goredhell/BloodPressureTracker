<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';

$targetId = $_GET['id'] ?? null;
$currentId = $_SESSION['user_id'];

if ($targetId && $targetId != $currentId) {
    $defaultPassword = 'admin123'; // Varsayılan şifre
    $hashed = password_hash($defaultPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $targetId]);

    $_SESSION['flash_success'] = "Parola sıfırlandı (yeni parola: {$defaultPassword})";
} else {
    $_SESSION['flash_error'] = "Kendi şifrenizi buradan sıfırlayamazsınız.";
}

header("Location: admin.php");
exit;
