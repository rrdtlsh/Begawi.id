<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Formulir Lamaran') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/formulir.css">
</head>

<body>
    <div class="main-container">
        <div class="text-center mb-5">
            <h2 class="form-title"><?= isset($application) ? 'Edit Lamaran Pekerjaan' : 'Formulir Lamaran Pekerjaan' ?></h2>
        </div>

        <div class="job-summary mb-5">
            <p class="mb-1">Anda melamar untuk posisi:</p>
            <h3 class="job-title"><?= esc($job->title ?? 'Jasa Web Developer') ?></h3>
            <p class="company-name">di <?= esc($job->company_name ?? 'Jasa PT begawian') ?></p>
        </div>

        <?php if (function_exists('session') && session()->get('errors')): ?>
            <div class="alert alert-danger">
                <strong>Gagal memvalidasi data:</strong>
                <ul>
                    <?php foreach (session()->get('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($application) ? site_url('jobseeker/applications/update/' . esc($application->id)) : site_url('lamar/job/' . esc($job->id ?? '')) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h5 class="section-heading">Informasi Pelamar</h5>
            <div class="form-group">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" id="fullname" class="form-control"
                    value="<?= esc(session()->get('fullname') ?? '') ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" class="form-control" value="<?= esc(session()->get('email') ?? '') ?>"
                    readonly>
            </div>
            
            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" id="phone" class="form-control" 
                    value="<?= esc($profile->phone ?? '') ?>" 
                    placeholder="Nomor HP belum diisi di profil" readonly>
                
                <?php 
                if (empty($profile->phone)): ?>
                    <small class="form-text text-muted">
                        Nomor HP diambil dari detail profil, silahkan isi di halaman profil jika ingin ditampilkan.
                    </small>
                <?php endif; ?>
            </div>

            <h5 class="section-heading">Dokumen Lamaran</h5>
            <div class="form-group">
                <label for="cover_letter">Surat Lamaran (Cover Letter)</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="5"
                    placeholder="Sampaikan mengapa Anda tertarik dengan posisi ini..."
                    required><?= esc(old('cover_letter', $application->notes ?? '')) ?></textarea> </div>
            <div class="form-group">
                <label for="resume">Unggah CV / Resume Anda</label>
                <div class="custom-file">
                    <input type="file" name="resume" class="custom-file-input" id="resume" <?= isset($application) && !empty($application->resume_file_path) ? '' : 'required' ?>>
                    <label class="custom-file-label" for="resume">Choose file</label>
                </div>
                <small class="form-text text-muted">Format: PDF, Doc, Docx. Maksimal 2MB.
                    <?php if (isset($application) && !empty($application->resume_file_path)): ?>
                        <br>CV saat ini: <a href="<?= base_url('uploads/resumes/' . esc($application->resume_file_path)) ?>" target="_blank">Lihat CV</a>
                    <?php endif; ?>
                </small>
            </div>

            <button type="submit" class="btn btn-submit btn-block mt-4">
                <?= isset($application) ? 'Simpan Perubahan' : 'Kirim Lamaran' ?>
            </button>
            <a href="<?= isset($application) ? site_url('jobseeker/history') : site_url('lowongan/detail/' . ($job->id ?? '#')) ?>" class="btn btn-cancel btn-block mt-2">Batal</a>
        </form>
    </div>

    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function (e) {
            var fileName = document.getElementById("resume").files[0].name;
            var nextSibling = e.target.nextElementSibling
            nextSibling.innerText = fileName
        })

        <?php if (isset($application) && !empty($application->resume_file_path)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var currentResumePath = "<?= esc($application->resume_file_path) ?>";
                var fileName = currentResumePath.substring(currentResumePath.lastIndexOf('/') + 1);
                document.querySelector('.custom-file-label').innerText = fileName;
            });
        <?php endif; ?>
    </script>
</body>

</html>