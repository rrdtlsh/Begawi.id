<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pelamar</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }

        .job-details {
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Laporan Daftar Pelamar</h1>

    <div class="job-details">
        <h3>Detail Lowongan</h3>
        <p><strong>Judul:</strong> <?= esc($job->title) ?></p>
        <p><strong>Perusahaan:</strong> <?= esc($job->company_name) ?></p>
        <p><strong>Lokasi:</strong> <?= esc($job->location_name) ?></p>
        <p><strong>Tipe Pekerjaan:</strong> <?= esc($job->job_type) ?></p>
    </div>

    <h3>Daftar Pelamar</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Pelamar</th>
                <th>Email</th>
                <th>Tanggal Melamar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($applicants)):
                foreach ($applicants as $index => $applicant): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($applicant->jobseeker_name) ?></td>
                        <td><?= esc($applicant->jobseeker_email) ?></td>
                        <td><?= date('d M Y', strtotime($applicant->applied_at)) ?></td>
                        <td><?= esc(ucfirst($applicant->status)) ?></td>
                    </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada pelamar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>