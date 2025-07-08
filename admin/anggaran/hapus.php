<?php
// ===================================================================
// KODE UNTUK FILE: admin/anggaran/hapus.php
// ===================================================================

/**
 * File: hapus.php (Anggaran)
 * Deskripsi: Skrip untuk menghapus data transaksi anggaran.
 */

require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_anggaran = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_ukm_for_redirect = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
$source_page = $_GET['from'] ?? 'list';

if ($id_anggaran) {
    try {
        $stmt = $pdo->prepare("DELETE FROM anggaran WHERE id_anggaran = ?");
        $stmt->execute([$id_anggaran]);
        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Transaksi berhasil dihapus.'];
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus transaksi.'];
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