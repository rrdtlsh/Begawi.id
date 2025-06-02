<h1>Registrasi Pengguna Baru 📝</h1>

<?php if (session()->getFlashdata('message')): ?>
    <p style="color:blue;"><?= esc(session()->getFlashdata('message')) ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red;"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color:red;">
    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
        <li><?= esc($error) ?></li>
    <?php endforeach ?>
    </ul>
<?php endif; ?>

<form method="post" action="/register/process">
    <?= csrf_field() ?>
    <div>
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" value="<?= old('username') ?>" required>
    </div>
    <br>
    <div>
        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" value="<?= old('email') ?>" required>
    </div>
    <br>
    <div>
        <label for="password">Password (min. 6 karakter):</label><br>
        <input type="password" name="password" id="password" required>
    </div>
    <br>
    <div>
        <label for="role">Daftar sebagai:</label><br>
        <select name="role" id="role" required>
            <option value="jobseeker" <?= old('role') == 'jobseeker' ? 'selected' : '' ?>>Pencari Kerja</option>
            <option value="vendor" <?= old('role') == 'vendor' ? 'selected' : '' ?>>Penyedia Kerja</option>
        </select>
    </div>
    <br>
    <button type="submit">Register</button>
</form>
<p>Sudah punya akun? <a href="/login">Login di sini</a></p>