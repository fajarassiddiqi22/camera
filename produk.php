<?php
require_once __DIR__ . '/config/db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';
$notif = "";

// Tambah ke keranjang (hanya user yang login)
if (isset($_POST['tambah_keranjang'])) {
    if (!$isLoggedIn) {
        header("Location: login.php");
        exit;
    }
    $produk_id = (int) $_POST['produk_id'];
    $user_id = $_SESSION['user_id'];

    // Jika produk sudah ada di keranjang user, tambah jumlahnya
    $cek = $koneksi->prepare("SELECT id, jumlah FROM keranjang WHERE user_id = ? AND produk_id = ?");
    $cek->bind_param("ii", $user_id, $produk_id);
    $cek->execute();
    $hasil = $cek->get_result();

    if ($hasil->num_rows > 0) {
        $item = $hasil->fetch_assoc();
        $jumlahBaru = $item['jumlah'] + 1;
        $update = $koneksi->prepare("UPDATE keranjang SET jumlah = ? WHERE id = ?");
        $update->bind_param("ii", $jumlahBaru, $item['id']);
        $update->execute();
    } else {
        $insert = $koneksi->prepare("INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES (?, ?, 1)");
        $insert->bind_param("ii", $user_id, $produk_id);
        $insert->execute();
    }
    $notif = "Produk berhasil ditambahkan ke keranjang!";
}

$produkList = $koneksi->query("SELECT * FROM produk ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <h2 style="color:#fff; margin-bottom:16px;">Semua Produk Kamera</h2>

    <?php if ($notif): ?>
        <div class="alert alert-success"><?= htmlspecialchars($notif) ?></div>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
        <a href="tambah_produk.php" class="btn" style="width:auto; padding:10px 22px; display:inline-block; margin-bottom:20px;">+ Tambah Produk</a>
    <?php endif; ?>

    <div class="produk-grid">
        <?php while ($row = $produkList->fetch_assoc()): ?>
            <div class="produk-card">
                <!-- ===== TEMPAT FOTO PRODUK KAMERA ===== -->
                <div class="produk-gambar">
                    <img src="images/produk/<?= htmlspecialchars($row['gambar']) ?>"
                         alt="<?= htmlspecialchars($row['nama_produk']) ?>"
                         onerror="this.src='images/produk/default.jpg'">
                </div>
                <div class="produk-info">
                    <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
                    <p class="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <p class="stok">Stok: <?= (int) $row['stok'] ?> unit</p>

                    <div class="produk-actions">
                        <form method="POST" action="produk.php">
                            <input type="hidden" name="produk_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="tambah_keranjang" class="btn">🛒 Keranjang</button>
                        </form>

                        <?php if ($isAdmin): ?>
                            <form method="POST" action="hapus.php" class="form-hapus">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>
