<?php
$pageTitle = 'Edit Pengurus';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_pengurus = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_pengurus) {
    header('Location: /admin/ukm/list.php'); exit;
}

require_once __DIR__ . '/../../inc/header.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $urutan_jabatan = $_POST['urutan_jabatan'] ?? 100;
    $id_ukm = $_POST['id_ukm'] ?? 0;
    $current_foto = $_POST['current_foto'] ?? '';
    $foto_filename = $current_foto;

    // Cek upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $target_dir = __DIR__ . "/../../upload/pengurus/";
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_foto_filename = uniqid('pengurus_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_foto_filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            if (!empty($current_foto) && file_exists($target_dir . $current_foto)) {
                unlink($target_dir . $current_foto);
            }
            $foto_filename = $new_foto_filename;
        } else {
            $error_message = "Gagal mengupload foto baru.";
        }
    }
    
    // Update database
    if (empty($error_message) && !empty($nama_lengkap)) {
        try {
            $stmt = $pdo->prepare("UPDATE pengurus SET nama_lengkap=?, jabatan=?, foto=?, urutan_jabatan=? WHERE id_pengurus=?");
            $stmt->execute([$nama_lengkap, $jabatan, $foto_filename, $urutan_jabatan, $id_pengurus]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Data pengurus berhasil diperbarui!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

// Ambil data pengurus yang akan diedit
try {
    $stmt = $pdo->prepare("SELECT * FROM pengurus WHERE id_pengurus = ?");
    $stmt->execute([$id_pengurus]);
    $pengurus = $stmt->fetch();
    if (!$pengurus) {
        header('Location: /admin/ukm/list.php'); exit;
    }
} catch (PDOException $e) {
    die("Gagal mengambil data pengurus.");
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $pengurus['id_ukm']; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Pengurus</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $id_pengurus; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_ukm" value="<?php echo $pengurus['id_ukm']; ?>">
            <input type="hidden" name="current_foto" value="<?php echo htmlspecialchars($pengurus['foto']); ?>">
            
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($pengurus['nama_lengkap']); ?>" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($pengurus['jabatan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Ganti Foto (Opsional)</label>
                <p><img src="/upload/pengurus/<?php echo htmlspecialchars($pengurus['foto']); ?>" alt="Foto saat ini" style="width: 80px; height: 80px;"></p>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            <div class="form-group">
                <label for="urutan_jabatan">Urutan Tampil</label>
                <input type="number" id="urutan_jabatan" name="urutan_jabatan" value="<?php echo htmlspecialchars($pengurus['urutan_jabatan']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
