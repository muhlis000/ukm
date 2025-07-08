<?php
/**
 * File: login.php
 * Deskripsi: Halaman login untuk administrator.
 * File ini memiliki dua fungsi utama:
 * 1. Menampilkan form login jika diakses melalui metode GET.
 * 2. Memproses data login yang dikirim melalui metode POST.
 * Saat memproses, ia akan memverifikasi username dan password (yang sudah di-hash)
 * dengan data di database. Jika berhasil, ia akan membuat session untuk admin
 * dan mengarahkannya ke halaman dashboard. Jika gagal, pesan error akan ditampilkan.
 */

session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../inc/db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = 'Username dan password tidak boleh kosong.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password, nama_lengkap FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // ===================================
            // KODE DEBUG MULAI DI SINI
            // ===================================
            echo '<pre style="background: #f0f0f0; color: #333; padding: 15px; border: 1px solid #ccc; margin: 20px; font-family: monospace; line-height: 1.6;">';
            echo '<b>--- DEBUGGING INFO ---</b><br><br>';
            echo 'Username dari form: ';
            var_dump($username);
            
            echo 'Password dari form: ';
            var_dump($password);

            echo 'Data user yang ditemukan dari DB: ';
            var_dump($admin); // Jika ini `false`, berarti user 'admin' tidak ditemukan.
            
            if ($admin) {
                echo 'Password Hash dari DB: ';
                var_dump($admin['password']);

                echo 'Hasil verifikasi (password_verify): ';
                var_dump(password_verify($password, $admin['password'])); // Harusnya `true` agar bisa login
            } else {
                 echo '<br><b>KESIMPULAN: Username tidak ditemukan di database.</b>';
            }
            echo '</pre>';
            // ===================================
            // KODE DEBUG SELESAI
            // ===================================

            // Verifikasi user ada dan password cocok
            if ($admin && password_verify($password, $admin['password'])) {
                // Login berhasil, simpan data ke session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_nama'] = $admin['nama_lengkap'];
                
                // Hentikan eksekusi setelah header untuk memastikan pengalihan
                // dan hapus output debug sebelum redirect.
                // Saat sudah berhasil, Anda bisa menghapus blok debug di atas.
                header('Location: dashboard.php');
                exit;
            } else {
                // Login gagal
                $error_message = 'Username atau password salah.';
            }

        } catch (PDOException $e) {
            $error_message = 'Terjadi kesalahan database. Silakan coba lagi.';
            error_log($e->getMessage());
        }
    }
}
$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Gaya khusus untuk halaman login */
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: var(--background-color); }
        .login-card { width: 100%; max-width: 400px; padding: 40px; background-color: var(--surface-color); box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 8px; }
        .login-card h1 { text-align: center; font-size: 1.8rem; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .error-message { color: #D32F2F; background-color: #FFCDD2; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Admin Panel Login</h1>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            <a href="/ukm-portfolio/index.php" class="btn btn-secondary" style="margin-top: 15px; width: 100%; border: 1px; align-text: center">Kembali ke Beranda</a>
        </form>
    </div>
</body>
</html>
