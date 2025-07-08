<?php
/**
 * File: pengurus.php
 * Deskripsi: Halaman ini menampilkan struktur kepengurusan dari sebuah UKM.
 * Ia mengambil data dari tabel 'pengurus' yang berelasi dengan ID UKM yang aktif.
 * Setiap pengurus akan ditampilkan dalam bentuk kartu profil yang berisi foto,
 * nama, dan jabatan, memberikan gambaran yang jelas tentang tim di balik UKM.
 */

require_once __DIR__ . '/../inc/db.php';

// Validasi ID UKM dari URL
$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /ukm-portfolio/index.php');
    exit;
}

// Ambil nama UKM untuk judul dan data pengurus
try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();

    if (!$ukm) {
        header('Location: /ukm-portfolio/index.php');
        exit;
    }

    $stmt_pengurus = $pdo->prepare("SELECT * FROM pengurus WHERE id_ukm = ? ORDER BY urutan_jabatan ASC"); // Asumsi ada kolom urutan
    $stmt_pengurus->execute([$id_ukm]);
    $pengurus_list = $stmt_pengurus->fetchAll();

} catch (PDOException $e) {
    error_log("Gagal mengambil data pengurus: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data kepengurusan.");
}

$pageTitle = 'Struktur Pengurus - ' . htmlspecialchars($ukm['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-title-section" style="padding: 40px 0; background-color: var(--surface-color);">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">Struktur Pengurus</h1>
        <p style="font-size: 1.2rem; color: var(--text-muted-color);"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></p>
    </div>
</div>

<?php 
// Memanggil komponen sub-navigasi
require_once __DIR__ . '/sub_nav.php'; 
?>

<section id="pengurus-content">
    <div class="container">
        <?php if (!empty($pengurus_list)): ?>
            <div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">
                <?php foreach ($pengurus_list as $pengurus): ?>
                    <div class="card" style="text-align: center;">
                        <div class="card-content">
                            <img src="/ukm-portfolio/upload/pengurus/<?php echo htmlspecialchars($pengurus['foto']); ?>" alt="Foto <?php echo htmlspecialchars($pengurus['nama_lengkap']); ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 15px auto; border: 3px solid var(--border-color);">
                            <h3 style="font-size: 1.2rem; color: var(--text-color); margin-bottom: 5px;"><?php echo htmlspecialchars($pengurus['nama_lengkap']); ?></h3>
                            <p style="color: var(--primary-color); font-weight: 600;"><?php echo htmlspecialchars($pengurus['jabatan']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-content" style="text-align: center;">
                    <p style="color: var(--text-muted-color); padding: 20px;">Belum ada data pengurus yang ditambahkan untuk UKM ini.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
