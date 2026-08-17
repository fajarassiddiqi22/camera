<?php
require_once __DIR__ . '/includes/auth.php'; // wajib login

$user_id = $_SESSION['user_id'];
$error = "";

// Ambil isi keranjang user
$query = $koneksi->prepare("
    SELECT k.id, k.jumlah, p.nama_produk, p.harga, p.gambar
    FROM keranjang k
    JOIN produk p ON k.produk_id = p.id
    WHERE k.user_id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$hasil = $query->get_result();

$items = [];
$subtotalProduk = 0;
while ($row = $hasil->fetch_assoc()) {
    $row['subtotal'] = $row['harga'] * $row['jumlah'];
    $subtotalProduk += $row['subtotal'];
    $items[] = $row;
}

// Kalau keranjang kosong, tidak bisa checkout
if (count($items) === 0) {
    header("Location: keranjang.php");
    exit;
}

// Daftar opsi pengiriman & biayanya
$opsiPengiriman = [
    'Reguler'   => 15000,
    'Express'   => 35000,
    'Same Day'  => 60000,
];

// Proses submit form checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penerima = trim($_POST['nama_penerima']);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $metode_pengiriman = $_POST['metode_pengiriman'] ?? '';
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';

    if ($nama_penerima === "" || $alamat === "" || $no_hp === "") {
        $error = "Nama penerima, alamat, dan no HP wajib diisi.";
    } elseif (!array_key_exists($metode_pengiriman, $opsiPengiriman)) {
        $error = "Pilih metode pengiriman yang valid.";
    } elseif (!in_array($metode_pembayaran, ['Transfer Bank', 'COD', 'E-Wallet'])) {
        $error = "Pilih metode pembayaran yang valid.";
    } else {
        $biayaKirim = $opsiPengiriman[$metode_pengiriman];
        $totalBayar = $subtotalProduk + $biayaKirim;

        $koneksi->begin_transaction();
        try {
            // Simpan data pesanan utama
            $stmt = $koneksi->prepare("
                INSERT INTO pesanan (user_id, nama_penerima, alamat_pengiriman, no_hp, metode_pengiriman, biaya_pengiriman, metode_pembayaran, subtotal_produk, total_bayar)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "issssdsdd",
                $user_id, $nama_penerima, $alamat, $no_hp,
                $metode_pengiriman, $biayaKirim, $metode_pembayaran,
                $subtotalProduk, $totalBayar
            );
            $stmt->execute();
            $pesanan_id = $koneksi->insert_id;

            // Simpan detail tiap produk (snapshot harga saat checkout)
            $stmtDetail = $koneksi->prepare("
                INSERT INTO pesanan_detail (pesanan_id, nama_produk, harga, jumlah, subtotal)
                VALUES (?, ?, ?, ?, ?)
            ");
            foreach ($items as $item) {
                $stmtDetail->bind_param(
                    "isdid",
                    $pesanan_id, $item['nama_produk'], $item['harga'], $item['jumlah'], $item['subtotal']
                );
                $stmtDetail->execute();
            }

            // Kosongkan keranjang user setelah pesanan dibuat
            $stmtHapus = $koneksi->prepare("DELETE FROM keranjang WHERE user_id = ?");
            $stmtHapus->bind_param("i", $user_id);
            $stmtHapus->execute();

            $koneksi->commit();

            header("Location: konfirmasi.php?id=" . $pesanan_id);
            exit;
        } catch (Exception $e) {
            $koneksi->rollback();
            $error = "Gagal memproses pesanan, silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <h2 style="color:#fff; margin-bottom:16px;">Checkout</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:24px; align-items:start;">

        <!-- ===== FORM ALAMAT, PENGIRIMAN, PEMBAYARAN ===== -->
        <form method="POST" action="checkout.php">
            <div class="card-panel">
                <h3 style="color:#1e3c72; margin-bottom:16px;">📦 Alamat Pengiriman</h3>
                <label>Nama Penerima</label>
                <input type="text" name="nama_penerima" placeholder="Nama lengkap penerima" required
                       style="width:100%; padding:10px 12px; margin-bottom:16px; border:1px solid #ccc; border-radius:8px;">

                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="3" placeholder="Jalan, No. Rumah, Kelurahan, Kecamatan, Kota, Kode Pos" required
                          style="width:100%; padding:10px 12px; margin-bottom:16px; border:1px solid #ccc; border-radius:8px;"></textarea>

                <label>No. HP</label>
                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" required
                       style="width:100%; padding:10px 12px; margin-bottom:6px; border:1px solid #ccc; border-radius:8px;">
            </div>

            <div class="card-panel">
                <h3 style="color:#1e3c72; margin-bottom:16px;">🚚 Metode Pengiriman</h3>
                <?php foreach ($opsiPengiriman as $nama => $biaya): ?>
                    <label style="display:flex; align-items:center; gap:10px; padding:12px; border:1px solid #eee; border-radius:8px; margin-bottom:10px; cursor:pointer; font-weight:500;">
                        <input type="radio" name="metode_pengiriman" value="<?= $nama ?>" <?= $nama === 'Reguler' ? 'checked' : '' ?> required>
                        <span style="flex:1;"><?= $nama ?> <span style="color:#888; font-weight:400; font-size:13px;">(estimasi <?= $nama === 'Reguler' ? '3-5 hari' : ($nama === 'Express' ? '1-2 hari' : 'hari ini') ?>)</span></span>
                        <span style="color:#ff8a00; font-weight:700;">Rp <?= number_format($biaya, 0, ',', '.') ?></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="card-panel">
                <h3 style="color:#1e3c72; margin-bottom:16px;">💳 Metode Pembayaran</h3>
                <?php
                $opsiBayar = [
                    'Transfer Bank' => 'BCA / BNI / BRI / Mandiri',
                    'E-Wallet'      => 'GoPay / OVO / DANA / ShopeePay',
                    'COD'           => 'Bayar di tempat saat barang sampai',
                ];
                foreach ($opsiBayar as $nama => $ket):
                ?>
                    <label style="display:flex; align-items:center; gap:10px; padding:12px; border:1px solid #eee; border-radius:8px; margin-bottom:10px; cursor:pointer; font-weight:500;">
                        <input type="radio" name="metode_pembayaran" value="<?= $nama ?>" <?= $nama === 'Transfer Bank' ? 'checked' : '' ?> required>
                        <span style="flex:1;"><?= $nama ?> <span style="color:#888; font-weight:400; font-size:13px;">(<?= $ket ?>)</span></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn" style="width:100%; padding:14px; font-size:16px;">Buat Pesanan</button>
        </form>

        <!-- ===== RINGKASAN PESANAN ===== -->
        <div class="card-panel">
            <h3 style="color:#1e3c72; margin-bottom:16px;">Ringkasan Pesanan</h3>
            <?php foreach ($items as $item): ?>
                <div style="display:flex; justify-content:space-between; gap:10px; padding:8px 0; border-bottom:1px solid #eee; font-size:14px;">
                    <span><?= htmlspecialchars($item['nama_produk']) ?> &times; <?= $item['jumlah'] ?></span>
                    <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                </div>
            <?php endforeach; ?>

            <div style="display:flex; justify-content:space-between; padding-top:14px; font-size:14px; color:#555;">
                <span>Subtotal Produk</span>
                <span>Rp <?= number_format($subtotalProduk, 0, ',', '.') ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; padding-top:6px; font-size:14px; color:#555;">
                <span>Ongkos Kirim</span>
                <span>menyesuaikan pilihan</span>
            </div>
            <hr style="margin:14px 0; border:none; border-top:1px solid #eee;">
            <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:700; color:#1e3c72;">
                <span>Total</span>
                <span>mulai Rp <?= number_format($subtotalProduk + min($opsiPengiriman), 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<footer>
    &copy; 2024 - <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>