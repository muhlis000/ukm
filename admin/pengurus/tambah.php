<?php
$pageTitle = 'Tambah Pengurus';
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
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $urutan_jabatan = $_POST['urutan_jabatan'] ?? 100;
    $foto_filename = '';

    // Proses upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $target_dir = __DIR__ . "/../../upload/pengurus/";
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $foto_filename = uniqid('pengurus_', true) . '.' . $file_extension;
        $target_file = $target_dir . $foto_filename;

        // Validasi file
        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            $error_message = "Gagal mengupload foto.";
        }
    }

    // Simpan ke database jika tidak ada error
    if (empty($error_message) && !empty($nama_lengkap)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO pengurus (id_ukm, nama_lengkap, jabatan, foto, urutan_jabatan) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_ukm, $nama_lengkap, $jabatan, $foto_filename, $urutan_jabatan]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Data pengurus berhasil ditambahkan!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menyimpan data: " . $e->getMessage();
        }
    } elseif(empty($nama_lengkap)) {
        $error_message = "Nama lengkap tidak boleh kosong.";
    }
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $id_ukm; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Pengurus</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="tambah.php?id_ukm=<?php echo $id_ukm; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            <div class="form-group">
                <label for="urutan_jabatan">Urutan Tampil</label>
                <input type="number" id="urutan_jabatan" name="urutan_jabatan" value="100">
                <small>Angka lebih kecil akan tampil lebih dulu.</small>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
