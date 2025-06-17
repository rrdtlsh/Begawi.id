<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
Manajemen Data Master
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Data Master</h1>
</div>


<?php if (session()->get('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->get('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" id="masterDataTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-pane" type="button" role="tab"><i class="bi bi-tags-fill me-2"></i>Kategori Pekerjaan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills-pane" type="button" role="tab"><i class="bi bi-star-fill me-2"></i>Keahlian</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="locations-tab" data-bs-toggle="tab" data-bs-target="#locations-pane" type="button" role="tab"><i class="bi bi-geo-alt-fill me-2"></i>Lokasi</button>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content" id="masterDataTabContent">

            <div class="tab-pane fade show active" id="categories-pane" role="tabpanel">
                <div class="d-flex justify-content-end mb-3">
                    <a href="<?= route_to('admin.job-categories.new') ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Kategori</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Ikon</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category->id ?></td>
                                <td><?= esc($category->name) ?></td>
                                <td><i class="bi <?= esc($category->icon_path) ?>"></i> </td>
                                <td class="text-end">
                                    <div class="actions">
                                        <a href="<?= route_to('admin.job-categories.edit', $category->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.job-categories.delete', $category->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus kategori ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="skills-pane" role="tabpanel">
                 <div class="d-flex justify-content-end mb-3">
                    <a href="<?= route_to('admin.skills.new') ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Keahlian</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Keahlian</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= $skill->id ?></td>
                                <td><?= esc($skill->name) ?></td>
                                <td class="text-end">
                                    <div class="actions">
                                        <a href="<?= route_to('admin.skills.edit', $skill->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.skills.delete', $skill->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus keahlian ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="locations-pane" role="tabpanel">
                 <div class="d-flex justify-content-end mb-3">
                    <a href="<?= route_to('admin.locations.new') ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Lokasi</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Lokasi</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locations as $location): ?>
                            <tr>
                                <td><?= $location->id ?></td>
                                <td><?= esc($location->name) ?></td>
                                <td class="text-end">
                                    <div class="actions">
                                        <a href="<?= route_to('admin.locations.edit', $location->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="<?= route_to('admin.locations.delete', $location->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus lokasi ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
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