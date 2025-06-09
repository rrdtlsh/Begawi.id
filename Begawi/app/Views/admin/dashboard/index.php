<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pengguna</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_users) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Vendor</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_vendors) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pencari Kerja</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_jobseekers) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Lowongan Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_active_jobs) ?></div>
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

        // --- GRAFIK 1: PERBANDINGAN PENGGUNA (VENDOR VS JOBSEEKER) ---
        const ctxUsers = document.getElementById('userComparisonChart').getContext('2d');
        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: ['Vendor', 'Pencari Kerja'],
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: [
                        <?= esc($total_vendors) ?>,
                        <?= esc($total_jobseekers) ?>
                    ],
                    backgroundColor: [
                        'rgba(28, 200, 138, 0.7)',  // Hijau
                        'rgba(54, 185, 204, 0.7)'   // Biru-Muda
                    ],
                    borderColor: [
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Membuat bar menjadi horizontal agar lebih mudah dibaca
                maintainAspectRatio: true,
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                plugins: { legend: { display: false } }
            }
        });


        // --- GRAFIK 2: PERBANDINGAN KONTEN (JOBS VS TRAININGS) ---
        const ctxContent = document.getElementById('contentComparisonChart').getContext('2d');
        new Chart(ctxContent, {
            type: 'bar',
            data: {
                labels: ['Lowongan Aktif', 'Total Pelatihan'],
                datasets: [{
                    label: 'Jumlah Konten',
                    data: [
                        <?= esc($total_active_jobs) ?>,
                        <?= esc($total_trainings) ?>
                    ],
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.7)',  // Biru
                        'rgba(246, 194, 62, 0.7)'   // Kuning
                    ],
                    borderColoAr: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(246, 194, 62, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Membuat bar menjadi horizontal
                maintainAspectRatio: true,
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
                plugins: { legend: { display: false } }
            }
        });

    });
</script>
<?= $this->endSection() ?>