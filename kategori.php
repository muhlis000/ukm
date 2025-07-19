<?php
require_once __DIR__ . '/inc/db.php';

// Validasi parameter ID dari URL. Harus berupa angka.
$id_kategori = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_kategori) {
    // Jika ID tidak valid atau tidak ada, alihkan ke halaman utama.
    header('Location: index.php');
    exit;
}

// Ambil detail kategori dari database
try {
    $stmt_cat = $pdo->prepare("SELECT nama_kategori, deskripsi FROM kategori_ukm WHERE id_kategori = ?");
    $stmt_cat->execute([$id_kategori]);
    $kategori = $stmt_cat->fetch();

    // Jika kategori dengan ID tersebut tidak ditemukan, alihkan.
    if (!$kategori) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: Gagal mengambil data kategori.");
}

// Ambil daftar UKM dalam kategori ini
try {
    $stmt_ukm = $pdo->prepare("SELECT id_ukm, nama_ukm, logo FROM ukm WHERE id_kategori = ? ORDER BY nama_ukm ASC");
    $stmt_ukm->execute([$id_kategori]);
    $ukm_list = $stmt_ukm->fetchAll();
} catch (PDOException $e) {
    die("Error: Gagal mengambil daftar UKM.");
}


$pageTitle = 'Kategori: ' . htmlspecialchars($kategori['nama_kategori']);
require_once __DIR__ . '/inc/header.php';
?>

<section class="page-header" style="background-color: var(--surface-color); padding: 40px 0;">
    <div class="container">
        <h1>Kategori: <?php echo htmlspecialchars($kategori['nama_kategori']); ?></h1>
        <p style="color: var(--text-muted-color);"><?php echo htmlspecialchars($kategori['deskripsi']); ?></p>
    </div>
</section>

<section id="ukm-list">
    <div class="container">
        <h2 style="margin-bottom: 30px;">Daftar UKM</h2>

        <?php if (!empty($ukm_list)): ?>
            <div class="card-grid">
                <?php foreach ($ukm_list as $ukm): ?>
                    <a href="ukm/detail.php?id=<?php echo $ukm['id_ukm']; ?>" class="card-link">
                        <div class="card">
                            <div class="card-content" style="text-align: center;">
                                <img src="/upload/logo/<?php echo htmlspecialchars($ukm['logo']); ?>" alt="Logo <?php echo htmlspecialchars($ukm['nama_ukm']); ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 15px auto; border: 3px solid var(--border-color);" onerror="this.onerror=null;this.src='https://placehold.co/80x80/EBF4FF/1E40AF?text=Logo';">
                                <h3 style="font-size: 1.2rem; color: var(--text-color); margin: 0;"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></h3>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-muted-color);">Belum ada UKM yang terdaftar di kategori ini.</p>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php#kategori" class="btn btn-primary">&larr; Kembali ke Semua Kategori</a>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
