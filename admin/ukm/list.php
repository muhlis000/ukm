<?php
/**
 * File: list.php (UKM)
 * Deskripsi: Halaman utama untuk manajemen UKM.
 * Menampilkan daftar semua UKM dengan beberapa informasi kunci seperti logo, nama,
 * dan kategori. Menggunakan JOIN SQL untuk mengambil nama kategori dari tabel
 * kategori_ukm, membuat tampilan lebih informatif bagi admin.
 */

$pageTitle = 'Manajemen UKM';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Mengambil semua data UKM, di-join dengan kategori untuk mendapatkan nama kategori
try {
    $stmt = $pdo->query("
        SELECT u.id_ukm, u.nama_ukm, u.logo, k.nama_kategori
        FROM ukm u
        LEFT JOIN kategori_ukm k ON u.id_kategori = k.id_kategori
        ORDER BY u.nama_ukm ASC
    ");
    $ukm_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data UKM. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="tambah.php" class="btn btn-primary action-button">Tambah UKM Baru</a>

    <?php
    // Menampilkan flash message
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
                    <th style="width: 10%;">Logo</th>
                    <th>Nama UKM</th>
                    <th>Kategori</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ukm_list)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada data UKM.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ukm_list as $ukm): ?>
                        <tr>
                            <td>
                                <img src="/ukm-portfolio/upload/logo/<?php echo htmlspecialchars($ukm['logo']); ?>" alt="Logo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" onerror="this.onerror=null;this.src='https://placehold.co/50x50/eee/ccc?text=N/A';">
                            </td>
                            <td><?php echo htmlspecialchars($ukm['nama_ukm']); ?></td>
                            <td><?php echo htmlspecialchars($ukm['nama_kategori'] ?? 'Tidak ada kategori'); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $ukm['id_ukm']; ?>" class="btn">Edit</a>
                                <a href="hapus.php?id=<?php echo $ukm['id_ukm']; ?>" class="btn btn-secondary" onclick="return confirm('PERINGATAN: Menghapus UKM akan menghapus semua data terkait (proker, pengurus, dll). Apakah Anda yakin?');">Hapus</a>
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
