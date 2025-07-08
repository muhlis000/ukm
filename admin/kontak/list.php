<?php
/**
 * File: list.php (Kontak)
 * Deskripsi: Halaman utama untuk manajemen kontak.
 * Halaman ini menampilkan daftar SEMUA UKM, beserta ringkasan informasi
 * kontak mereka (seperti email dan telepon) yang diambil menggunakan LEFT JOIN.
 * Ini memungkinkan admin melihat status kontak setiap UKM dalam satu halaman
 * dan menyediakan tautan untuk mengedit detailnya.
 */

$pageTitle = 'Manajemen Kontak';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Mengambil semua data UKM, di-join dengan kontak untuk mendapatkan info ringkas
try {
    $stmt = $pdo->query("
        SELECT u.id_ukm, u.nama_ukm, k.email, k.telepon
        FROM ukm u
        LEFT JOIN kontak k ON u.id_ukm = k.id_ukm
        ORDER BY u.nama_ukm ASC
    ");
    $ukm_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil data. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <p style="color: var(--text-muted-color); margin-top: -15px; margin-bottom: 20px;">
        Halaman ini menampilkan ringkasan kontak untuk semua UKM. Klik 'Edit' untuk mengelola detail kontak setiap UKM.
    </p>

    <?php
    // Tampilkan flash message jika ada (berguna jika dialihkan dari halaman edit)
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
                    <th>Nama UKM</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ukm_list)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Belum ada data UKM yang terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ukm_list as $ukm): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ukm['nama_ukm']); ?></td>
                            <td><?php echo htmlspecialchars($ukm['email'] ?? '<i>Belum diatur</i>'); ?></td>
                            <td><?php echo htmlspecialchars($ukm['telepon'] ?? '<i>Belum diatur</i>'); ?></td>
                            <td>
                                <!-- Link ini akan mengarah ke halaman edit untuk UKM yang spesifik -->
                                <a href="edit.php?id_ukm=<?php echo $ukm['id_ukm']; ?>" class="btn">Edit Kontak</a>
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
