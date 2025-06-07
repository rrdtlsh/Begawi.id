<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Lowongan Pekerjaan') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
    .search-card {
        background-color: #f8f9fa;
        border: none;
    }

    .job-card {
        border: 1px solid #e9ecef;
        transition: box-shadow 0.3s ease-in-out, transform 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        /* Perbaikan: Untuk posisi bookmark */
    }

    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .job-card .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .company-logo {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .bookmark-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #6c757d;
        text-decoration: none;
        z-index: 10;
    }

    .bookmark-btn .bi-bookmark-fill {
        color: #007bff;
    }
    </style>
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4"><?= esc($title) ?></h1>

        <!-- FORM PENCARIAN LENGKAP -->
        <div class="card search-card p-4 mb-5">
            <form action="/lowongan" method="post">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="keyword">Kata Kunci</label>
                        <input type="text" id="keyword" class="form-control" name="keyword"
                            placeholder="Judul pekerjaan..." value="<?= esc($old_input['keyword'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="location">Lokasi</label>
                        <select name="location" id="location" class="form-control">
                            <option value="">Semua Lokasi</option>
                            <?php if (!empty($locations)): foreach($locations as $loc): ?>
                            <option value="<?= $loc->id ?>"
                                <?= ($old_input['location'] ?? '') == $loc->id ? 'selected' : '' ?>>
                                <?= esc($loc->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            <?php if (!empty($categories)): foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"
                                <?= ($old_input['category'] ?? '') == $cat->id ? 'selected' : '' ?>>
                                <?= esc($cat->name) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">Cari</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DAFTAR LOWONGAN DENGAN CARD VIEW -->
        <div class="row">
            <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card job-card h-100">

                    <?php if (session()->get('role') === 'jobseeker'): ?>
                    <a href="#" class="bookmark-btn" title="Simpan lowongan" data-item-id="<?= $job->id ?>"
                        data-item-type="job">
                        <i class="bi <?= in_array($job->id, $bookmarkedJobs) ? 'bi-bookmark-fill' : 'bi-bookmark' ?>"
                            style="font-size: 1.5rem;"></i>
                    </a>
                    <?php endif; ?>

                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <img src="<?= esc($job->company_logo_path ? '/uploads/logos/'.$job->company_logo_path : 'https://placehold.co/50x50/A1C349/FFFFFF?text=PT') ?>"
                                alt="Logo Perusahaan" class="company-logo mr-3">
                            <div>
                                <h5 class="card-title mb-1" style="font-size: 1.1rem;"><a
                                        href="#"><?= esc($job->title) ?></a></h5>
                                <h6 class="card-subtitle text-muted"><?= esc($job->company_name) ?></h6>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            <i class="bi bi-geo-alt"></i> <?= esc($job->location_name ?? 'N/A') ?>
                            <span class="mx-2">|</span>
                            <i class="bi bi-briefcase"></i> <?= esc($job->job_type) ?>
                        </p>

                        <p class="card-text small text-muted flex-grow-1">
                            <?= esc(word_limiter($job->description, 15)) ?>
                        </p>

                        <a href="#" class="btn btn-outline-primary mt-auto">Lihat Detail & Lamar</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4>Lowongan Tidak Ditemukan</h4>
                    <p>Tidak ada lowongan yang cocok dengan kriteria pencarian Anda. Coba kata kunci lain.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Link Paginasi -->
        <div class="mt-4 d-flex justify-content-center">
            <?= $pager->links() ?>
        </div>
    </div>

    <!-- Script jQuery & AJAX -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.bookmark-btn').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var icon = button.find('i');
            var itemId = button.data('item-id');
            var itemType = button.data('item-type');

            $.ajax({
                url: "<?= site_url('bookmark/toggle') ?>",
                type: "POST",
                data: {
                    item_id: itemId,
                    item_type: itemType,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.action === 'added') {
                            icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                        } else {
                            icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                        }
                    } else {
                        alert('Gagal: ' + (response.message ||
                            'Silakan login terlebih dahulu.'));
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Pastikan Anda sudah login.');
                }
            });
        });
    });
    </script>
</body>

</html>