<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php'; // Menü dosyasını dahil et

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $pulse = $_POST['pulse'];

    $stmt = $conn->prepare("UPDATE blood_pressure_records SET systolic = ?, diastolic = ?, pulse = ? WHERE id = ?");
    $stmt->bind_param("iiii", $systolic, $diastolic, $pulse, $id);
    $stmt->execute();

    echo "Kayıt başarıyla güncellendi.";
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM blood_pressure_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
    Büyük Tansiyon: <input type="number" name="systolic" value="<?php echo $record['systolic']; ?>" required>
    Küçük Tansiyon: <input type="number" name="diastolic" value="<?php echo $record['diastolic']; ?>" required>
    Nabız: <input type="number" name="pulse" value="<?php echo $record['pulse']; ?>" required>
    <button type="submit">Güncelle</button>
</form>
