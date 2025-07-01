<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($id) {
    // Önce bu kullanıcıya ait olup olmadığını kontrol et
    $stmt = $pdo->prepare("SELECT * FROM blood_pressure_records WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $record = $stmt->fetch();

    if ($record) {
        // Silme işlemi
        $stmt = $pdo->prepare("DELETE FROM blood_pressure_records WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: records.php");
exit();
?>
