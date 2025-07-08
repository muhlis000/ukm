<?php
/**
 * File: tambah.php (Proker)
 * Deskripsi: Menyediakan formulir untuk menambah proker baru.
 * ID UKM diambil dari URL untuk memastikan proker ditambahkan ke UKM yang benar.
 */

$pageTitle = 'Tambah Program Kerja';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: /ukm-portfolio/admin/ukm/list.php');
    exit;
}

require_once __DIR__ . '/../../inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_proker = $_POST['nama_proker'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'] ?? '';
    $status = $_POST['status'] ?? 'direncanakan';

    if (!empty($nama_proker) && !empty($tanggal_pelaksanaan)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO proker (id_ukm, nama_proker, deskripsi, tanggal_pelaksanaan, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_ukm, $nama_proker, $deskripsi, $tanggal_pelaksanaan, $status]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Program kerja berhasil ditambahkan!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        $error_message = "Nama program dan tanggal pelaksanaan tidak boleh kosong.";
    }
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $id_ukm; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Proker</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="tambah.php?id_ukm=<?php echo $id_ukm; ?>" method="POST">
            <div class="form-group">
                <label for="nama_proker">Nama Program Kerja</label>
                <input type="text" id="nama_proker" name="nama_proker" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan</label>
                <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Direncanakan">Direncanakan</option>
                    <option value="Berlangsung">Berlangsung</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
