<?php
$pageTitle = 'Tambah Event';
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
    $nama_event = $_POST['nama_event'] ?? '';
    $tanggal_event = $_POST['tanggal_event'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $poster_filename = '';

    // Proses upload poster
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['poster'];
        $target_dir = __DIR__ . "/../../upload/event/";
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $poster_filename = uniqid('event_', true) . '.' . $file_extension;
        $target_file = $target_dir . $poster_filename;

        if (!move_uploaded_file($file['tmp_name'], $target_file)) {
            $error_message = "Gagal mengupload poster.";
        }
    }

    if (empty($error_message) && !empty($nama_event)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO event (id_ukm, nama_event, tanggal_event, deskripsi, poster) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_ukm, $nama_event, $tanggal_event, $deskripsi, $poster_filename]);
            
            $_SESSION['flash_message'] = ['type' => 'flash-success', 'text' => 'Event berhasil ditambahkan!'];
            header('Location: list.php?id_ukm=' . $id_ukm);
            exit;
        } catch (PDOException $e) {
            $error_message = "Gagal menyimpan data: " . $e->getMessage();
        }
    } elseif(empty($nama_event)) {
        $error_message = "Nama event tidak boleh kosong.";
    }
}
?>
<div class="container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <a href="list.php?id_ukm=<?php echo $id_ukm; ?>" class="btn" style="margin-bottom: 20px;">&larr; Kembali ke Daftar Event</a>

    <div class="form-container">
        <?php if (isset($error_message)): ?>
            <div class="flash-message flash-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="tambah.php?id_ukm=<?php echo $id_ukm; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_event">Nama Event</label>
                <input type="text" id="nama_event" name="nama_event" required>
            </div>
            <div class="form-group">
                <label for="tanggal_event">Tanggal Event</label>
                <input type="date" id="tanggal_event" name="tanggal_event" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="poster">Poster Event</label>
                <input type="file" id="poster" name="poster" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Event</button>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/../../inc/footer.php';
?>
