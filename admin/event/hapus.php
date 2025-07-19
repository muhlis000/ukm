<?php
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_event = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_ukm_for_redirect = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
$source_page = $_GET['from'] ?? 'list'; // Menentukan halaman asal (default ke 'list')

if ($id_event) {
    try {
        // Ambil nama file poster sebelum menghapus record dari database
        $stmt_select = $pdo->prepare("SELECT poster FROM event WHERE id_event = ?");
        $stmt_select->execute([$id_event]);
        $poster_to_delete = $stmt_select->fetchColumn();

        // Hapus record event dari database
        $stmt_delete = $pdo->prepare("DELETE FROM event WHERE id_event = ?");
        $stmt_delete->execute([$id_event]);

        // Jika ada file poster, hapus dari server
        if ($poster_to_delete) {
            $file_path = __DIR__ . '/../../upload/event/' . $poster_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Event berhasil dihapus.'];

    } catch (PDOException $e) {
        $_SESSION['flash_message'] = ['type' => 'flash-error', 'text' => 'Gagal menghapus event.'];
        error_log($e->getMessage());
    }
}

// Logika redirect baru
if ($source_page === 'detail' && $id_ukm_for_redirect) {
    // Jika berasal dari halaman detail, kembali ke halaman detail UKM tersebut
    header('Location: detail.php?id_ukm=' . $id_ukm_for_redirect);
} else {
    // Jika tidak, kembali ke halaman daftar utama
    header('Location: list.php');
}
exit;
