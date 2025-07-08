<?php
/**
 * File: anggaran.php
 * Deskripsi: Menyajikan rincian anggaran UKM untuk transparansi. Halaman ini
 * menampilkan tabel yang berisi sumber dana, jumlah, dan deskripsi alokasi.
 * Ini membantu anggota dan pihak terkait untuk memahami kesehatan finansial
 * dan perencanaan keuangan dari UKM tersebut.
 */

require_once __DIR__ . '/../inc/db.php';

// Validasi ID UKM dari URL
$id_ukm = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /ukm-portfolio/index.php');
    exit;
}

// Ambil nama UKM dan data anggaran
try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();

    if (!$ukm) {
        header('Location: /ukm-portfolio/index.php');
        exit;
    }

    $stmt_anggaran = $pdo->prepare("SELECT * FROM anggaran WHERE id_ukm = ? ORDER BY tanggal DESC");
    $stmt_anggaran->execute([$id_ukm]);
    $anggaran_list = $stmt_anggaran->fetchAll();

} catch (PDOException $e) {
    error_log("Gagal mengambil data anggaran: " . $e->getMessage());
    die("Terjadi kesalahan saat memuat data anggaran.");
}

$pageTitle = 'Anggaran - ' . htmlspecialchars($ukm['nama_ukm']);
require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-title-section" style="padding: 40px 0; background-color: var(--surface-color);">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 0;">Rincian Anggaran</h1>
        <p style="font-size: 1.2rem; color: var(--text-muted-color);"><?php echo htmlspecialchars($ukm['nama_ukm']); ?></p>
    </div>
</div>

<?php 
// Memanggil komponen sub-navigasi
require_once __DIR__ . '/sub_nav.php'; 
?>

<section id="anggaran-content">
    <div class="container">
        <div class="card">
            <div class="card-content">
                <?php if (!empty($anggaran_list)): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Tanggal</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: left;">Keterangan</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: right;">Pemasukan (IDR)</th>
                                <th style="padding: 12px; border: 1px solid var(--border-color); text-align: right;">Pengeluaran (IDR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_pemasukan = 0;
                            $total_pengeluaran = 0;
                            foreach ($anggaran_list as $item): 
                                $is_pemasukan = $item['jenis'] == 'pemasukan';
                                $total_pemasukan += $is_pemasukan ? $item['jumlah'] : 0;
                                $total_pengeluaran += !$is_pemasukan ? $item['jumlah'] : 0;
                            ?>
                                <tr>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo date('d M Y', strtotime($item['tanggal'])); ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color);"><?php echo htmlspecialchars($item['keterangan']); ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color); text-align: right;"><?php echo $is_pemasukan ? number_format($item['jumlah'], 0, ',', '.') : '-'; ?></td>
                                    <td style="padding: 12px; border: 1px solid var(--border-color); text-align: right;"><?php echo !$is_pemasukan ? number_format($item['jumlah'], 0, ',', '.') : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot style="font-weight: bold;">
                            <tr>
                                <td colspan="2" style="padding: 12px; border: 1px solid var(--border-color); text-align: right;">Total</td>
                                <td style="padding: 12px; border: 1px solid var(--border-color); text-align: right;"><?php echo number_format($total_pemasukan, 0, ',', '.'); ?></td>
                                <td style="padding: 12px; border: 1px solid var(--border-color); text-align: right;"><?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 12px; border: 1px solid var(--border-color); text-align: right;">Saldo Akhir</td>
                                <td style="padding: 12px; border: 1px solid var(--border-color); text-align: right; background-color: #f0f4f8;"><?php echo number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-muted-color); padding: 20px;">Belum ada data anggaran yang ditambahkan untuk UKM ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
