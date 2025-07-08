<?php
// File: generate_hash.php
// Ganti 'admin123' jika Anda ingin menggunakan password lain.
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password asli: " . $password . "<br>";
echo "Hash yang dihasilkan: " . $hash;
?>