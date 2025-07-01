<?php
session_start();
require 'db_connection.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Kullanıcı adı veya şifre hatalı.";
    }
}
?>

<form method="POST">
    Kullanıcı Adı: <input type="text" name="username" required>
    Parola: <input type="password" name="password" required>
    <button type="submit">Giriş Yap</button>
</form>
