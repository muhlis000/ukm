<?php
// ===================================================================
// KODE UNTUK FILE: admin/pengurus/hapus.php
// ===================================================================

/**
 * File: hapus.php (Pengurus)
 * Deskripsi: Skrip untuk menghapus data pengurus dan file fotonya.
 */

require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_pengurus = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_ukm_for_redirect = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
$source_page = $_GET['from'] ?? 'list';

if ($id_pengurus) {
    try {
        // Ambil nama file foto sebelum menghapus record
        $stmt_select = $pdo->prepare("SELECT foto FROM pengurus WHERE id_pengurus = ?");
        $stmt_select->execute([$id_pengurus]);
        $foto_to_delete = $stmt_select->fetchColumn();

        // Hapus record dari database
        $stmt_delete = $pdo->prepare("DELETE FROM pengurus WHERE id_pengurus = ?");
        $stmt_delete->execute([$id_pengurus]);

        // Hapus file foto fisik jika ada
        if ($foto_to_delete) {
            $file_path = __DIR__ . '/../../upload/pengurus/' . $foto_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Data pengurus berhasil dihapus.'];

    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus data pengurus.'];
        error_log($e->getMessage());
    }
}

// Logika redirect
if ($source_page === 'detail' && $id_ukm_for_redirect) {
    header('Location: detail.php?id_ukm=' . $id_ukm_for_redirect);
} else {
    header('Location: list.php');
}
exit;
?>