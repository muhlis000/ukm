<?php
// File BARU: admin/pengurus/detail.php

/**
 * File: detail.php (Pengurus)
 * Deskripsi: Menampilkan daftar pengurus untuk SATU UKM spesifik.
 */

$pageTitle = 'Daftar Pengurus';
require_once __DIR__ . '/../../inc/auth.php';
require_once __DIR__ . '/../../inc/db.php';
$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) { header('Location: list.php'); exit; }

try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();
    if (!$ukm) { header('Location: list.php'); exit; }
    $stmt_pengurus = $pdo->prepare("SELECT * FROM pengurus WHERE id_ukm = ? ORDER BY urutan_jabatan ASC");
    $stmt_pengurus->execute([$id_ukm]);
    $pengurus_list = $stmt_pengurus->fetchAll();
} catch (PDOException $e) { die("Error: Gagal mengambil data. " . $e->getMessage()); }
require_once __DIR__ . '/../../inc/header.php';
?>
<div class="container">
    <h1 class="page-title">Daftar Pengurus: <?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
    <div style="margin-bottom: 20px;">
        <a href="list.php" class="btn">&larr; Kembali ke Daftar</a>
        <a href="tambah.php?id_ukm=<?php echo $id_ukm; ?>" class="btn btn-primary">Tambah Pengurus Baru</a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead><tr><th style="width: 10%;">Foto</th><th>Nama Lengkap</th><th>Jabatan</th><th style="width: 15%;">Aksi</th></tr></thead>
            <tbody>
                <?php if (empty($pengurus_list)): ?>
                    <tr><td colspan="4" style="text-align: center;">Belum ada pengurus untuk UKM ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($pengurus_list as $pengurus): ?>
                    <tr>
                        <td><img src="/ukm-portfolio/upload/pengurus/<?php echo htmlspecialchars($pengurus['foto']); ?>" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                        <td><?php echo htmlspecialchars($pengurus['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($pengurus['jabatan']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $pengurus['id_pengurus']; ?>" class="btn">Edit</a>
                            <a href="hapus.php?id=<?php echo $pengurus['id_pengurus']; ?>&id_ukm=<?php echo $id_ukm; ?>&from=detail" class="btn btn-secondary" onclick="return confirm('Anda yakin?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/../../inc/footer.php'; ?>