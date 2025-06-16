<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Peserta Pelatihan</title>
    <style>
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    .table th {
        background-color: #f2f2f2;
    }

    h1,
    h2 {
        text-align: center;
    }
    </style>
</head>

<body>
    <h1>Laporan Daftar Peserta</h1>
    <h2>Untuk Pelatihan: <?= esc($training->title) ?></h2>
    <hr>
    <p>Dicetak pada: <?= date('d F Y, H:i') ?></p>

    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Tanggal Mendaftar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($participants)): ?>
            <?php foreach ($participants as $index => $participant): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc($participant->jobseeker_name ?? 'N/A') ?></td>
                <td><?= esc($participant->jobseeker_email ?? 'N/A') ?></td>
                <td><?= date('d M Y', strtotime($participant->enrolled_at ?? time())) ?></td>
                <td><?= esc(ucfirst($participant->status ?? 'N/A')) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada peserta yang mendaftar.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>