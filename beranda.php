<?php
require_once __DIR__ . '/config/db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beranda - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<section class="hero">
    <h1>📷 Toko Kamera Online</h1>
    <p>Temukan kamera DSLR, mirrorless, dan action cam terbaik untuk mengabadikan momenmu.</p>
    <br>
    <a href="produk.php" class="btn" style="width:auto; padding:12px 30px; display:inline-block;">Lihat Semua Produk</a>
</section>

<footer>
    &copy; 2024 <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>