<?php
/**
 * File: tambah.php (Anggaran)
 * Deskripsi: Menyediakan formulir untuk menambah transaksi anggaran baru.
 */

$pageTitle = 'Tambah Transaksi Anggaran';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /admin/ukm/list.php');
    exit;
}

require_once __DIR__ . '/../../inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $jenis = $_POST['jenis'] ?? '';
    $jumlah = $_POST['jumlah'] ?? 0;
    
    // Hapus format non-numerik dari jumlah
    $jumlah = filter_var($jumlah, FILTER_SANITIZE_NUMBER_INT);

    if (!empty($tanggal) && !empty($keterangan) && !empty($jenis) && $jumlah > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO anggaran (id_ukm, tanggal, keterangan, jenis, jumlah) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_ukm, $tanggal, $keterangan, $jenis, $jumlah]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Transaksi berhasil ditambahkan!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        $error_message = "Semua field harus diisi dan jumlah harus lebih besar dari 0.";
    }
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $id_ukm; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Anggaran</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="tambah.php?id_ukm=<?php echo $id_ukm; ?>" method="POST">
            <div class="form-group">
                <label for="tanggal">Tanggal Transaksi</label>
                <input type="date" id="tanggal" name="tanggal" required>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" id="keterangan" name="keterangan" required>
            </div>
             <div class="form-group">
                <label for="jenis">Jenis Transaksi</label>
                <select id="jenis" name="jenis" required>
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah (IDR)</label>
                <input type="number" id="jumlah" name="jumlah" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
