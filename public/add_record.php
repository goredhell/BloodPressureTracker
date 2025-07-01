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
    $systolic = $_POST['systolic'] ?? '';
    $diastolic = $_POST['diastolic'] ?? '';
    $pulse = $_POST['pulse'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($systolic && $diastolic && $pulse) {
        $stmt = $pdo->prepare("INSERT INTO blood_pressure_records (user_id, systolic, diastolic, pulse, notes, record_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $systolic, $diastolic, $pulse, $notes]);
        $success = 'Kayıt başarıyla eklendi!';
    } else {
        $error = 'Lütfen tüm zorunlu alanları doldurun.';
    }
}
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Tansiyon Kaydı Ekle</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label for="systolic" class="form-label">Büyük Tansiyon (Sistolik)</label>
            <input type="number" name="systolic" id="systolic" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="diastolic" class="form-label">Küçük Tansiyon (Diyastolik)</label>
            <input type="number" name="diastolic" id="diastolic" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="pulse" class="form-label">Nabız</label>
            <input type="number" name="pulse" id="pulse" class="form-control" required>
        </div>
        <div class="col-12">
            <label for="notes" class="form-label">Notlar (İsteğe Bağlı)</label>
            <textarea name="notes" id="notes" rows="3" class="form-control"></textarea>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Kaydı Ekle</button>
        </div>
    </form>
</div>
