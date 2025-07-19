<?php
$pageTitle = 'Edit Kategori';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

// Validasi ID dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = $_POST['nama_kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $id_kategori = $_POST['id_kategori'] ?? 0;

    if (!empty($nama_kategori) && $id_kategori == $id) {
        try {
            $stmt = $pdo->prepare("UPDATE kategori_ukm SET nama_kategori = ?, deskripsi = ? WHERE id_kategori = ?");
            $stmt->execute([$nama_kategori, $deskripsi, $id]);

            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Kategori berhasil diperbarui!'];
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui kategori: " . $e->getMessage();
        }
    } else {
        $error_message = "Nama kategori tidak boleh kosong.";
    }
}

// Ambil data yang akan diedit dari database
try {
    $stmt = $pdo->prepare("SELECT * FROM kategori_ukm WHERE id_kategori = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();
    if (!$kategori) {
        header('Location: list.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error: Tidak dapat menemukan data kategori.");
}
?>

<h1 class="page-title"><?php echo $pageTitle; ?></h1>
<a href="list.php" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

<div class="form-container">
    <?php if (isset($error_message)): ?>
        <div class="flash-message flash-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <input type="hidden" name="id_kategori" value="<?php echo $kategori['id_kategori']; ?>">
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" id="nama_kategori" name="nama_kategori" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($kategori['deskripsi']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
