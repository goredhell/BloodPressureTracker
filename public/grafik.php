<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

// Verileri al
$stmt = $pdo->prepare("SELECT record_date, systolic, diastolic, pulse FROM blood_pressure_records WHERE user_id = ? ORDER BY record_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetchAll();

// Verileri grafik için hazırla
$labels = [];
$systolicData = [];
$diastolicData = [];
$pulseData = [];

foreach ($data as $row) {
    $labels[] = date("d.m H:i", strtotime($row['record_date']));
    $systolicData[] = $row['systolic'];
    $diastolicData[] = $row['diastolic'];
    $pulseData[] = $row['pulse'];
}
?>

<div class="container">
    <h3 class="text-center text-primary mb-4">Tansiyon ve Nabız Grafiği</h3>
    <?php if (empty($labels)): ?>
        <div class="alert alert-warning">Görüntülenecek kayıt bulunamadı.</div>
    <?php else: ?>
        <canvas id="tansiyonChart" height="120"></canvas>
    <?php endif; ?>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('tansiyonChart').getContext('2d');

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Sistolik',
                data: <?= json_encode($systolicData) ?>,
                borderColor: 'rgba(0, 123, 255, 1)',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Diyastolik',
                data: <?= json_encode($diastolicData) ?>,
                borderColor: 'rgba(40, 167, 69, 1)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Nabız',
                data: <?= json_encode($pulseData) ?>,
                borderColor: 'rgba(255, 193, 7, 1)',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        stacked: false,
        plugins: {
            title: {
                display: true,
                text: 'Zamanla Tansiyon ve Nabız Değerleri'
            }
        },
        scales: {
            y: {
                title: {
                    display: true,
                    text: 'Değer'
                }
            },
            x: {
                ticks: {
                    maxRotation: 90,
                    minRotation: 45
                }
            }
        }
    }
});
</script>
