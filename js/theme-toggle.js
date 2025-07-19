document.addEventListener('DOMContentLoaded', () => {
    const themeToggleButton = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Fungsi untuk menerapkan tema
    const applyTheme = (isDark) => {
        if (isDark) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }
    };

    // Cek tema saat halaman dimuat
    const storedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (storedTheme === 'dark' || (storedTheme === null && systemPrefersDark)) {
        applyTheme(true);
    } else {
        applyTheme(false);
    }

    // Event listener untuk tombol
    themeToggleButton.addEventListener('click', () => {
        const isCurrentlyDark = htmlElement.classList.contains('dark');
        applyTheme(!isCurrentlyDark);

        // Simpan preferensi pengguna
        localStorage.setItem('theme', !isCurrentlyDark ? 'dark' : 'light');
    });
});
