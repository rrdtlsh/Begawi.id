<!DOCTYPE html>
<html>

<head>
    <title>Kabar Baik Lamaran Anda</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6;">
    <h2>Selamat, <?= esc($jobseeker_name) ?>!</h2>
    <p>
        Kami ingin memberitahukan kabar baik bahwa lamaran Anda untuk posisi <strong><?= esc($job_title) ?></strong>
        di <strong><?= esc($company_name) ?></strong> telah diterima dan akan diproses ke tahap selanjutnya.
    </p>
    <p>
        Tim dari perusahaan akan segera menghubungi Anda untuk informasi lebih lanjut mengenai jadwal interview atau
        tahapan berikutnya.
    </p>
    <p>
        Terima kasih telah menggunakan platform Begawi. Semoga sukses!
    </p>
    <br>
    <p>Hormat kami,</p>
    <p><strong>Tim Begawi</strong></p>
</body>

</html>