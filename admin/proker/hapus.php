<?php
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_proker = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_ukm_for_redirect = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
$source_page = $_GET['from'] ?? 'list';

if ($id_proker) {
    try {
        $stmt = $pdo->prepare("DELETE FROM proker WHERE id_proker = ?");
        $stmt->execute([$id_proker]);
        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Program kerja berhasil dihapus.'];
    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus program kerja.'];
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