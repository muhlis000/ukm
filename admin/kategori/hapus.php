<?php
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
