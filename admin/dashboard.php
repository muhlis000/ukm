<?php
/**
 * File: dashboard.php
 * Deskripsi: Halaman utama atau beranda dari panel admin.
 * Halaman ini adalah halaman pertama yang dilihat admin setelah login.
 * Ia menampilkan pesan selamat datang dan menyediakan link-link navigasi utama
 * ke berbagai modul manajemen (CRUD) seperti Kategori, UKM, Pengurus, dll.
 * Halaman ini dilindungi oleh auth.php.
 */

// Langkah 1: Proteksi Halaman
// File auth.php akan memeriksa apakah admin sudah login.
// Jika belum, pengguna akan dialihkan ke halaman login.
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/db.php';

// (Opsional) Ambil beberapa statistik ringkas untuk ditampilkan
try {
    $total_ukm = $pdo->query("SELECT COUNT(*) FROM ukm")->fetchColumn();
    $total_kategori = $pdo->query("SELECT COUNT(*) FROM kategori_ukm")->fetchColumn();
    $total_event = $pdo->query("SELECT COUNT(*) FROM event")->fetchColumn();
} catch (PDOException $e) {
    // Jika gagal, set ke 0
    $total_ukm = $total_kategori = $total_event = 0;
    error_log("Dashboard stat error: " . $e->getMessage());
}


$pageTitle = 'Admin Dashboard';
// Untuk admin, kita bisa membuat header & footer terpisah atau kondisional.
// Demi kesederhanaan, kita gunakan header utama dan tambahkan style admin.
require_once __DIR__ . '/../inc/header.php'; // Menggunakan header publik
?>

<!-- Menambahkan beberapa style khusus untuk area admin -->
<style>
.admin-content { padding: 40px 0; }
.admin-nav-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
.admin-nav-card { background: var(--surface-color); padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: transform 0.2s, box-shadow 0.2s; }
.admin-nav-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.admin-nav-card h3 { font-size: 1.5rem; color: var(--primary-color); }
.admin-nav-card a { text-decoration: none; color: inherit; }
.stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
.stat-card { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); padding: 20px; border-radius: 8px; text-align: center; }
.stat-card .count { font-size: 2.5rem; font-weight: 800; }
.stat-card .label { font-size: 1rem; opacity: 0.9; }
</style>

<div class="admin-content">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Admin Dashboard</h1>
            <div>
                Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['admin_nama']); ?>!</strong>
            </div>
        </div>
        
        <!-- Statistik Ringkas -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="count"><?php echo $total_ukm; ?></div>
                <div class="label">Total UKM</div>
            </div>
            <div class="stat-card">
                <div class="count"><?php echo $total_kategori; ?></div>
                <div class="label">Total Kategori</div>
            </div>
            <div class="stat-card">
                <div class="count"><?php echo $total_event; ?></div>
                <div class="label">Total Event</div>
            </div>
        </div>

        <h2>Menu Manajemen</h2>
        <p style="color: var(--text-muted-color); margin-bottom: 30px;">Pilih salah satu modul di bawah ini untuk mulai mengelola konten website.</p>
        
        <div class="admin-nav-grid">
            <div class="admin-nav-card">
                <a href="kategori/list.php">
                    <h3>Manajemen Kategori</h3>
                    <p>Tambah, edit, atau hapus kategori UKM.</p>
                </a>
            </div>
            <div class="admin-nav-card">
                <a href="ukm/list.php">
                    <h3>Manajemen UKM</h3>
                    <p>Kelola profil lengkap untuk setiap UKM.</p>
                </a>
            </div>
            <div class="admin-nav-card">
                <a href="proker/list.php">
                    <h3>Manajemen Proker</h3>
                    <p>Kelola program kerja untuk setiap UKM.</p>
                </a>
            </div>
            <div class="admin-nav-card">
                <a href="pengurus/list.php">
                    <h3>Manajemen Pengurus</h3>
                    <p>Kelola data dan foto pengurus UKM.</p>
                </a>
            </div>
             <div class="admin-nav-card">
                <a href="anggaran/list.php">
                    <h3>Manajemen Anggaran</h3>
                    <p>Kelola data pemasukan dan pengeluaran.</p>
                </a>
            </div>
             <div class="admin-nav-card">
                <a href="event/list.php">
                    <h3>Manajemen Event</h3>
                    <p>Kelola event atau acara yang diselenggarakan.</p>
                </a>
            </div>
             <div class="admin-nav-card">
                <a href="kontak/list.php">
                    <h3>Manajemen Kontak</h3>
                    <p>Perbarui informasi kontak setiap UKM.</p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../inc/footer.php';
?>
