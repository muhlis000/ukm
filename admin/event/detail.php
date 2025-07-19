<?php
$pageTitle = 'Daftar Event';
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';
$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) { header('Location: list.php'); exit; }

try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();
    if (!$ukm) { header('Location: list.php'); exit; }
    $stmt_event = $pdo->prepare("SELECT * FROM event WHERE id_ukm = ? ORDER BY tanggal_event DESC");
    $stmt_event->execute([$id_ukm]);
    $event_list = $stmt_event->fetchAll();
} catch (PDOException $e) { die("Error: Gagal mengambil data. " . $e->getMessage()); }
require_once __DIR__ . '/../../inc/header.php';
?>
<div class="container">
    <h1 class="page-title">Daftar Event: <?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
    <div style="margin-bottom: 20px;">
        <a href="list.php" class="btn">&larr; Kembali ke Daftar</a>
        <a href="tambah.php?id_ukm=<?php echo $id_ukm; ?>" class="btn btn-primary">Tambah Event Baru</a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead><tr><th style="width: 15%;">Poster</th><th>Nama Event</th><th>Tanggal</th><th style="width: 15%;">Aksi</th></tr></thead>
            <tbody>
                <?php if (empty($event_list)): ?>
                    <tr><td colspan="4" style="text-align: center;">Belum ada event untuk UKM ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($event_list as $event): ?>
                    <tr>
                        <td><img src="/upload/event/<?php echo htmlspecialchars($event['poster']); ?>" alt="Poster" style="width: 100px; height: auto; border-radius: var(--radius-lg);"></td>
                        <td><?php echo htmlspecialchars($event['nama_event']); ?></td>
                        <td><?php echo date('d M Y', strtotime($event['tanggal_event'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $event['id_event']; ?>" class="btn">Edit</a>
                            <a href="hapus.php?id=<?php echo $event['id_event']; ?>&id_ukm=<?php echo $id_ukm; ?>&from=detail" class="btn btn-secondary" onclick="return confirm('Anda yakin?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../inc/footer.php'; ?>
