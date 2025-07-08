<?php
/**
 * File: edit.php (UKM)
 * Deskripsi: Formulir untuk mengedit data UKM yang sudah ada.
 * Logikanya lebih kompleks karena harus:
 * 1. Mengambil data saat ini untuk mengisi form.
 * 2. Menangani pembaruan data teks.
 * 3. Menangani pembaruan logo (jika ada file baru yang diunggah), termasuk
 * menghapus file logo lama dari server untuk menghemat ruang.
 */

$pageTitle = 'Edit UKM';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php'); exit;
}

// Ambil data kategori untuk dropdown
$kategori_list = $pdo->query("SELECT id_kategori, nama_kategori FROM kategori_ukm ORDER BY nama_kategori")->fetchAll();

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data
    $nama_ukm = $_POST['nama_ukm'] ?? '';
    $id_kategori = $_POST['id_kategori'] ?? null;
    $slogan = $_POST['slogan'] ?? '';
    $deskripsi_panjang = $_POST['deskripsi_panjang'] ?? '';
    $visi = $_POST['visi'] ?? '';
    $misi = $_POST['misi'] ?? '';
    $current_logo = $_POST['current_logo'] ?? '';
    $logo_filename = $current_logo; // Default ke logo saat ini

    // Cek apakah ada logo baru yang diupload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // Logika upload sama seperti tambah.php
        $file = $_FILES['logo'];
        $target_dir = __DIR__ . "/../../upload/logo/";
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_logo_filename = uniqid('logo_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_logo_filename;

        // Lakukan validasi lagi
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Hapus logo lama jika ada & jika berhasil upload yg baru
            if (!empty($current_logo) && file_exists($target_dir . $current_logo)) {
                unlink($target_dir . $current_logo);
            }
            $logo_filename = $new_logo_filename; // Gunakan nama file baru
        } else {
            $error_message = "Gagal mengupload logo baru.";
        }
    }
    
    // Update ke database jika tidak ada error
    if (empty($error_message) && !empty($nama_ukm)) {
        try {
            $sql = "UPDATE ukm SET nama_ukm=?, id_kategori=?, slogan=?, logo=?, deskripsi_panjang=?, visi=?, misi=? WHERE id_ukm=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_ukm, $id_kategori, $slogan, $logo_filename, $deskripsi_panjang, $visi, $misi, $id]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'UKM berhasil diperbarui!'];
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui data: " . $e->getMessage();
        }
    } elseif(empty($nama_ukm)) {
        $error_message = "Nama UKM tidak boleh kosong.";
    }
}

// Ambil data UKM yang akan diedit
try {
    $stmt = $pdo->prepare("SELECT * FROM ukm WHERE id_ukm = ?");
    $stmt->execute([$id]);
    $ukm = $stmt->fetch();
    if (!$ukm) {
        header('Location: list.php'); exit;
    }
} catch(PDOException $e) {
    die("Gagal mengambil data UKM.");
}
?>

<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($ukm['logo']); ?>">
            
            <div class="form-group">
                <label for="nama_ukm">Nama UKM</label>
                <input type="text" id="nama_ukm" name="nama_ukm" value="<?php echo htmlspecialchars($ukm['nama_ukm']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="id_kategori">Kategori</label>
                <select id="id_kategori" name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $kategori): ?>
                        <option value="<?php echo $kategori['id_kategori']; ?>" <?php echo ($kategori['id_kategori'] == $ukm['id_kategori']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="logo">Ganti Logo (Opsional)</label>
                <p>Logo saat ini: <img src="/ukm-portfolio/upload/logo/<?php echo htmlspecialchars($ukm['logo']); ?>" alt="Logo saat ini" style="width: 60px; height: 60px; vertical-align: middle; margin-left: 10px;"></p>
                <input type="file" id="logo" name="logo" accept="image/*">
                <small>Kosongkan jika tidak ingin mengganti logo.</small>
            </div>
            
            <!-- field lainnya -->
            <div class="form-group"><label for="slogan">Slogan</label><input type="text" id="slogan" name="slogan" value="<?php echo htmlspecialchars($ukm['slogan']); ?>"></div>
            <div class="form-group"><label for="deskripsi_panjang">Deskripsi Lengkap</label><textarea id="deskripsi_panjang" name="deskripsi_panjang" rows="6"><?php echo htmlspecialchars($ukm['deskripsi_panjang']); ?></textarea></div>
            <div class="form-group"><label for="visi">Visi</label><textarea id="visi" name="visi" rows="3"><?php echo htmlspecialchars($ukm['visi']); ?></textarea></div>
            <div class="form-group"><label for="misi">Misi</label><textarea id="misi" name="misi" rows="5"><?php echo htmlspecialchars($ukm['misi']); ?></textarea></div>
            
            <button type="submit" class="btn btn-primary">Update UKM</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
