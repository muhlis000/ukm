<?php
/**
 * File: search.php (API)
 * Deskripsi: Backend untuk fitur live search.
 * File ini tidak menghasilkan HTML. Tugasnya adalah menerima kata kunci,
 * melakukan query ke database, dan mengembalikan hasilnya dalam format JSON
 * yang akan dikonsumsi oleh JavaScript (js/search.js).
 */

// Set header ke JSON untuk memastikan browser tahu tipe konten yang dikirimkan.
header('Content-Type: application/json');

require_once __DIR__ . '/../inc/db.php';

$results = [];
$query = $_GET['q'] ?? '';

// Hanya jalankan query jika kata kunci tidak kosong
if (!empty($query)) {
    try {
        // "%" adalah wildcard di SQL, artinya cocok dengan karakter apa pun.
        // Kita letakkan di awal dan akhir untuk mencari di mana saja dalam nama UKM.
        $searchTerm = '%' . $query . '%';

        // Prepared statement untuk keamanan dari SQL Injection
        $stmt = $pdo->prepare("
            SELECT u.id_ukm, u.nama_ukm, u.logo, k.nama_kategori
            FROM ukm u
            LEFT JOIN kategori_ukm k ON u.id_kategori = k.id_kategori
            WHERE u.nama_ukm LIKE ?
            LIMIT 5 -- Batasi hasil untuk performa
        ");

        $stmt->execute([$searchTerm]);
        $results = $stmt->fetchAll();

    } catch (PDOException $e) {
        // Jika terjadi error, kita bisa mengirimkan response error dalam format JSON
        http_response_code(500); // Server Error
        $results = ['error' => 'Database query failed.'];
    }
}

// Encode array PHP ke dalam format string JSON dan kirimkan sebagai output.
echo json_encode($results);
?>
