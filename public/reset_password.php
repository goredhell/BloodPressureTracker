<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'header.php'; // Menü dosyasını dahil et

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password, $id);
    $stmt->execute();

    echo "Şifre başarıyla sıfırlandı.";
}

$id = $_GET['id'];
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    Yeni Şifre: <input type="password" name="new_password" required>
    <button type="submit">Şifreyi Sıfırla</button>
</form>
