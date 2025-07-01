<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'header.php';
?>

<div class="container">
    <div class="row mb-4">
        <div class="col text-center">
            <h2 class="text-primary">Hoş geldiniz!</h2>
            <p class="lead">Tansiyon takibinize buradan devam edebilirsiniz.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Tansiyon Kaydı Ekle</h5>
                    <p class="card-text">Yeni bir tansiyon verisi girmek için buraya tıklayın.</p>
                    <a href="add_record.php" class="btn btn-primary">Ekle</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Kayıtlarım</h5>
                    <p class="card-text">Tüm tansiyon ölçümlerini görüntüleyin ve yönetin.</p>
                    <a href="records.php" class="btn btn-outline-primary">Kayıtları Gör</a>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Kullanıcı Yönetimi</h5>
                    <p class="card-text">Kullanıcı hesaplarını yönetin.</p>
                    <a href="admin.php" class="btn btn-outline-secondary">Yönet</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
