<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

//// DEBUG: Oturum bilgisi göster
//echo "<div class='container'><div class='alert alert-info'>";
//echo "<strong>DEBUG:</strong><br>";
//echo "Aktif Kullanıcı ID: " . ($_SESSION['user_id'] ?? 'yok') . "<br>";
//echo "</div></div>";

// Veritabanından kayıtları çek
$stmt = $pdo->prepare("SELECT * FROM blood_pressure_records  WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$records = $stmt->fetchAll();
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Tansiyon Kayıtlarım</h3>

    <?php if (count($records) === 0): ?>
        <div class="alert alert-warning">
            Henüz kayıt bulunamadı.
            <br><small><strong>DEBUG:</strong> Sorgu sonucunda 0 kayıt geldi.</small>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col">Tarih</th>
                        <th scope="col">Sistolik</th>
                        <th scope="col">Diyastolik</th>
                        <th scope="col">Nabız</th>
                        <th scope="col">Notlar</th>
                        <th scope="col">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= date("d.m.Y H:i", strtotime($record['record_date'] ?? $record['created_at'] ?? '')) ?></td>
                            <td class="text-center"><?= htmlspecialchars($record['systolic']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($record['diastolic']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($record['pulse']) ?></td>
                            <td><?= nl2br(htmlspecialchars($record['notes'])) ?></td>
                            <td class="text-center">
                                <a href="edit_record.php?id=<?= $record['id'] ?>" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                <a href="delete_record.php?id=<?= $record['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
