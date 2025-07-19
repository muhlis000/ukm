<?php
require_once __DIR__ . '/../inc/db.php';

// Validasi ID UKM dari URL
$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /index.php');
    exit;
}

// Ambil data UKM dari database
try {
    // Query ini mengambil data detail dari tabel 'ukm'
    $stmt = $pdo->prepare("SELECT * FROM ukm WHERE id_ukm = ?");
    $stmt->execute([$id_ukm]);
    $ukm = $stmt->fetch();

    if (!$ukm) {
        // Jika UKM tidak ditemukan, kembali ke halaman utama
        header('Location: /index.php');
        exit;
    }
} catch (PDOException $e) {
    // Sebaiknya error dicatat, bukan ditampilkan ke pengguna
    error_log("Gagal mengambil data UKM: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data UKM. Silakan coba lagi nanti.");
}

$pageTitle = htmlspecialchars($ukm['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';
?>

<div class="ukm-profile-header" style="padding: 40px 0; background-color: var(--surface-color); text-align: center;">
    <div class="container">
        <img src="/upload/logo/<?php echo htmlspecialchars($ukm['logo']); ?>" alt="Logo <?php echo htmlspecialchars($ukm['nama_ukm']); ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 20px auto; border: 4px solid var(--primary-color);" onerror="this.onerror=null;this.src='https://placehold.co/120x120/EBF4FF/1E40AF?text=Logo';">
        <h1 style="font-size: 2.8rem; margin-bottom: 5px;"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
        <p style="font-size: 1.1rem; color: var(--text-muted-color);"><?php echo htmlspecialchars($ukm['slogan']); // Asumsi ada kolom slogan ?></p>
    </div>
</div>

<?php 
// Memanggil komponen sub-navigasi
// Variabel $id_ukm sudah tersedia dari atas
require_once __DIR__ . '/sub_nav.php'; 
?>

<section id="ukm-content">
    <div class="container">
        <div class="profile-layout" style="display: grid; grid-template-columns: 1fr; gap: 40px;">

            <!-- Deskripsi UKM -->
            <div class="card">
                <div class="card-content">
                    <h2 style="font-size: 1.8rem;">Tentang Kami</h2>
                    <div class="ukm-description" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($ukm['deskripsi_panjang'])); // Asumsi ada kolom deskripsi panjang ?>
                    </div>
                </div>
            </div>

            <!-- Visi & Misi -->
            <div class="card">
                <div class="card-content">
                    <h2 style="font-size: 1.8rem;">Visi & Misi</h2>
                    <h3 style="color: var(--primary-color); margin-top: 20px;">Visi</h3>
                    <p style="margin-bottom: 20px;"><?php echo htmlspecialchars($ukm['visi']); // Asumsi ada kolom visi ?></p>

                    <h3 style="color: var(--primary-color);">Misi</h3>
                    <div class="misi-list">
                        <?php echo nl2br(htmlspecialchars($ukm['misi'])); // Asumsi ada kolom misi, nl2br untuk baris baru ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
