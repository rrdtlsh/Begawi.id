<?php // File: app/Views/layouts/footer.php ?>

<footer class="footer py-5">
    <div class="container">
        <div class="row">
            <!-- Kolom Logo (lebar tetap) -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="<?= base_url('images/Logo_Begawi.png') ?>" alt="Logo Begawi" class="logo-footer mb-3" style="height: 40px;">
                <p>Platform penyedia jasa terpercaya di Banjarmasin.</p>
            </div>
            
            <!-- Kolom Tautan Cepat (dilebarkan menjadi 4) -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5>Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Lowongan Kerja</a></li>
                    <li><a href="#">Perusahaan</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5>Ikuti Kami</h5>
                <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> Email: info@begawi.com</p>
                <p><i class="bi bi-telephone-fill me-2"></i> Telepon: (+62) 123-456-789</p>
            </div>
        </div>
        <div class="footer-bottom text-center pt-4 mt-4">
            <p>&copy; <span id="currentYear">2025</span> Begawi. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script>
    if(document.getElementById('currentYear')) {
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    }
</script>
