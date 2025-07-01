<?php
session_start();
?>

<nav>
    <ul>
        <li><a href="dashboard.php">Ana Sayfa</a></li>
        <li><a href="add_record.php">Tansiyon Kaydı Ekle</a></li>
        <li><a href="records.php">Tansiyon Kayıtlarım</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="admin.php">Kullanıcı Yönetimi</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Çıkış Yap</a></li>
    </ul>
</nav>
