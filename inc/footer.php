<?php

?>
    </main> <!-- Penutup tag <main> yang dibuka di header.php -->

    <footer class="main-footer" style="background-color: var(--surface-variant); color: var(--text-muted); padding-top: var(--space-12); margin-top: var(--space-16); border-top: 1px solid var(--border);">
        <div class="container">
            <div class="footer-content" style="display: flex; flex-wrap: wrap; gap: var(--space-8); justify-content: space-between; margin-bottom: var(--space-10);">
                
                <!-- Kolom Tentang -->
                <div class="footer-column" style="flex: 2; min-width: 250px;">
                    <h3 style="font-family: var(--font-heading); color: var(--text); font-size: 1.2rem; margin-bottom: var(--space-4);">Portofolio UKM</h3>
                    <p style="font-size: 0.9rem; line-height: 1.7;">Platform digital untuk menjelajahi dan menemukan informasi lengkap mengenai berbagai Unit Kegiatan Mahasiswa di lingkungan kampus.</p>
                </div>

                <!-- Kolom Tautan Cepat -->
                <div class="footer-column" style="flex: 1; min-width: 150px;">
                    <h4 style="font-family: var(--font-heading); color: var(--text); font-size: 1rem; margin-bottom: var(--space-4);">Tautan Cepat</h4>
                    <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: var(--space-2);">
                        <li><a href="/index.php" style="font-size: 0.9rem; text-decoration: none; color: var(--text-muted);">Beranda</a></li>
                        <li><a href="/index.php#kategori" style="font-size: 0.9rem; text-decoration: none; color: var(--text-muted);">Kategori</a></li>
                        <li><a href="/admin/login.php" style="font-size: 0.9rem; text-decoration: none; color: var(--text-muted);">Login Admin</a></li>
                    </ul>
                </div>

                <!-- Kolom Kontak -->
                <div class="footer-column" style="flex: 1; min-width: 200px;">
                    <h4 style="font-family: var(--font-heading); color: var(--text); font-size: 1rem; margin-bottom: var(--space-4);">Hubungi Kami</h4>
                    <p style="font-size: 0.9rem; margin: 0;">Untuk informasi lebih lanjut, silakan hubungi pihak kemahasiswaan.</p>
                    <a href="mailto:info@kampus.ac.id" style="font-size: 0.9rem; color: var(--primary-600);">info@kampus.ac.id</a>
                </div>

            </div>
            <div class="footer-bottom" style="border-top: 1px solid var(--border); padding-block: var(--space-6); text-align: center;">
                <p style="margin: 0; font-size: 0.875rem;">&copy; <?php echo date('Y'); ?> Portofolio UKM STIS.</p>
            </div>
        </div>
    </footer>

    <!-- Muat skrip di sini -->
    <script src="/js/toggle.js"></script>
    <script src="/js/search.js"></script>
    <script src="/js/theme-toggle.js"></script>
    <script src="/js/card-glow.js"></script>
    <script src="/js/slider.js"></script>

</body>
</html>
