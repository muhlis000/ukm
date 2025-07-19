<?php
$pageTitle = 'Tambah Kategori';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi sederhana
    if (!empty($nama_kategori)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO kategori_ukm (nama_kategori, deskripsi) VALUES (?, ?)");
            $stmt->execute([$nama_kategori, $deskripsi]);

            // Set flash message untuk notifikasi sukses
            $_SESSION['flash_message'] = [
                'type' => 'flash-success',
                'text' => 'Kategori berhasil ditambahkan!'
            ];
            
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menambahkan kategori: " . $e->getMessage();
        }
    } else {
        $error_message = "Nama kategori tidak boleh kosong.";
    }
}
?>

<h1 class="page-title"><?php echo $pageTitle; ?></h1>
<a href="list.php" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

<div class="form-container">
    <?php if (isset($error_message)): ?>
        <div class="flash-message flash-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="tambah.php" method="POST">
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
