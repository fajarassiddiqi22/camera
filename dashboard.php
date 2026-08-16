<?php
require_once __DIR__ . '/includes/auth.php';

if (!isAdmin()) {
    header("Location: beranda.php");
    exit;
}

$totalProduk = $koneksi->query("SELECT COUNT(*) AS jml FROM produk")->fetch_assoc()['jml'];
$totalUser = $koneksi->query("SELECT COUNT(*) AS jml FROM users WHERE role='user'")->fetch_assoc()['jml'];
$totalStok = $koneksi->query("SELECT SUM(stok) AS jml FROM produk")->fetch_assoc()['jml'];
$totalKeranjang = $koneksi->query("SELECT COUNT(*) AS jml FROM keranjang")->fetch_assoc()['jml'];

$produkList = $koneksi->query("SELECT * FROM produk ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <h2 style="color:#fff; margin-bottom:16px;">Dashboard Admin</h2>

    <div class="stat-cards">
        <div class="stat-card">
            <h3><?= $totalProduk ?></h3>
            <p>Total Produk</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalUser ?></h3>
            <p>Total Pengguna</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalStok ?? 0 ?></h3>
            <p>Total Stok Kamera</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalKeranjang ?></h3>
            <p>Item di Keranjang</p>
        </div>
    </div>

    <div class="card-panel">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="color:#1e3c72;">Kelola Produk</h3>
            <a href="tambah_produk.php" class="btn" style="width:auto; padding:10px 22px;">+ Tambah Produk</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $produkList->fetch_assoc()): ?>
                    <tr>
                        <td><img class="thumb" src="images/produk/<?= htmlspecialchars($row['gambar']) ?>" onerror="this.src='images/produk/default.jpg'"></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= (int) $row['stok'] ?></td>
                        <td>
                            <form method="POST" action="hapus.php" class="form-hapus" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="width:auto; padding:6px 12px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>
