<!DOCTYPE html>
<html>

<head>
    <title>Update Mengenai Lamaran Kerja Anda</title>
</head>

<body
    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #2c3e50;">Selamat, <?= esc($jobseeker_name) ?>!</h2>
    <p>
        Kami dari Tim Begawi ingin menyampaikan kabar baik. Berdasarkan informasi dari
        <strong><?= esc($company_name) ?></strong>, kami dengan senang hati memberitahukan bahwa lamaran Anda untuk
        posisi:
    </p>
    <p style="font-size: 1.1em; font-weight: bold; color: #3498db;">
        <?= esc($job_title) ?>
    </p>
    <p>
        telah berhasil lolos tahap seleksi awal dan akan diproses ke tahap selanjutnya.
    </p>

    <h3>Langkah Berikutnya</h3>
    <p>
        Tim rekrutmen dari <strong><?= esc($company_name) ?></strong> akan segera menghubungi Anda secara langsung untuk
        memberikan informasi lebih lanjut, seperti jadwal wawancara atau tahapan tes berikutnya. Mohon pastikan kontak
        Anda (email dan telepon) selalu aktif.
    </p>
    <p>
        Sambil menunggu, ini adalah kesempatan yang baik untuk mempersiapkan diri dengan mencari tahu lebih dalam
        tentang perusahaan dan peran yang Anda lamar.
    </p>
    <br>
    <p>Kami turut senang atas pencapaian ini dan semoga Anda sukses di tahap selanjutnya!</p>
    <p>Hormat kami,</p>
    <p><strong>Tim Begawi</strong></p>
</body>

</html>