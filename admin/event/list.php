<?php
// ===================================================================
// KODE UNTUK MODUL: MANAJEMEN EVENT
// ===================================================================

// File: admin/event/list.php (Logika Alur Baru)

/**
 * File: list.php (Event - Logika Alur Baru)
 * Deskripsi: Menampilkan daftar SEMUA UKM beserta jumlah event mereka.
 */

$pageTitle = 'Manajemen Event';
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/header.php';

try {
    $stmt = $pdo->query("
        SELECT u.id_ukm, u.nama_ukm, COUNT(e.id_event) AS jumlah_event
        FROM ukm u
        LEFT JOIN event e ON u.id_ukm = e.id_ukm
        GROUP BY u.id_ukm, u.nama_ukm
        ORDER BY u.nama_ukm ASC
    ");
    $ukm_list = $stmt->fetchAll();
} catch (PDOException $e) { die("Error: Gagal mengambil data. " . $e->getMessage()); }
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <p style="color: var(--text-muted); margin-top: -20px; margin-bottom: 20px;">Pilih UKM di bawah ini untuk menambah event baru atau melihat daftar eventnya.</p>
    <div class="table-container">
        <table class="table">
            <thead><tr><th>Nama UKM</th><th style="text-align: center;">Jumlah Event</th><th style="text-align: center; width: 30%;">Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($ukm_list as $ukm): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($ukm['nama_ukm']); ?></strong></td>
                        <td style="text-align: center;"><?php echo $ukm['jumlah_event']; ?></td>
                        <td style="text-align: center;">
                            <a href="tambah.php?id_ukm=<?php echo $ukm['id_ukm']; ?>" class="btn btn-primary">Tambah Event</a>
                            <a href="detail.php?id_ukm=<?php echo $ukm['id_ukm']; ?>" class="btn">Lihat Daftar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../inc/footer.php'; ?>