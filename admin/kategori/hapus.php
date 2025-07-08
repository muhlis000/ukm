<?php
/**
 * File: hapus.php (Kategori)
 * Deskripsi: Skrip ini menangani proses penghapusan data kategori.
 * Ia tidak memiliki tampilan HTML. Tugasnya hanya menerima ID dari URL,
 * melakukan penghapusan data dari database, lalu mengarahkan kembali
 * ke halaman daftar dengan pesan notifikasi. Dilindungi oleh auth.php.
 */

// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Validasi ID dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        // Peringatan: Sebelum menghapus kategori, idealnya periksa dulu
        // apakah ada UKM yang masih menggunakan kategori ini.
        // Jika ada, proses hapus bisa dibatalkan atau UKM-nya harus dipindahkan dulu.
        // Contoh:
        // $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM ukm WHERE id_kategori = ?");
        // $stmt_check->execute([$id]);
        // if ($stmt_check->fetchColumn() > 0) {
        //     $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus! Kategori masih digunakan oleh UKM.'];
        // } else {
            $stmt = $pdo->prepare("DELETE FROM kategori_ukm WHERE id_kategori = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Kategori berhasil dihapus.'];
        // }
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus kategori.'];
        error_log($e->getMessage());
    }
}

// Kembali ke halaman daftar
header('Location: list.php');
exit;
