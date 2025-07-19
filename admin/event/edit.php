<?php
$pageTitle = 'Edit Event';
// 1. Panggil script otentikasi
require_once __DIR__ . '/../../inc/auth.php';
// 2. PANGGIL KONEKSI DATABASE (INI YANG HILANG)
require_once __DIR__ . '/../../inc/db.php';
// 3. Panggil header setelah koneksi siap
require_once __DIR__ . '/../../inc/header.php';

$id_event = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_event) {
    header('Location: /admin/ukm/list.php'); exit;
}

require_once __DIR__ . '/../../inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_event = $_POST['nama_event'] ?? '';
    $tanggal_event = $_POST['tanggal_event'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $id_ukm = $_POST['id_ukm'] ?? 0;
    $current_poster = $_POST['current_poster'] ?? '';
    $poster_filename = $current_poster;

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['poster'];
        $target_dir = __DIR__ . "/../../upload/event/";
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_poster_filename = uniqid('event_', true) . '.' . $file_extension;
        $target_file = $target_dir . $new_poster_filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            if (!empty($current_poster) && file_exists($target_dir . $current_poster)) {
                unlink($target_dir . $current_poster);
            }
            $poster_filename = $new_poster_filename;
        } else {
            $error_message = "Gagal mengupload poster baru.";
        }
    }
    
    if (empty($error_message) && !empty($nama_event)) {
        try {
            $stmt = $pdo->prepare("UPDATE event SET nama_event=?, tanggal_event=?, deskripsi=?, poster=? WHERE id_event=?");
            $stmt->execute([$nama_event, $tanggal_event, $deskripsi, $poster_filename, $id_event]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Event berhasil diperbarui!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

// Ambil data event yang akan diedit
try {
    $stmt = $pdo->prepare("SELECT * FROM event WHERE id_event = ?");
    $stmt->execute([$id_event]);
    $event = $stmt->fetch();
    if (!$event) {
        header('Location: /admin/ukm/list.php'); exit;
    }
} catch (PDOException $e) {
    die("Gagal mengambil data event.");
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $event['id_ukm']; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Event</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $id_event; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_ukm" value="<?php echo $event['id_ukm']; ?>">
            <input type="hidden" name="current_poster" value="<?php echo htmlspecialchars($event['poster']); ?>">
            
            <div class="form-group">
                <label for="nama_event">Nama Event</label>
                <input type="text" id="nama_event" name="nama_event" value="<?php echo htmlspecialchars($event['nama_event']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_event">Tanggal Event</label>
                <input type="date" id="tanggal_event" name="tanggal_event" value="<?php echo htmlspecialchars($event['tanggal_event']); ?>" required>
            </div>
             <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5"><?php echo htmlspecialchars($event['deskripsi']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="poster">Ganti Poster (Opsional)</label>
                <p><img src="/upload/event/<?php echo htmlspecialchars($event['poster']); ?>" alt="Poster saat ini" style="max-width: 150px;"></p>
                <input type="file" id="poster" name="poster" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
