<?php
/**
 * File: logout.php
 * Deskripsi: Skrip untuk proses logout admin.
 * File ini memulai session, menghancurkan semua data yang tersimpan di dalamnya,
 * dan kemudian mengarahkan pengguna kembali ke halaman login. Ini adalah cara
 * yang aman dan standar untuk mengakhiri sesi pengguna.
 */

session_start();

// Hapus semua variabel session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login
header('Location: login.php');
exit;
