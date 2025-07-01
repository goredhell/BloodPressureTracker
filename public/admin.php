<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

// Kullanıcıları çek
$stmt = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Kullanıcı Yönetimi</h3>

    <?php if (count($users) === 0): ?>
        <div class="alert alert-info">Kayıtlı kullanıcı bulunamadı.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th scope="col">Kullanıcı Adı</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Kayıt Tarihi</th>
                        <th scope="col">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($user['role']) ?></td>
                            <td class="text-center"><?= date("d.m.Y H:i", strtotime($user['created_at'])) ?></td>
                            <td class="text-center">
                                <!-- İleride parola sıfırlama linki gibi işlemler buraya eklenebilir -->
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">Sil</a>
                                <?php else: ?>
                                    <span class="text-muted">(Siz)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
