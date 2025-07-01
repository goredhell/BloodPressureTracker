<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php'; // Menü dosyasını dahil et

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM blood_pressure_records WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Tansiyon Kayıtlarım</h2>";
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " - Büyük Tansiyon: " . $row['systolic'] . " - Küçük Tansiyon: " . $row['diastolic'] . " - Nabız: " . $row['pulse'] . " - Tarih: " . $row['record_date'];
    echo " <a href='edit_record.php?id=" . $row['id'] . "'>Düzenle</a> | <a href='delete_record.php?id=" . $row['id'] . "'>Sil</a><br>";
}
?>
