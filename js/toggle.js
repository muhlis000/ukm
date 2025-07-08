/**
 * Deskripsi: Mengelola fungsionalitas buka-tutup menu navigasi mobile.
 * Kini juga memperbarui atribut `aria-expanded` pada tombol untuk
 * meningkatkan aksesibilitas bagi pengguna screen reader.
 */

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mainNav = document.getElementById('main-nav');

    // Pastikan kedua elemen ada di halaman sebelum menambahkan event listener
    if (mobileMenuButton && mainNav) {
        mobileMenuButton.addEventListener('click', function() {
            // Cek status menu saat ini (terbuka atau tertutup)
            const isOpened = mobileMenuButton.getAttribute('aria-expanded') === 'true';

            // Toggle atribut aria-expanded
            mobileMenuButton.setAttribute('aria-expanded', !isOpened);

            // Toggle class 'is-open' pada elemen nav untuk menampilkan/menyembunyikan menu
            mainNav.classList.toggle('is-open');
        });
    }
});
