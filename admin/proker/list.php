<?php
/**
 * File: list.php (Proker)
 * Deskripsi: Halaman utama untuk manajemen Program Kerja.
 * Halaman ini menampilkan daftar semua proker dari seluruh UKM.
 */

$pageTitle = 'Manajemen Program Kerja';
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';
require_once __DIR__ . '/../../inc/header.php';

// Mengambil semua data proker, di-join dengan tabel ukm untuk mendapatkan nama UKM
try {
    $stmt = $pdo->query("
        SELECT p.id_proker, p.id_ukm, p.nama_proker, p.tanggal_pelaksanaan, p.status, u.nama_ukm
        FROM proker p
        JOIN ukm u ON p.id_ukm = u.id_ukm
        ORDER BY p.tanggal_pelaksanaan DESC
    ");
    $proker_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data program kerja. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <p style="color: var(--text-muted); margin-top: -20px; margin-bottom: 20px;">
        Halaman ini menampilkan semua program kerja dari seluruh UKM. Untuk menambah proker baru, Anda harus memilih UKM terlebih dahulu.
    </p>

    <?php
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        echo "<div class='flash-message flash-success'>{$message['text']}</div>";
    }
    ?>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Program Kerja</th>
                    <th>Milik UKM</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proker_list)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada data program kerja.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proker_list as $proker): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proker['nama_proker']); ?></td>
                            <td><strong><?php echo htmlspecialchars($proker['nama_ukm']); ?></strong></td>
                            <td><?php echo date('d M Y', strtotime($proker['tanggal_pelaksanaan'])); ?></td>
                            <td><?php echo htmlspecialchars($proker['status']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="tambah.php?id_ukm=<?php echo $proker['id_ukm']; ?>" class="btn btn-primary">Tambah Proker</a>
                                    <a href="detail.php?id_ukm=<?php echo $proker['id_ukm']; ?>" class="btn">Lihat Daftar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
