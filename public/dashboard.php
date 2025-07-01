<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php'; // Menü dosyasını dahil et

echo "<h1>Hoş Geldiniz</h1>";

// Kullanıcı rolüne göre menü seçenekleri
if ($_SESSION['role'] === 'admin') {
    echo "<h2>Admin Menüsü</h2>";
    echo "<a href='admin.php'>Kullanıcı Yönetimi</a><br>";
}

echo "<h2>Kullanıcı Menüsü</h2>";
echo "<a href='add_record.php'>Tansiyon Kaydı Ekle</a><br>";
echo "<a href='records.php'>Tansiyon Kayıtlarımı Görüntüle</a><br>";
echo "<a href='logout.php'>Çıkış Yap</a><br>";
?>
