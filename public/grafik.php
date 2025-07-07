<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php';
include 'header.php';

// Tarih aralığı filtreleme
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$whereSql = "user_id = ?";
$params = [$_SESSION['user_id']];

if ($start_date && $end_date) {
    $whereSql .= " AND record_date BETWEEN ? AND ?";
    $params[] = $start_date . " 00:00:00";
    $params[] = $end_date . " 23:59:59";
}

$stmt = $pdo->prepare("SELECT record_date, systolic, diastolic, pulse FROM blood_pressure_records WHERE $whereSql ORDER BY record_date ASC");
$stmt->execute($params);
$data = $stmt->fetchAll();

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
    <h3 class="text-center text-primary mb-3">Tansiyon ve Nabız Grafikleri</h3>

    <!-- Tarih filtresi -->
    <form method="get" class="row g-3 mb-4 align-items-end">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">Bitiş Tarihi</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
        </div>
        <div class="col-md-4 text-end">
            <button type="submit" class="btn btn-primary">Filtrele</button>
            <a href="grafik.php" class="btn btn-outline-secondary">Sıfırla</a>
        </div>
    </form>

    <?php if (empty($labels)): ?>
        <div class="alert alert-warning">Seçilen tarihlerde kayıt bulunamadı.</div>
    <?php else: ?>

    <!-- Zaman Serisi Grafikler -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h5 class="text-center">Tansiyon Zaman Grafiği</h5>
            <canvas id="bpChart" height="120"></canvas>
        </div>
        <div class="col-12 mb-4">
            <h5 class="text-center">Nabız Zaman Grafiği</h5>
            <canvas id="pulseChart" height="120"></canvas>
        </div>
    </div>

    <!-- Histogramlar -->
    <hr class="my-4">
    <h4 class="text-center text-secondary mb-3">Dağılım Grafikleri (Histogram)</h4>
    <div class="row gy-4">
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
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($labels) ?>;
const systolicData = <?= json_encode($systolicData) ?>;
const diastolicData = <?= json_encode($diastolicData) ?>;
const pulseData = <?= json_encode($pulseData) ?>;

// Zaman Grafiği: Tansiyon
new Chart(document.getElementById('bpChart').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label: 'Sistolik',
                data: systolicData,
                borderColor: 'rgba(0,123,255,1)',
                backgroundColor: 'rgba(0,123,255,0.1)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Diyastolik',
                data: diastolicData,
                borderColor: 'rgba(40,167,69,1)',
                backgroundColor: 'rgba(40,167,69,0.1)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { title: { display: true, text: 'mmHg' } },
            x: { ticks: { maxRotation: 90, minRotation: 45 } }
        }
    }
});

// Zaman Grafiği: Nabız
new Chart(document.getElementById('pulseChart').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Nabız',
            data: pulseData,
            borderColor: 'rgba(255,193,7,1)',
            backgroundColor: 'rgba(255,193,7,0.1)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { title: { display: true, text: 'BPM' } },
            x: { ticks: { maxRotation: 90, minRotation: 45 } }
        }
    }
});

// Histogram hesaplama fonksiyonu
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
const histS = calculateHistogram(systolicData, 10);
new Chart(document.getElementById('histSistolik').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histS.labels.map(l => l + '-' + (+l + 9)),
        datasets: [{
            label: 'Sistolik Dağılımı',
            data: histS.counts,
            backgroundColor: 'rgba(0,123,255,0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Büyük Tansiyon (Sistolik)' } }
    }
});

// Histogram: Diastolik
const histD = calculateHistogram(diastolicData, 10);
new Chart(document.getElementById('histDiastolik').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histD.labels.map(l => l + '-' + (+l + 9)),
        datasets: [{
            label: 'Diastolik Dağılımı',
            data: histD.counts,
            backgroundColor: 'rgba(40,167,69,0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Küçük Tansiyon (Diastolik)' } }
    }
});

// Histogram: Nabız
const histP = calculateHistogram(pulseData, 10);
new Chart(document.getElementById('histPulse').getContext('2d'), {
    type: 'bar',
    data: {
        labels: histP.labels.map(l => l + '-' + (+l + 9)),
        datasets: [{
            label: 'Nabız Dağılımı',
            data: histP.counts,
            backgroundColor: 'rgba(255,193,7,0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Nabız' } }
    }
});
</script>
