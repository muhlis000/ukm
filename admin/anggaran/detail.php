<?php 
// ===================================================================
// KODE UNTUK FILE BARU: admin/anggaran/detail.php
// ===================================================================

/**
 * File: detail.php (Anggaran)
 * Deskripsi: Menampilkan riwayat transaksi detail untuk SATU UKM spesifik.
 */

$pageTitle = 'Riwayat Anggaran';

require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: list.php');
    exit;
}

try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();
    if (!$ukm) { header('Location: list.php'); exit; }

    $stmt_anggaran = $pdo->prepare("SELECT * FROM anggaran WHERE id_ukm = ? ORDER BY tanggal DESC, id_anggaran DESC");
    $stmt_anggaran->execute([$id_ukm]);
    $anggaran_list = $stmt_anggaran->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data. " . $e->getMessage());
}

require_once __DIR__ . '/../../inc/header.php';
?>

<div class="container">
    <h1 class="page-title">Riwayat Anggaran: <?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
    <div style="margin-bottom: 20px;">
        <a href="list.php" class="btn">&larr; Kembali ke Daftar UKM</a>
        <a href="tambah.php?id_ukm=<?php echo $id_ukm; ?>" class="btn btn-primary">Tambah Transaksi Baru</a>
    </div>

    <?php
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        echo "<div class='flash-message {$message['type']}'>{$message['text']}</div>";
    }
    ?>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th style="text-align: right;">Jumlah (IDR)</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($anggaran_list)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada anggaran untuk UKM ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($anggaran_list as $item): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($item['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($item['keterangan']); ?></td>
                            <td>
                                <span style="background-color: <?php echo $item['jenis'] == 'pemasukan' ? '#e8f5e9' : '#ffebee'; ?>; color: <?php echo $item['jenis'] == 'pemasukan' ? '#2e7d32' : '#c62828'; ?>; padding: 3px 8px; border-radius: 4px; font-weight: 600;">
                                    <?php echo htmlspecialchars(ucfirst($item['jenis'])); ?>
                                </span>
                            </td>
                            <td style="text-align: right;"><?php echo number_format($item['jumlah'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $item['id_anggaran']; ?>" class="btn">Edit</a>
                                <a href="hapus.php?id=<?php echo $item['id_anggaran']; ?>&id_ukm=<?php echo $item['id_ukm']; ?>&from=detail" class="btn btn-secondary" onclick="return confirm('Anda yakin ingin menghapus transaksi ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../inc/footer.php'; ?>

