<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Laporan Statistik Aplikasi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Total Pengguna</h4>
                    <h2 class="text-white"><?= esc($total_users) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Total Vendor</h4>
                    <h2 class="text-white"><?= esc($total_vendors) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Total Pencari Kerja</h4>
                    <h2 class="text-white"><?= esc($total_jobseekers) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Lowongan Aktif</h4>
                    <h2 class="text-white"><?= esc($total_active_jobs) ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Grafik Distribusi Pengguna</h4>
                    <canvas id="userDistributionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chartData = {
            labels: ['Vendor (Perusahaan)', 'Pencari Kerja'],
            datasets: [{
                label: 'Jumlah Pengguna',
                data: [
                    <?= esc($total_vendors) ?>,
                    <?= esc($total_jobseekers) ?>
                ],
                backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(0, 123, 255, 0.7)'],
                borderColor: ['rgba(40, 167, 69, 1)', 'rgba(0, 123, 255, 1)'],
                borderWidth: 1
            }]
        };

        const chartConfig = {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: { legend: { display: false } }
            }
        };

        const ctx = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(ctx, chartConfig);
    });
</script>
<?= $this->endSection() ?>