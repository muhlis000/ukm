<?php

$pageTitle = 'Tambah UKM';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Ambil daftar kategori untuk dropdown
try {
    $kategori_stmt = $pdo->query("SELECT id_kategori, nama_kategori FROM kategori_ukm ORDER BY nama_kategori");
    $kategori_list = $kategori_stmt->fetchAll();
} catch(PDOException $e) {
    die("Gagal mengambil data kategori.");
}


// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data teks
    $nama_ukm = $_POST['nama_ukm'] ?? '';
    $id_kategori = $_POST['id_kategori'] ?? null;
    $slogan = $_POST['slogan'] ?? '';
    $deskripsi_panjang = $_POST['deskripsi_panjang'] ?? '';
    $visi = $_POST['visi'] ?? '';
    $misi = $_POST['misi'] ?? '';
    $logo_filename = '';

    // Proses upload logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['logo'];
        $target_dir = __DIR__ . "/../../upload/logo/";
        
        // Buat nama file yang unik untuk menghindari penimpaan file
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $logo_filename = uniqid('logo_', true) . '.' . $file_extension;
        $target_file = $target_dir . $logo_filename;

        // Validasi file (contoh: ukuran dan tipe)
        if ($file['size'] > 2000000) { // 2MB
            $error_message = "Maaf, ukuran file terlalu besar.";
        } elseif (!in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
            $error_message = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        } else {
            if (!move_uploaded_file($file['tmp_name'], $target_file)) {
                $error_message = "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }
    }

    // Jika tidak ada error, simpan ke database
    if (empty($error_message) && !empty($nama_ukm)) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO ukm (nama_ukm, id_kategori, slogan, logo, deskripsi_panjang, visi, misi) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$nama_ukm, $id_kategori, $slogan, $logo_filename, $deskripsi_panjang, $visi, $misi]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'UKM berhasil ditambahkan!'];
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menyimpan data UKM: " . $e->getMessage();
        }
    } elseif(empty($nama_ukm)) {
         $error_message = "Nama UKM tidak boleh kosong.";
    }
}
?>
<div class="container">
<h1 class="page-title"><?php echo $pageTitle; ?></h1>
<a href="list.php" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

<div class="form-container">
    <?php if (isset($error_message)): ?>
        <div class="flash-message flash-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- PENTING: enctype="multipart/form-data" diperlukan untuk upload file -->
    <form action="tambah.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_ukm">Nama UKM</label>
            <input type="text" id="nama_ukm" name="nama_ukm" required>
        </div>
        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select id="id_kategori" name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach($kategori_list as $kategori): ?>
                    <option value="<?php echo $kategori['id_kategori']; ?>"><?php echo htmlspecialchars($kategori['nama_kategori']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="slogan">Slogan</label>
            <input type="text" id="slogan" name="slogan">
        </div>
        <div class="form-group">
            <label for="logo">Logo UKM</label>
            <input type="file" id="logo" name="logo" accept="image/*">
        </div>
        <div class="form-group">
            <label for="deskripsi_panjang">Deskripsi Lengkap</label>
            <textarea id="deskripsi_panjang" name="deskripsi_panjang" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="visi">Visi</label>
            <textarea id="visi" name="visi" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="misi">Misi</label>
            <textarea id="misi" name="misi" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan UKM</button>
    </form>
</div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
