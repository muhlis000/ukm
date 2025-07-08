<?php 
// ===================================================================
// KODE UNTUK FILE: admin/anggaran/list.php (Logika Baru)
// ===================================================================

/**
 * File: list.php (Anggaran - Logika Alur Baru)
 * Deskripsi: Menampilkan daftar SEMUA UKM beserta total saldo mereka.
 * Halaman ini menjadi pusat untuk memilih UKM yang akan dikelola anggarannya.
 */

$pageTitle = 'Manajemen Anggaran';

require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/header.php';

// Query untuk mengambil semua UKM dan menghitung total saldo mereka.
// Menggunakan subquery untuk kalkulasi saldo.
try {
    $stmt = $pdo->query("
        SELECT 
            u.id_ukm, 
            u.nama_ukm,
            (
                SELECT SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE -jumlah END) 
                FROM anggaran a 
                WHERE a.id_ukm = u.id_ukm
            ) AS saldo
        FROM ukm u
        ORDER BY u.nama_ukm ASC
    ");
    $ukm_list_with_saldo = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data UKM dan saldo. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <p style="color: var(--text-muted); margin-top: -20px; margin-bottom: 20px;">
        Pilih UKM di bawah ini untuk menambah transaksi baru atau melihat riwayat detail anggarannya.
    </p>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama UKM</th>
                    <th style="text-align: right;">Saldo Terkini (IDR)</th>
                    <th style="text-align: center; width: 30%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ukm_list_with_saldo)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">Belum ada data UKM untuk dikelola.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ukm_list_with_saldo as $ukm): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($ukm['nama_ukm']); ?></strong></td>
                            <td style="text-align: right; font-weight: 600; color: <?php echo ($ukm['saldo'] ?? 0) >= 0 ? 'var(--success)' : 'var(--error)'; ?>;">
                                <?php echo number_format($ukm['saldo'] ?? 0, 2, ',', '.'); ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="tambah.php?id_ukm=<?php echo $ukm['id_ukm']; ?>" class="btn btn-primary">Tambah Transaksi</a>
                                <a href="detail.php?id_ukm=<?php echo $ukm['id_ukm']; ?>" class="btn">Lihat Anggaran</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../inc/footer.php'; ?>

