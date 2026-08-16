<?php
require_once __DIR__ . '/includes/auth.php';

if (!isAdmin()) {
    header("Location: beranda.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Ambil nama file gambar dulu supaya bisa dihapus dari folder
    $stmt = $koneksi->prepare("SELECT gambar FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $pathGambar = __DIR__ . '/images/produk/' . $row['gambar'];
        if ($row['gambar'] !== 'default.jpg' && file_exists($pathGambar)) {
            unlink($pathGambar);
        }
    }

    $hapus = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
    $hapus->bind_param("i", $id);
    $hapus->execute();
}

header("Location: produk.php");
exit;
?>
