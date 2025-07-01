<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password'])) {
        $error = "Mevcut şifre yanlış.";
    } elseif (strlen($new) < 6) {
        $error = "Yeni şifre en az 6 karakter olmalı.";
    } elseif ($new !== $confirm) {
        $error = "Yeni şifre ile tekrar şifre uyuşmuyor.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $_SESSION['user_id']]);
        $success = "Şifreniz başarıyla güncellendi.";
    }
}
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Şifremi Değiştir</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-12">
            <label for="current_password" class="form-label">Mevcut Şifreniz</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="new_password" class="form-label">Yeni Şifre</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="confirm_password" class="form-label">Yeni Şifre (Tekrar)</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
        </div>
    </form>
</div>
