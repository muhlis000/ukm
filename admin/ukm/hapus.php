<?php
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        // 1. Ambil nama file logo sebelum menghapus record
        $stmt_select = $pdo->prepare("SELECT logo FROM ukm WHERE id_ukm = ?");
        $stmt_select->execute([$id]);
        $logo_to_delete = $stmt_select->fetchColumn();

        // 2. Hapus record UKM dari database
        // PENTING: Untuk aplikasi nyata, Anda harus menangani penghapusan data
        // terkait di tabel lain (proker, pengurus, event, anggaran, kontak)
        // Ini bisa dilakukan dengan 'ON DELETE CASCADE' di database
        // atau dengan menjalankan query DELETE untuk setiap tabel terkait di sini.
        $stmt_delete = $pdo->prepare("DELETE FROM ukm WHERE id_ukm = ?");
        $stmt_delete->execute([$id]);

        // 3. Hapus file logo fisik jika ada
        if ($logo_to_delete) {
            $file_path = __DIR__ . '/../../upload/logo/' . $logo_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'UKM dan data terkait berhasil dihapus.'];

    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus UKM.'];
        error_log($e->getMessage());
    }
}

header('Location: list.php');
exit;
