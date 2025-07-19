<?php
require_once __DIR__ . '/../inc/db.php';

// Validasi ID UKM dari URL
$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /index.php');
    exit;
}

// Ambil nama UKM untuk judul halaman dan data proker
try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();

    if (!$ukm) {
        header('Location: /index.php');
        exit;
    }

    $stmt_proker = $pdo->prepare("SELECT * FROM proker WHERE id_ukm = ? ORDER BY tanggal_pelaksanaan ASC");
    $stmt_proker->execute([$id_ukm]);
    $proker_list = $stmt_proker->fetchAll();

} catch (PDOException $e) {
    error_log("Gagal mengambil data proker: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data Program Kerja.");
}

$pageTitle = 'Program Kerja - ' . htmlspecialchars($ukm['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-title-section" style="padding: 40px 0; background-color: var(--surface-color);">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">Program Kerja</h1>
        <p style="font-size: 1.2rem; color: var(--text-muted-color);"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></p>
    </div>
</div>

<?php 
// Memanggil komponen sub-navigasi
require_once __DIR__ . '/sub_nav.php'; 
?>

<section id="proker-content">
    <div class="container">
        <div class="card">
            <div class="card-content">
                <?php if (!empty($proker_list)): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background-color: #f9f9f9;">
                            <tr>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Nama Program</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Deskripsi</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Tanggal Pelaksanaan</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proker_list as $proker): ?>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo htmlspecialchars($proker['nama_proker']); ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo htmlspecialchars($proker['deskripsi']); ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo date('d F Y', strtotime($proker['tanggal_pelaksanaan'])); ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo htmlspecialchars($proker['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-muted-color); padding: 20px;">Belum ada program kerja yang ditambahkan untuk UKM ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
