<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Menambahkan beberapa penyesuaian kecil untuk tema default */
    .card .card-icon {
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }
    .card:hover .card-icon {
        opacity: 0.6;
    }
    
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 glitched-title">Dashboard</h1>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Vendor</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_vendors) ?></div>
                    </div>
                    <div class="col-auto card-icon">
                        <i class="fas fa-store fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pencari Kerja</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_jobseekers) ?></div>
                    </div>
                    <div class="col-auto card-icon">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Lowongan Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_active_jobs) ?></div>
                    </div>
                    <div class="col-auto card-icon">
                        <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pelatihan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_trainings) ?></div>
                    </div>
                    <div class="col-auto card-icon">
                        <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Perbandingan Pengguna</h6>
            </div>
            <div class="card-body">
                <canvas id="userComparisonChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Grafik Perbandingan Konten</h6>
            </div>
            <div class="card-body">
                <canvas id="contentComparisonChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let userChartInstance = null;
    let contentChartInstance = null;

    function getChartColors() {
        const isCyberpunk = document.body.classList.contains('cyberpunk-theme');
        if (isCyberpunk) {
            return {
                gridColor: 'rgba(142, 45, 226, 0.3)',
                ticksColor: '#c0c0ff',
                userColors: {
                    bg: ['rgba(57, 255, 20, 0.7)', 'rgba(0, 255, 249, 0.7)'],
                    border: ['#39ff14', '#00fff9']
                },
                contentColors: {
                    bg: ['rgba(255, 0, 193, 0.7)', 'rgba(249, 240, 2, 0.7)'],
                    border: ['#ff00c1', '#f9f002']
                }
            };
        } else {
            return {
                gridColor: 'rgba(0, 0, 0, 0.1)',
                ticksColor: '#666',
                userColors: {
                    bg: ['rgba(28, 200, 138, 0.7)', 'rgba(54, 185, 204, 0.7)'],
                    border: ['rgba(28, 200, 138, 1)', 'rgba(54, 185, 204, 1)']
                },
                contentColors: {
                    bg: ['rgba(78, 115, 223, 0.7)', 'rgba(246, 194, 62, 0.7)'],
                    border: ['rgba(78, 115, 223, 1)', 'rgba(246, 194, 62, 1)']
                }
            };
        }
    }

    function initCharts() {
        if (userChartInstance) userChartInstance.destroy();
        if (contentChartInstance) contentChartInstance.destroy();

        const colors = getChartColors();
        
        const userCtx = document.getElementById('userComparisonChart').getContext('2d');
        userChartInstance = new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: ['Vendor', 'Pencari Kerja'],
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: [<?= esc($total_vendors) ?>, <?= esc($total_jobseekers) ?>],
                    backgroundColor: colors.userColors.bg,
                    borderColor: colors.userColors.border,
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y', maintainAspectRatio: true,
                scales: { 
                    x: { beginAtZero: true, ticks: { precision: 0, color: colors.ticksColor }, grid: { color: colors.gridColor }},
                    y: { ticks: { color: colors.ticksColor }, grid: { color: colors.gridColor }}
                },
                plugins: { legend: { display: false } }
            }
        });

        const contentCtx = document.getElementById('contentComparisonChart').getContext('2d');
        contentChartInstance = new Chart(contentCtx, {
            type: 'bar',
            data: {
                labels: ['Lowongan Aktif', 'Total Pelatihan'],
                datasets: [{
                    label: 'Jumlah Konten',
                    data: [<?= esc($total_active_jobs) ?>, <?= esc($total_trainings) ?>],
                    backgroundColor: colors.contentColors.bg,
                    borderColor: colors.contentColors.border,
                    borderWidth: 2
                }]
            },
            options: {
                indexAxis: 'y', maintainAspectRatio: true,
                scales: { 
                    x: { beginAtZero: true, ticks: { precision: 0, color: colors.ticksColor }, grid: { color: colors.gridColor }},
                    y: { ticks: { color: colors.ticksColor }, grid: { color: colors.gridColor }}
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    window.updateCharts = initCharts;
    initCharts();
});
</script>
<?= $this->endSection() ?>