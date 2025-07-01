<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM blood_pressure_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: records.php");
exit();
?>
