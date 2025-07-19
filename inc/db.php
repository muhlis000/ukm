<?php

// Konfigurasi untuk koneksi ke database.
// Ganti nilai-nilai ini sesuai dengan pengaturan server database Anda.
define('BASE_URL', 'http://localhost/ukm-portfolio');

$host = 'localhost';      // Biasanya 'localhost' atau alamat IP server database.
$dbname = 'projec15_ukm_portofolio'; // Nama database yang Anda buat.
$user = 'projec15_root';             // Username untuk mengakses database.
$pass = '@kaesquare123';               // Password untuk user database tersebut.
$charset = 'utf8mb4';     // Charset yang direkomendasikan untuk mendukung berbagai karakter.

// DSN (Data Source Name) mendefinisikan sumber data untuk koneksi.
// Ini mencakup jenis database (mysql), host, nama database, dan charset.
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opsi untuk koneksi PDO.
// - PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION: Melemparkan exception jika terjadi error SQL.
// - PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC: Mengembalikan hasil query sebagai array asosiatif.
// - PDO::ATTR_EMULATE_PREPARES => false: Menggunakan prepared statements asli dari driver database.
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Membuat instance PDO baru untuk memulai koneksi.
    // Variabel $pdo akan digunakan di seluruh aplikasi untuk berinteraksi dengan database.
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika koneksi gagal, script akan berhenti dan menampilkan pesan error.
    // Ini mencegah aplikasi berjalan dengan koneksi database yang tidak valid.
    // Pada lingkungan produksi, pesan error detail sebaiknya tidak ditampilkan ke user,
    // melainkan dicatat (logged) di server.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
