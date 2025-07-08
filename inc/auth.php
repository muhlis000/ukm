<?php
/**
 * File: auth.php
 * Deskripsi: Skrip otentikasi untuk panel admin.
 * File ini harus disertakan di bagian paling atas dari setiap halaman admin yang
 * memerlukan proteksi login. Tugasnya adalah memeriksa apakah session admin
 * (yang dibuat saat login berhasil) ada dan valid. Jika tidak, skrip akan
 * secara otomatis mengalihkan pengguna kembali ke halaman login, sehingga
 * mencegah akses tidak sah.
 */

// Memulai atau melanjutkan session yang sudah ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah 'admin_id' ada di dalam session
if (!isset($_SESSION['admin_id'])) {
    // Jika tidak ada, berarti pengguna belum login.
    // Arahkan mereka ke halaman login dan hentikan eksekusi skrip.
    header('Location: /ukm-portfolio/admin/login.php');
    exit;
}
