<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<h1 class="h3 mb-4"><?= esc($title) ?></h1>

<?php if (session()->get('errors')): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach (session()->get('errors') as $error): ?>
        <li><?= esc($error) ?></li>
        <?php endforeach ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form
            action="<?= isset($category) ? route_to('admin.job-categories.update', $category->id) : route_to('admin.job-categories.create') ?>"
            method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?= esc(old('name', $category->name ?? ''), 'attr') ?>" required>
            </div>

            <div class="mb-3">
                <label for="icon_path" class="form-label">Nama Class Icon (dari Bootstrap Icons)</label>
                <input type="text" class="form-control" id="icon_path" name="icon_path"
                    value="<?= esc(old('icon_path', $category->icon_path ?? ''), 'attr') ?>"
                    placeholder="Contoh: bi-code-slash">
                <small class="form-text text-muted">Lihat daftar ikon di <a href="https://icons.getbootstrap.com/"
                        target="_blank">website Bootstrap Icons</a>.</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= route_to('admin.master-data.index') ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>