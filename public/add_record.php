<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $pulse = $_POST['pulse'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO blood_pressure_records (user_id, systolic, diastolic, pulse) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $user_id, $systolic, $diastolic, $pulse);
    $stmt->execute();

    echo "Kayıt başarıyla eklendi.";
}
?>

<form method="POST">
    Büyük Tansiyon: <input type="number" name="systolic" required>
    Küçük Tansiyon: <input type="number" name="diastolic" required>
    Nabız: <input type="number" name="pulse" required>
    <button type="submit">Kaydet</button>
</form>
