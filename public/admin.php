<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Yeni kullanıcı ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    echo "Kullanıcı başarıyla eklendi.";
}

// Mevcut kullanıcıları listeleme
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Kullanıcı Yönetimi</h2>";
echo "<form method='POST'>";
echo "Yeni Kullanıcı Adı: <input type='text' name='username' required>";
echo "Şifre: <input type='password' name='password' required>";
echo "<button type='submit'>Kullanıcı Ekle</button>";
echo "</form>";

echo "<h3>Mevcut Kullanıcılar</h3>";
while ($row = $result->fetch_assoc()) {
    echo "Kullanıcı Adı: " . $row['username'] . " - Rol: " . $row['role'];
    echo " <a href='reset_password.php?id=" . $row['id'] . "'>Şifre Sıfırla</a><br>";
}
?>
