<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('title') ?>
<?= esc($title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-4"><?= esc($title) ?></h1>

<?php if (session()->get('errors')): ?>
    <div class="alert alert-danger">
        <strong>Terjadi Kesalahan Validasi:</strong>
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
            action="<?= isset($skill) ? route_to('admin.skills.update', $skill->id) : route_to('admin.skills.create') ?>"
            method="post">
            <?= csrf_field() ?>

            <?php if (isset($skill)): ?>
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Keahlian</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?= esc(old('name', $skill->name ?? ''), 'attr') ?>" required>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= route_to('admin.master-data.index') ?>" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>