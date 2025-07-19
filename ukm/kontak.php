<?php
/**
 * File: kontak.php (Halaman Publik - Dengan Layout Baru)
 * Deskripsi: Menampilkan video profil dan detail kontak secara berdampingan.
 */
require_once __DIR__ . '/../inc/db.php';

// Fungsi helper untuk mengekstrak ID Video YouTube dari URL
function getYouTubeEmbedUrl($url) {
    if (empty($url)) return null;
    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $match
    );
    $youtube_id = $match[1] ?? null;
    return $youtube_id ? 'https://www.youtube.com/embed/' . $youtube_id : null;
}

$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) { header('Location: /index.php'); exit; }

// Ambil data UKM dan kontak
try {
    $stmt_ukm_kontak = $pdo->prepare("SELECT u.nama_ukm, c.* FROM ukm u LEFT JOIN kontak c ON u.id_ukm = c.id_ukm WHERE u.id_ukm = ?");
    $stmt_ukm_kontak->execute([$id_ukm]);
    $data = $stmt_ukm_kontak->fetch();
    if (!$data) { header('Location: /index.php'); exit; }
} catch (PDOException $e) { die("Error mengambil data."); }

$pageTitle = 'Kontak - ' . htmlspecialchars($data['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';

$embed_url = getYouTubeEmbedUrl($data['video_profil_url'] ?? null);
?>

<div class="page-title-section">
    <div class="container">
        <h1>Hubungi Kami</h1>
        <p><?php echo htmlspecialchars($data['nama_ukm']); ?></p>
    </div>
</div>

<?php require_once __DIR__ . '/sub_nav.php'; ?>

<section id="kontak-content">
    <div class="container">
        <!-- ============================================= -->
        <!--     PENERAPAN LAYOUT BARU DI SINI             -->
        <!-- ============================================= -->
        <div class="contact-layout">
            <!-- Kolom Kiri: Video Profil -->
            <div class="video-profile-section card">
                <div class="card-content">
                    <h2 style="text-align: center; margin-bottom: var(--space-6);">Video Profil</h2>
                    <?php if ($embed_url): ?>
                        <div class="video-container">
                            <iframe 
                                src="<?php echo $embed_url; ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                title="Video Profil <?php echo htmlspecialchars($data['nama_ukm']); ?>">
                            </iframe>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center;">Video profil belum tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kolom Kanan: Informasi Kontak -->
            <div class="card">
                 <div class="card-content">
                     <h2 style="margin-bottom: var(--space-6);">Detail Kontak</h2>
                     <ul style="list-style: none; display: flex; flex-direction: column; gap: var(--space-4);">
                        <li>
                            <strong>Email:</strong><br>
                            <a href="mailto:<?php echo htmlspecialchars($data['email'] ?? ''); ?>"><?php echo htmlspecialchars($data['email'] ?? 'N/A'); ?></a>
                        </li>
                        <li>
                            <strong>Telepon:</strong><br> 
                            <?php echo htmlspecialchars($data['telepon'] ?? 'N/A'); ?>
                        </li>
                     </ul>
                     <div style="margin-top: 30px;">
                        <a href="<?php echo htmlspecialchars($data['instagram_url'] ?? '#'); ?>" target="_blank" class="btn btn-primary">Kunjungi Instagram</a>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>