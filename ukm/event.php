<?php
/**
 * File: event.php
 * Deskripsi: Halaman ini berfungsi sebagai kalender atau daftar acara dari UKM.
 * Ia menampilkan semua event, baik yang akan datang maupun yang sudah lewat,
 * lengkap dengan poster, tanggal, dan deskripsi singkat. Ini adalah cara yang bagus
 * untuk mempromosikan kegiatan dan menunjukkan keaktifan UKM.
 */

require_once __DIR__ . '/../inc/db.php';

// Validasi ID UKM dari URL
$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /ukm-portfolio/index.php');
    exit;
}

// Ambil nama UKM dan data event
try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();

    if (!$ukm) {
        header('Location: /ukm-portfolio/index.php');
        exit;
    }

    $stmt_event = $pdo->prepare("SELECT * FROM event WHERE id_ukm = ? ORDER BY tanggal_event DESC");
    $stmt_event->execute([$id_ukm]);
    $event_list = $stmt_event->fetchAll();

} catch (PDOException $e) {
    error_log("Gagal mengambil data event: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data event.");
}

$pageTitle = 'Event - ' . htmlspecialchars($ukm['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-title-section" style="padding: 40px 0; background-color: var(--surface-color);">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">Event & Kegiatan</h1>
        <p style="font-size: 1.2rem; color: var(--text-muted-color);"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></p>
    </div>
</div>

<?php 
require_once __DIR__ . '/sub_nav.php'; 
?>

<section id="event-content">
    <div class="container">
        <?php if (!empty($event_list)): ?>
            <div class="event-list" style="display: grid; gap: 30px;">
                <?php foreach ($event_list as $event): ?>
                    <div class="card" style="display: flex; flex-direction: column; md:flex-direction: row; gap: 20px;">
                        <img src="/ukm-portfolio/upload/event/<?php echo htmlspecialchars($event['poster']); ?>" alt="Poster <?php echo htmlspecialchars($event['nama_event']); ?>" style="width: 100%; max-width: 300px; height: auto; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/300x400/EBF4FF/1E40AF?text=Poster';">
                        <div class="card-content">
                            <p style="color: var(--secondary-color); font-weight: 600;"><?php echo date('d F Y', strtotime($event['tanggal_event'])); ?></p>
                            <h2 style="font-size: 1.8rem; margin-top: 5px;"><?php echo htmlspecialchars($event['nama_event']); ?></h2>
                            <p style="color: var(--text-muted-color); margin-top: 10px;"><?php echo nl2br(htmlspecialchars($event['deskripsi'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card"><div class="card-content" style="text-align: center;"><p style="color: var(--text-muted-color); padding: 20px;">Belum ada event yang ditambahkan untuk UKM ini.</p></div></div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
