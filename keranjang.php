<?php
require_once __DIR__ . '/includes/auth.php'; // wajib login

$user_id = $_SESSION['user_id'];

// Hapus item dari keranjang
if (isset($_POST['hapus_item'])) {
    $id = (int) $_POST['id'];
    $stmt = $koneksi->prepare("DELETE FROM keranjang WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

// Update jumlah item
if (isset($_POST['update_jumlah'])) {
    $id = (int) $_POST['id'];
    $jumlah = max(1, (int) $_POST['jumlah']);
    $stmt = $koneksi->prepare("UPDATE keranjang SET jumlah = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $jumlah, $id, $user_id);
    $stmt->execute();
}

// Ambil isi keranjang beserta detail produk
$query = $koneksi->prepare("
    SELECT k.id, k.jumlah, p.nama_produk, p.harga, p.gambar
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.user_id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$hasil = $query->get_result();

$total = 0;
$items = [];
while ($row = $hasil->fetch_assoc()) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;
    $row['subtotal'] = $subtotal;
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <h2 style="color:#fff; margin-bottom:16px;">Keranjang Belanja</h2>

    <div class="card-panel">
        <?php if (count($items) === 0): ?>
            <p>Keranjang kamu masih kosong. <a href="produk.php" style="color:#ff8a00;">Belanja sekarang</a>.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><img class="thumb" src="images/produk/<?= htmlspecialchars($item['gambar']) ?>" onerror="this.src='images/produk/default.jpg'"></td>
                            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" action="keranjang.php" style="display:flex; gap:6px;">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="number" name="jumlah" value="<?= $item['jumlah'] ?>" min="1" style="width:60px; padding:6px;">
                                    <button type="submit" name="update_jumlah" class="btn" style="width:auto; padding:6px 10px;">Update</button>
                                </form>
                            </td>
                            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" action="keranjang.php" class="form-hapus">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="hapus_item" class="btn btn-danger" style="width:auto; padding:6px 10px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 style="text-align:right; margin-top:20px; color:#1e3c72;">
                Total: Rp <?= number_format($total, 0, ',', '.') ?>
            </h3>
            <div style="text-align:right; margin-top:10px;">
                <button class="btn" style="width:auto; padding:12px 28px;">Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>
