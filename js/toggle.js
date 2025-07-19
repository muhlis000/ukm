document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mainNav = document.getElementById('main-nav');

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
