<?php
/**
 * File: index.php (Versi Layout Baru)
 * Deskripsi: Halaman utama dengan hero section, search bar, dan slider yang diposisikan ulang.
 */

$pageTitle = 'Beranda';
require_once __DIR__ . '/inc/db.php';

try {
    $stmt_slider = $pdo->query("SELECT id_ukm, nama_ukm, slogan, logo FROM ukm ORDER BY RAND()");
    $slider_items = $stmt_slider->fetchAll();
} catch (PDOException $e) {
    error_log("Database error in slider query: " . $e->getMessage());
    $slider_items = [];
}

// Ambil daftar kategori untuk ditampilkan di bawah slider
try {
    $stmt_kategori = $pdo->query("SELECT id_kategori, nama_kategori, deskripsi FROM kategori_ukm ORDER BY nama_kategori ASC");
    $kategori_list = $stmt_kategori->fetchAll();
} catch (PDOException $e) {
    $kategori_list = [];
}

require_once __DIR__ . '/inc/header.php';
?>

<!-- ============================================= -->
<!--          HERO SECTION & SEARCH BAR            -->
<!-- ============================================= -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Temukan Potensimu di Sini</h1>
        <p class="hero-subtitle">
            Jelajahi beragam Unit Kegiatan Mahasiswa yang ada di kampus kita. Wadah untuk berkembang, berkreasi, dan berprestasi.
        </p>
        <div class="search-container">
            <div class="search-form">
                <input type="search" id="search-input" placeholder="Cari nama UKM..." autocomplete="off">
            </div>
            <div id="search-results"></div>
        </div>
    </div>
</section>


<!-- ============================================= -->
<!--          SLIDER SEMUA UKM                     -->
<!-- ============================================= -->

<section id="featured-ukm">
    <div class="container">
        <div class="section-title">
            <h2>Semua UKM Kampus</h2>
            <p>
                Jelajahi <strong><?php echo count($slider_items); ?> UKM</strong> 
                yang tersedia di kampus. Gunakan navigasi atau klik langsung pada UKM yang menarik.
            </p>
        </div>
    </div>

    <?php if (!empty($slider_items)): ?>
    <div class="slider-container">
        <div class="slide">
            <?php 
            // Array gambar placeholder yang lebih beragam
            $placeholder_images = [
                'https://images.unsplash.com/photo-1543286386-713bdd548da4?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1475924156734-496f6cac6ec1?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=2070&auto=format&fit=crop', 
                'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?q=80&w=2070&auto=format&fit=crop',  
            ];
            
            $i = 0;
            foreach ($slider_items as $item): 
                $bg_image = $placeholder_images[$i % count($placeholder_images)];
                
                // Gunakan logo UKM jika tersedia, jika tidak gunakan placeholder
                $display_image = !empty($item['logo']) ? 'uploads/logo/' . $item['logo'] : $bg_image;
                
                // Fallback slogan jika kosong
                $slogan = !empty($item['slogan']) ? $item['slogan'] : 'Bergabunglah dengan kami dan kembangkan potensi terbaikmu!';
                
                $i++;
            ?>
                <div class="item" 
                     style="background-image: url('<?php echo $display_image; ?>');"
                     data-ukm-id="<?php echo $item['id_ukm']; ?>"
                     data-ukm-name="<?php echo htmlspecialchars($item['nama_ukm']); ?>">
                    
                    <!-- Logo UKM jika ada -->
                    <?php if (!empty($item['logo'])): ?>
                    <div class="ukm-logo">
                        <img src="/upload/logo/<?php echo $item['logo']; ?>" 
                             alt="Logo <?php echo htmlspecialchars($item['nama_ukm']); ?>"
                             onerror="this.style.display='none'">
                    </div>
                    <?php endif; ?>
                    
                    <div class="content">
                        <div class="name"><?php echo htmlspecialchars($item['nama_ukm']); ?></div>
                        <div class="des"><?php echo htmlspecialchars($slogan); ?></div>
                        
                        <!-- Info tambahan -->
                        <div class="ukm-info">
                            <?php if (!empty($item['tahun_berdiri'])): ?>
                            <span class="info-item">
                                <i class="fa-solid fa-calendar"></i>
                                Est. <?php echo $item['tahun_berdiri']; ?>
                            </span>
                            <?php endif; ?>
                            
                            <span class="info-item status-<?php echo strtolower($item['status'] ?? 'aktif'); ?>">
                                <i class="fa-solid fa-circle"></i>
                                <?php echo ucfirst($item['status'] ?? 'Aktif'); ?>
                            </span>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="ukm/detail.php?id=<?php echo $item['id_ukm']; ?>" class="btn btn-primary">
                                <i class="fa-solid fa-eye"></i>
                                Lihat Profil
                            </a>
                            <button class="btn btn-outline" onclick="shareUKM(<?php echo $item['id_ukm']; ?>, '<?php echo htmlspecialchars($item['nama_ukm']); ?>')">
                                <i class="fa-solid fa-share"></i>
                                Bagikan
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- Kategori dipindahkan ke bawah slider -->
<section id="kategori">
    <div class="container">
        <div class="section-title">
            <h2>Jelajahi Kategori UKM</h2>
            <p>Temukan UKM berdasarkan minat dan bakat Anda.</p>
        </div>
        
        <div class="card-grid">
            <?php if (!empty($kategori_list)): ?>
                <?php foreach ($kategori_list as $kategori): ?>
                    <div class="card">
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($kategori['nama_kategori']); ?></h3>
                            <p style="min-height: 70px;"><?php echo htmlspecialchars($kategori['deskripsi']); ?></p>
                            <a href="kategori.php?id=<?php echo $kategori['id_kategori']; ?>" class="read-more">Lihat Selengkapnya &rarr;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1 / -1; text-align: center;">Belum ada kategori UKM yang ditambahkan.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/inc/footer.php';
?>
