<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

$id = $_GET['id'] ?? '';
$error = '';
$success = '';

// ID kontrolü
$stmt = $pdo->prepare("SELECT * FROM blood_pressure_records WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$record = $stmt->fetch();

if (!$record) {
    $error = 'Kayıt bulunamadı veya bu kayda erişim yetkiniz yok.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $systolic = $_POST['systolic'] ?? '';
    $diastolic = $_POST['diastolic'] ?? '';
    $pulse = $_POST['pulse'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($systolic && $diastolic && $pulse) {
        $stmt = $pdo->prepare("UPDATE blood_pressure_records SET systolic = ?, diastolic = ?, pulse = ?, notes = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$systolic, $diastolic, $pulse, $notes, $id, $_SESSION['user_id']]);
        $success = 'Kayıt başarıyla güncellendi!';
        // Veriyi yeniden çekelim
        $stmt = $pdo->prepare("SELECT * FROM blood_pressure_records WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $record = $stmt->fetch();
    } else {
        $error = 'Lütfen tüm zorunlu alanları doldurun.';
    }
}
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Tansiyon Kaydını Düzenle</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($record): ?>
    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label for="systolic" class="form-label">Büyük Tansiyon (Sistolik)</label>
            <input type="number" name="systolic" id="systolic" class="form-control" value="<?= htmlspecialchars($record['systolic']) ?>" required>
        </div>
        <div class="col-md-4">
            <label for="diastolic" class="form-label">Küçük Tansiyon (Diyastolik)</label>
            <input type="number" name="diastolic" id="diastolic" class="form-control" value="<?= htmlspecialchars($record['diastolic']) ?>" required>
        </div>
        <div class="col-md-4">
            <label for="pulse" class="form-label">Nabız</label>
            <input type="number" name="pulse" id="pulse" class="form-control" value="<?= htmlspecialchars($record['pulse']) ?>" required>
        </div>
        <div class="col-12">
            <label for="notes" class="form-label">Notlar</label>
            <textarea name="notes" id="notes" rows="3" class="form-control"><?= htmlspecialchars($record['notes']) ?></textarea>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">Kaydı Güncelle</button>
        </div>
    </form>
    <?php endif; ?>
</div>
