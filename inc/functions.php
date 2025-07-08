<?php
/**
 * File: functions.php
 * Deskripsi: File ini adalah kumpulan fungsi-fungsi pembantu (helper functions)
 * yang dapat dipanggil dari berbagai bagian di dalam website. Tujuannya adalah untuk
 * menampung logika yang sering digunakan kembali, sehingga kode menjadi lebih rapi,
 * mudah dibaca, dan mudah dikelola. Setiap fungsi di sini harus memiliki satu
 * tugas spesifik dan diberi komentar yang jelas.
 */

/**
 * Fungsi untuk memotong teks (truncate) ke panjang maksimum yang ditentukan
 * tanpa memotong kata di tengah.
 *
 * @param string $text Teks asli yang ingin dipotong.
 * @param int $maxLength Panjang maksimum karakter yang diinginkan.
 * @return string Teks yang sudah dipotong dan diberi elipsis (...).
 */
function truncateText(string $text, int $maxLength): string
{
    /**
     * Logika fungsi ini adalah sebagai berikut:
     * 1. Periksa apakah panjang teks asli lebih besar dari panjang maksimum.
     * 2. Jika tidak, kembalikan teks asli tanpa perubahan.
     * 3. Jika ya, potong teks menggunakan substr() hingga batas maksimum.
     * 4. Kemudian, cari posisi spasi terakhir dalam teks yang sudah dipotong
     * menggunakan strrpos() untuk menghindari pemotongan di tengah kata.
     * 5. Jika spasi ditemukan, potong lagi teks hingga posisi spasi tersebut.
     * 6. Tambahkan "..." di akhir teks yang sudah dipotong untuk menandakan
     * bahwa teks tersebut tidak lengkap.
     */
    if (strlen($text) <= $maxLength) {
        return $text;
    }

    $truncated = substr($text, 0, $maxLength);
    $lastSpace = strrpos($truncated, ' ');
    
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }

    return $truncated . '...';
}

// Anda bisa menambahkan fungsi-fungsi lain di sini di masa depan.
// Contoh:
// function formatRupiah($angka) { ... }
// function formatDateIndonesia($tanggal) { ... }

?>
