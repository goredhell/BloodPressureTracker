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

<!-- Histogramlar -->
<?php if (!empty($labels)): ?>
<hr class="my-5">
<h4 class="text-center text-secondary">Dağılım Grafikleri (Histogram)</h4>
<div class="row mt-4">
    <div class="col-md-4">
        <canvas id="histSistolik" height="120"></canvas>
    </div>
    <div class="col-md-4">
        <canvas id="histDiastolik" height="120"></canvas>
    </div>
    <div class="col-md-4">
        <canvas id="histPulse" height="120"></canvas>
    </div>
</div>
<?php endif; ?>

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

// Histogram hesaplaması için yardımcı fonksiyon
function calculateHistogram(data, step = 10) {
    const buckets = {};
    data.forEach(value => {
        const bucket = Math.floor(value / step) * step;
        buckets[bucket] = (buckets[bucket] || 0) + 1;
    });

    const labels = Object.keys(buckets).sort((a, b) => a - b);
    const counts = labels.map(label => buckets[label]);

    return { labels, counts };
}

// Histogram: Sistolik
const histS = calculateHistogram(<?= json_encode($systolicData) ?>, 10);
new Chart(document.getElementById('histSistolik').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histS.labels.map(l => l + "-" + (+l + 9)),
        datasets: [{
            label: 'Sistolik Dağılımı',
            data: histS.counts,
            backgroundColor: 'rgba(0, 123, 255, 0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Büyük Tansiyon (Sistolik)'
            }
        }
    }
});

// Histogram: Diastolik
const histD = calculateHistogram(<?= json_encode($diastolicData) ?>, 10);
new Chart(document.getElementById('histDiastolik').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histD.labels.map(l => l + "-" + (+l + 9)),
        datasets: [{
            label: 'Diastolik Dağılımı',
            data: histD.counts,
            backgroundColor: 'rgba(40, 167, 69, 0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Küçük Tansiyon (Diastolik)'
            }
        }
    }
});

// Histogram: Nabız
const histP = calculateHistogram(<?= json_encode($pulseData) ?>, 10);
new Chart(document.getElementById('histPulse').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histP.labels.map(l => l + "-" + (+l + 9)),
        datasets: [{
            label: 'Nabız Dağılımı',
            data: histP.counts,
            backgroundColor: 'rgba(255, 193, 7, 0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Nabız'
            }
        }
    }
});
</script>
