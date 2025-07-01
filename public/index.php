<?php
session_start();

// Eğer kullanıcı zaten giriş yapmışsa, dashboard sayfasına yönlendir
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tansiyon Kaydı Sistemi</title>
    <link rel="stylesheet" href="styles.css"> <!-- İsteğe bağlı stil dosyası -->
</head>
<body>
    <div class="container">
        <h1>Tansiyon Kaydı Sistemi</h1>
        <form action="login.php" method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Giriş Yap</button>
        </form>
        <p>Henüz bir hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
    </div>
</body>
</html>
