<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Form Lamaran') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #0c392c;
    }

    .main-container {
        max-width: 800px;
        margin: 40px auto;
        background-color: #fff;
        border-radius: 15px;
        padding: 40px;
    }

    .job-summary {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }

    .form-label {
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="text-center mb-4">
            <a href="/"><img src="/logo-begawi.png" alt="Logo" style="height:40px;"></a>
            <h2 class="mt-2">Formulir Lamaran Pekerjaan</h2>
        </div>

        <!-- Ringkasan Pekerjaan -->
        <div class="job-summary mb-4">
            <h5>Anda melamar untuk posisi:</h5>
            <h4 class="font-weight-bold"><?= esc($job->title) ?></h4>
            <h6>di <?= esc($job->company_name) ?></h6>
        </div>

        <?php if (session()->get('errors')): ?>
        <div class="alert alert-danger">
            <strong>Gagal memvalidasi data:</strong>
            <ul>
                <?php foreach (session()->get('errors') as $error): ?>
                <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Form Lamaran -->
        <form action="/lamar/job/<?= $job->id ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h5 class="mt-4">Informasi Pelamar</h5>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value="<?= esc(session()->get('fullname')) ?>" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?= esc(session()->get('email')) ?>" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon (Opsional)</label>
                <input type="text" class="form-control" value="<?= esc($profile->phone ?? '') ?>" readonly>
                <small class="form-text text-muted">Nomor telepon diambil dari profil Anda. Anda bisa mengubahnya di
                    halaman Edit Profil.</small>
            </div>

            <h5 class="mt-4">Dokumen Lamaran</h5>
            <div class="form-group">
                <label for="cover_letter" class="form-label">Surat Lamaran (Cover Letter)</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="8"
                    placeholder="Tulis surat lamaran singkat yang menjelaskan mengapa Anda cocok untuk posisi ini..."
                    required><?= old('cover_letter') ?></textarea>
            </div>
            <div class="form-group">
                <label for="resume" class="form-label">Unggah CV/Resume Anda</label>
                <input type="file" name="resume" id="resume" class="form-control-file" required>
                <small class="form-text text-muted">Format: PDF, DOC, DOCX. Maksimal 2MB.</small>
            </div>
            <hr>
            <button type="submit" class="btn btn-success btn-lg btn-block">KIRIM LAMARAN</button>
            <a href="/lowongan/detail/<?= $job->id ?>" class="btn btn-secondary btn-block mt-2">Batal</a>
        </form>
    </div>
</body>

</html>