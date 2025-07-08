<?php
/**
 * File: edit.php (Proker)
 * Deskripsi: Formulir untuk mengubah data proker yang ada.
 */

$pageTitle = 'Edit Program Kerja';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_proker = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_proker) {
    header('Location: /ukm-portfolio/admin/ukm/list.php');
    exit;
}

require_once __DIR__ . '/../../inc/header.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_proker = $_POST['nama_proker'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tanggal_pelaksanaan = $_POST['tanggal_pelaksanaan'] ?? '';
    $status = $_POST['status'] ?? '';
    $id_ukm = $_POST['id_ukm'] ?? 0;

    if (!empty($nama_proker) && !empty($tanggal_pelaksanaan)) {
        try {
            $stmt = $pdo->prepare("UPDATE proker SET nama_proker=?, deskripsi=?, tanggal_pelaksanaan=?, status=? WHERE id_proker=?");
            $stmt->execute([$nama_proker, $deskripsi, $tanggal_pelaksanaan, $status, $id_proker]);

            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Program kerja berhasil diperbarui!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui data: " . $e->getMessage();
        }
    } else {
        $error_message = "Nama program dan tanggal pelaksanaan tidak boleh kosong.";
    }
}

// Ambil data proker yang akan diedit
try {
    $stmt = $pdo->prepare("SELECT * FROM proker WHERE id_proker = ?");
    $stmt->execute([$id_proker]);
    $proker = $stmt->fetch();
    if (!$proker) {
        header('Location: /ukm-portfolio/admin/ukm/list.php'); exit;
    }
} catch (PDOException $e) {
    die("Gagal mengambil data proker.");
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $proker['id_ukm']; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Proker</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $id_proker; ?>" method="POST">
            <input type="hidden" name="id_ukm" value="<?php echo $proker['id_ukm']; ?>">
            <div class="form-group">
                <label for="nama_proker">Nama Program Kerja</label>
                <input type="text" id="nama_proker" name="nama_proker" value="<?php echo htmlspecialchars($proker['nama_proker']); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5"><?php echo htmlspecialchars($proker['deskripsi']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_pelaksanaan">Tanggal Pelaksanaan</label>
                <input type="date" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" value="<?php echo htmlspecialchars($proker['tanggal_pelaksanaan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <?php $statuses = ['Direncanakan', 'Berlangsung', 'Selesai', 'Dibatalkan']; ?>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo ($proker['status'] == $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
