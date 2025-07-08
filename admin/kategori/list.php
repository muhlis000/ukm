<?php
/**
 * File: list.php (Kategori - Revisi)
 * Deskripsi: Halaman utama manajemen kategori.
 * Perubahan: Sekarang menggunakan header dan footer global dari direktori root /inc/,
 * bukan lagi dari /admin/inc/. Ini memastikan konsistensi tampilan.
 * Semua gaya sekarang dimuat dari file css/style.css utama.
 */

// Menampilkan semua error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pageTitle = 'Manajemen Kategori';

// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Mengambil semua data kategori dari database
try {
    $stmt = $pdo->query("SELECT * FROM kategori_ukm ORDER BY nama_kategori ASC");
    $kategori_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="tambah.php" class="btn btn-primary action-button">Tambah Kategori Baru</a>

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
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kategori_list)): ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">Belum ada data kategori.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($kategori['nama_kategori']); ?></td>
                            <td><?php echo htmlspecialchars($kategori['deskripsi']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $kategori['id_kategori']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="hapus.php?id=<?php echo $kategori['id_kategori']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Hapus</a>
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

