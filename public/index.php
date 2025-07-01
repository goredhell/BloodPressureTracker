<?php
session_start();

// Giriş yapılmışsa doğrudan dashboard'a git
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tansiyon Takip Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-5 text-primary mb-3">Tansiyon Takip Sistemi</h1>
            <p class="lead mb-4">
                Günlük tansiyon ölçümlerinizi kaydedin ve geçmiş verilerinizi takip edin.
            </p>
            <a href="login.php" class="btn btn-primary btn-lg">Giriş Yap</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
