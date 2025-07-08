<?php
/**
 * File: sub_nav.php
 * Deskripsi: Komponen navigasi sekunder yang dapat digunakan kembali untuk semua halaman
 * di dalam direktori /ukm. Tujuannya adalah untuk menyediakan navigasi yang konsisten
 * antara halaman detail, program kerja, pengurus, dll., untuk satu UKM yang sama.
 * File ini secara dinamis membuat link dan menandai halaman yang sedang aktif.
 * Variabel $id_ukm dan $page_slug diharapkan sudah didefinisikan di file pemanggil.
 */

// Daftar halaman yang ada di sub-navigasi
$nav_items = [
    'detail.php' => 'Profil',
    'proker.php' => 'Program Kerja',
    'pengurus.php' => 'Struktur Pengurus',
    'anggaran.php' => 'Anggaran',
    'event.php' => 'Event',
    'kontak.php' => 'Kontak'
];

// Mendapatkan nama file dari halaman saat ini, misal: "detail.php"
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="ukm-sub-nav" style="background-color: var(--surface-color); border-bottom: 1px solid var(--border-color); margin-bottom: 40px;">
    <div class="container">
        <ul style="display: flex; list-style: none; overflow-x: auto; padding: 10px 0;">
            <?php foreach ($nav_items as $file => $label): ?>
                <?php
                    // Menentukan apakah item nav ini adalah halaman yang sedang aktif
                    $is_active = ($file == $current_page);
                    $style = 'padding: 10px 20px; font-weight: 600; color: var(--text-muted-color); border-bottom: 3px solid transparent; white-space: nowrap;';
                    if ($is_active) {
                        $style = 'padding: 10px 20px; font-weight: 600; color: var(--primary-color); border-bottom: 3px solid var(--primary-color); white-space: nowrap;';
                    }
                ?>
                <li style="display: inline-block;">
                    <a href="/ukm-portfolio/ukm/<?php echo $file; ?>?id=<?php echo htmlspecialchars($id_ukm); ?>" style="<?php echo $style; ?>">
                        <?php echo htmlspecialchars($label); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
