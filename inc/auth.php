<?php

// Memulai atau melanjutkan session yang sudah ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah 'admin_id' ada di dalam session
if (!isset($_SESSION['admin_id'])) {
    // Jika tidak ada, berarti pengguna belum login.
    // Arahkan mereka ke halaman login dan hentikan eksekusi skrip.
    header('Location: /admin/login.php');
    exit;
}
