<?php
/**
 * File: edit.php (Kontak - Versi Final dengan Video)
 * Deskripsi: Halaman utama untuk mengelola informasi kontak UKM.
 * File ini menangani baik pembuatan data kontak baru (jika belum ada)
 * maupun pembaruan data yang sudah ada, termasuk URL video profil.
 */

$pageTitle = 'Manajemen Kontak';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. Panggil koneksi database
require_once __DIR__ . '/../../inc/db.php';

// Validasi ID UKM dari URL. Harus dilakukan SEBELUM memanggil header
// untuk mencegah "headers already sent" error jika terjadi redirect.
$id_ukm = filter_input(INPUT_GET, 'id_ukm', FILTER_VALIDATE_INT);
if (!$id_ukm) {
    header('Location: list.php');
    exit;
}

// 3. Panggil header setelah semua logika awal selesai
require_once __DIR__ . '/../../inc/header.php';

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua data dari form
    $video_url = $_POST['video_profil_url'] ?? '';
    $email = $_POST['email'] ?? '';
    $telepon = $_POST['telepon'] ?? '';
    $instagram_url = $_POST['instagram_url'] ?? '';

    try {
        // Cek apakah data kontak untuk UKM ini sudah ada
        $stmt_check = $pdo->prepare("SELECT id_kontak FROM kontak WHERE id_ukm = ?");
        $stmt_check->execute([$id_ukm]);
        $exists = $stmt_check->fetchColumn();

        if ($exists) {
            // Jika ada, UPDATE data
            $sql = "UPDATE kontak SET video_profil_url=?, email=?, telepon=?, instagram_url=? WHERE id_ukm=?";
            $params = [$video_url, $email, $telepon, $instagram_url, $id_ukm];
        } else {
            // Jika tidak ada, INSERT data baru
            $sql = "INSERT INTO kontak (id_ukm, video_profil_url, email, telepon, instagram_url) VALUES (?, ?, ?, ?, ?)";
            $params = [$id_ukm, $video_url, $email, $telepon, $instagram_url];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Informasi kontak berhasil diperbarui!'];
        // Arahkan kembali ke halaman daftar kontak setelah update
        header('Location: list.php');
        exit;

    } catch (PDOException $e) {
        $error_message = "Gagal memperbarui data: " . $e->getMessage();
    }
}

// Ambil data UKM dan data kontak yang ada untuk ditampilkan di form
try {
    $stmt_ukm = $pdo->prepare("SELECT nama_ukm FROM ukm WHERE id_ukm = ?");
    $stmt_ukm->execute([$id_ukm]);
    $ukm = $stmt_ukm->fetch();
    if (!$ukm) {
        header('Location: list.php'); exit;
    }

    // Ambil data kontak yang ada untuk UKM ini
    $stmt_kontak = $pdo->prepare("SELECT * FROM kontak WHERE id_ukm = ?");
    $stmt_kontak->execute([$id_ukm]);
    $kontak = $stmt_kontak->fetch();

} catch (PDOException $e) {
    die("Error: Gagal mengambil data. " . $e->getMessage());
}
?>

<div class="container">
    <h1 class="page-title">Edit Kontak: <?php echo htmlspecialchars($ukm['nama_ukm']); ?></h1>
    <div style="margin-bottom: 20px;">
        <a href="list.php" class="btn">&larr; Kembali ke Daftar Kontak</a>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="flash-message flash-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form action="edit.php?id_ukm=<?php echo $id_ukm; ?>" method="POST">
            <div class="form-group">
                <label for="video_profil_url">URL Video Profil (YouTube)</label>
                <input type="url" id="video_profil_url" name="video_profil_url" value="<?php echo htmlspecialchars($kontak['video_profil_url'] ?? ''); ?>" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($kontak['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input type="text" id="telepon" name="telepon" value="<?php echo htmlspecialchars($kontak['telepon'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="instagram_url">URL Instagram</label>
                <input type="url" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/nama_ukm" value="<?php echo htmlspecialchars($kontak['instagram_url'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
