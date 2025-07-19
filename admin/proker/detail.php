<?php 
$pageTitle = 'Riwayat Program Kerja';

require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';

$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) { header('Location: list.php'); exit; }

try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();
    if (!$ukm) { header('Location: list.php'); exit; }

    $stmt_proker = $pdo->prepare("SELECT * FROM proker WHERE id_ukm = ? ORDER BY tanggal_pelaksanaan DESC");
    $stmt_proker->execute([$id_ukm]);
    $proker_list = $stmt_proker->fetchAll();
} catch (PDOException $e) { die("Error: Gagal mengambil data. " . $e->getMessage()); }

require_once __DIR__ . '/../../inc/header.php';
?>

<div class="container">
    <h1 class="page-title">Riwayat Proker: <?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
    <div style="margin-bottom: 20px;">
        <a href="list.php" class="btn">&larr; Kembali ke Daftar</a>
        <a href="tambah.php?id_ukm=<?php echo $id_ukm; ?>" class="btn btn-primary">Tambah Proker Baru</a>
    </div>
    
    <!-- (Tempat untuk flash message) -->

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Program</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($proker_list)): ?>
                    <tr><td colspan="4" style="text-align: center;">Belum ada proker untuk UKM ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($proker_list as $proker): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proker['nama_proker']); ?></td>
                            <td><?php echo date('d M Y', strtotime($proker['tanggal_pelaksanaan'])); ?></td>
                            <td><?php echo htmlspecialchars($proker['status']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $proker['id_proker']; ?>" class="btn">Edit</a>
                                <a href="hapus.php?id=<?php echo $proker['id_proker']; ?>&id_ukm=<?php echo $id_ukm; ?>&from=detail" class="btn btn-secondary" onclick="return confirm('Anda yakin?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../inc/footer.php'; ?>