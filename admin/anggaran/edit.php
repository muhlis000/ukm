<?php
/**
 * File: edit.php (Anggaran)
 * Deskripsi: Formulir untuk mengubah data transaksi anggaran yang ada.
 */

$pageTitle = 'Edit Transaksi Anggaran';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_anggaran = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_anggaran) {
    header('Location: /ukm-portfolio/admin/ukm/list.php'); exit;
}

require_once __DIR__ . '/../../inc/header.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $jenis = $_POST['jenis'] ?? '';
    $jumlah = filter_var($_POST['jumlah'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    $id_ukm = $_POST['id_ukm'] ?? 0;

    if (!empty($tanggal) && !empty($keterangan) && $jumlah > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE anggaran SET tanggal=?, keterangan=?, jenis=?, jumlah=? WHERE id_anggaran=?");
            $stmt->execute([$tanggal, $keterangan, $jenis, $jumlah, $id_anggaran]);

            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Transaksi berhasil diperbarui!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

// Ambil data anggaran yang akan diedit
try {
    $stmt = $pdo->prepare("SELECT * FROM anggaran WHERE id_anggaran = ?");
    $stmt->execute([$id_anggaran]);
    $anggaran = $stmt->fetch();
    if (!$anggaran) {
        header('Location: /ukm-portfolio/admin/ukm/list.php'); exit;
    }
} catch (PDOException $e) {
    die("Gagal mengambil data anggaran.");
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $anggaran['id_ukm']; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Anggaran</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $id_anggaran; ?>" method="POST">
            <input type="hidden" name="id_ukm" value="<?php echo $anggaran['id_ukm']; ?>">
            
            <div class="form-group">
                <label for="tanggal">Tanggal Transaksi</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($anggaran['tanggal']); ?>" required>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" value="<?php echo htmlspecialchars($anggaran['keterangan']); ?>" required>
            </div>
             <div class="form-group">
                <label for="jenis">Jenis Transaksi</label>
                <select id="jenis" name="jenis" required>
                    <option value="pemasukan" <?php echo ($anggaran['jenis'] == 'pemasukan') ? 'selected' : ''; ?>>Pemasukan</option>
                    <option value="pengeluaran" <?php echo ($anggaran['jenis'] == 'pengeluaran') ? 'selected' : ''; ?>>Pengeluaran</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah (IDR)</label>
                <input type="number" id="jumlah" name="jumlah" value="<?php echo htmlspecialchars($anggaran['jumlah']); ?>" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Transaksi</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
