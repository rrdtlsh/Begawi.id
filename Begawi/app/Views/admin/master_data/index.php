<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen Data Master
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-4">Manajemen Data Master</h1>

<?php if (session()->get('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->get('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (session()->get('error') || session()->get('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <p class="mb-1"><strong>Terjadi Kesalahan:</strong></p>
        <ul class="mb-0">
            <?php
            $errors = session()->get('errors') ?? [];
            if (session()->get('error')) {
                $errors[] = session()->get('error');
            }
            foreach ($errors as $error):
                ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<ul class="nav nav-tabs" id="masterDataTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-pane"
            type="button" role="tab">Kategori Pekerjaan</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills-pane" type="button"
            role="tab">Keahlian</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="locations-tab" data-bs-toggle="tab" data-bs-target="#locations-pane" type="button"
            role="tab">Lokasi</button>
    </li>
</ul>

<div class="tab-content" id="masterDataTabContent">

    <div class="tab-pane fade show active" id="categories-pane" role="tabpanel">
        <div class="card border-top-0">
            <div class="card-body">
                <form action="<?= site_url('admin/job-categories/import') ?>" method="post"
                    enctype="multipart/form-data" class="border p-3 rounded bg-light mb-4">
                    <?= csrf_field() ?>
                    <h6 class="mb-1">Import Kategori dari Excel</h6>
                    <p class="small text-muted mb-2">Format: Kolom A = Nama Kategori, Kolom B = Class Icon.</p>
                    <div class="d-flex gap-2">
                        <input type="file" name="excel_file" class="form-control form-control-sm" required
                            accept=".xlsx, .xls">
                        <button type="submit" class="btn btn-sm btn-success flex-shrink-0"><i class="bi bi-upload"></i>
                            Upload</button>
                    </div>
                </form>

                <div class="d-flex justify-content-end my-3">
                    <a href="<?= route_to('admin.job-categories.new') ?>" class="btn btn-sm btn-primary"><i
                            class="bi bi-plus"></i> Tambah Kategori Manual</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Icon</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($item->name) ?></td>
                                    <td><i class="bi <?= esc($item->icon_path) ?>"></i></td>
                                    <td class="text-end">
                                        <a href="<?= route_to('admin.job-categories.edit', $item->id) ?>"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.job-categories.delete', $item->id) ?>"
                                            method="post" class="d-inline" onsubmit="return confirm('Anda yakin?');">
                                            <?= csrf_field() ?><button type="submit"
                                                class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="skills-pane" role="tabpanel">
        <div class="card border-top-0">
            <div class="card-body">
                <form action="<?= site_url('admin/skills/import') ?>" method="post" enctype="multipart/form-data"
                    class="border p-3 rounded bg-light mb-4">
                    <?= csrf_field() ?>
                    <h6 class="mb-1">Import Keahlian dari Excel</h6>
                    <p class="small text-muted mb-2">Format: Kolom A = Nama Keahlian.</p>
                    <div class="d-flex gap-2">
                        <input type="file" name="excel_file" class="form-control form-control-sm" required
                            accept=".xlsx, .xls">
                        <button type="submit" class="btn btn-sm btn-success flex-shrink-0"><i class="bi bi-upload"></i>
                            Upload</button>
                    </div>
                </form>

                <div class="d-flex justify-content-end my-3">
                    <a href="<?= route_to('admin.skills.new') ?>" class="btn btn-sm btn-primary"><i
                            class="bi bi-plus"></i> Tambah Keahlian Manual</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Keahlian</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skills as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($item->name) ?></td>
                                    <td class="text-end">
                                        <a href="<?= route_to('admin.skills.edit', $item->id) ?>"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.skills.delete', $item->id) ?>" method="post"
                                            class="d-inline" onsubmit="return confirm('Anda yakin?');">
                                            <?= csrf_field() ?><button type="submit"
                                                class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="locations-pane" role="tabpanel">
        <div class="card border-top-0">
            <div class="card-body">
                <form action="<?= site_url('admin/locations/import') ?>" method="post" enctype="multipart/form-data"
                    class="border p-3 rounded bg-light mb-4">
                    <?= csrf_field() ?>
                    <h6 class="mb-1">Import Lokasi dari Excel</h6>
                    <p class="small text-muted mb-2">Format: Kolom A = Nama Lokasi.</p>
                    <div class="d-flex gap-2">
                        <input type="file" name="excel_file" class="form-control form-control-sm" required
                            accept=".xlsx, .xls">
                        <button type="submit" class="btn btn-sm btn-success flex-shrink-0"><i class="bi bi-upload"></i>
                            Upload</button>
                    </div>
                </form>

                <div class="d-flex justify-content-end my-3">
                    <a href="<?= route_to('admin.locations.new') ?>" class="btn btn-sm btn-primary"><i
                            class="bi bi-plus"></i> Tambah Lokasi Manual</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Lokasi</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locations as $index => $item ): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($item->name) ?></td>
                                    <td class="text-end">
                                        <a href="<?= route_to('admin.locations.edit', $item->id) ?>"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.locations.delete', $item->id) ?>" method="post"
                                            class="d-inline" onsubmit="return confirm('Anda yakin?');">
                                            <?= csrf_field() ?><button type="submit"
                                                class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>