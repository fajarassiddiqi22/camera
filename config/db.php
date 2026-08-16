<?php
// =========================================
// KONEKSI DATABASE
// =========================================
$host = "localhost";
$user = "root";       // sesuaikan dengan user MySQL kamu
$pass = "";            // sesuaikan dengan password MySQL kamu
$dbname = "toko_kamera";

$koneksi = new mysqli($host, $user, $pass, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");

// Mulai session di satu tempat supaya konsisten di semua halaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
