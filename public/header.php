<?php
session_start();
?>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Tansiyon Takip</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Menüyü Aç">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Ana Sayfa</a></li>
        <li class="nav-item"><a class="nav-link" href="add_record.php">Tansiyon Kaydı Ekle</a></li>
        <li class="nav-item"><a class="nav-link" href="records.php">Tansiyon Kayıtlarım</a></li>
	<li class="nav-item"><a class="nav-link" href="grafik.php">Grafikler</a></li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="admin.php">Kullanıcı Yönetimi</a></li>
        <?php endif; ?>
		<?php if (isset($_SESSION['user_id'])): ?>
	<li class="nav-item"><a class="nav-link" href="change_password.php">Şifremi Değiştir</a></li>
		<?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php">Çıkış Yap</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>