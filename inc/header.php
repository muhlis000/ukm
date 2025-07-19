<?php

// Memulai session jika belum ada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat fungsi-fungsi umum.
require_once __DIR__ . '/functions.php';

// Menentukan status login admin.
$isAdminLoggedIn = isset($_SESSION['admin_id']);

// Mendefinisikan item menu berdasarkan status login.
if ($isAdminLoggedIn) {
    $navItems = [
        'Dashboard' => '/admin/dashboard.php',
        'Kategori' => '/admin/kategori/list.php',
        'UKM' => '/admin/ukm/list.php'
    ];
} else {
    $navItems = [
        'Beranda' => '/index.php',
        'Kategori' => '/index.php#kategori'
    ];

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/functions.php';
$isAdminLoggedIn = isset($_SESSION['admin_id']);

}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Portofolio UKM' : 'Portofolio UKM'; ?></title>
    <meta name="description" content="Jelajahi Unit Kegiatan Mahasiswa di kampus kami.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- ============================================= -->
    <!-- TAMBAHAN: Font Awesome untuk Ikon Slider      -->
    <!-- ============================================= -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <a href="<?php echo $isAdminLoggedIn ? '/admin/dashboard.php' : '/index.php'; ?>" class="site-logo">
               <img src="/img/logo-kampus.png" alt="Logo Kampus" onerror="this.onerror=null;this.src='https://placehold.co/40x40/EBF4FF/1E40AF?text=Logo';">  
               <span class="site-title">Portofolio UKM<?php echo $isAdminLoggedIn ? ' <span class="admin-tag">(Admin)</span>' : ''; ?></span>
            </a>
            <div class="nav-wrapper">
                <nav id="main-nav" class="main-nav">
                    <ul>
                        <?php foreach ($navItems as $label => $url): ?>
                            <li><a href="<?php echo $url; ?>"><?php echo $label; ?></a></li>
                        <?php endforeach; ?>
                        
                        <?php if ($isAdminLoggedIn): ?>
                            <li><a href="/admin/logout.php" class="btn btn-secondary">Logout</a></li>
                        <?php else: ?>
                            <li><a href="/admin/login.php" class="btn btn-primary">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <button id="theme-toggle" type="button" class="theme-toggle-button" aria-label="Toggle dark mode">
                    <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>

                <!-- Tombol ini mengontrol navigasi dengan ID 'main-nav' -->
                <button id="mobile-menu-button" class="mobile-menu-button" aria-label="Open menu" aria-controls="main-nav" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>
    <main id="main-content">
        
