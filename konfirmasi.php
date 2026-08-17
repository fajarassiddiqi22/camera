<?php
require_once __DIR__ . '/includes/auth.php'; // wajib login

$user_id = $_SESSION['user_id'];
$pesanan_id = (int) ($_GET['id'] ?? 0);

// Ambil data pesanan (hanya milik user yang login)
$stmt = $koneksi->prepare("SELECT * FROM pesanan WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $pesanan_id, $user_id);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    header("Location: beranda.php");
    exit;
}

// Ambil detail produk pesanan
$stmtDetail = $koneksi->prepare("SELECT * FROM pesanan_detail WHERE pesanan_id = ?");
$stmtDetail->bind_param("i", $pesanan_id);
$stmtDetail->execute();
$detail = $stmtDetail->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Konfirmasi Pesanan - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="container">
    <div class="card-panel" style="max-width:700px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:20px;">
            <div style="font-size:48px;">✅</div>
            <h2 style="color:#1e3c72; margin-top:10px;">Pesanan Berhasil Dibuat!</h2>
            <p style="color:#666;">Nomor Pesanan: <strong>#<?= $pesanan['id'] ?></strong></p>
        </div>

        <h3 style="color:#1e3c72; margin-bottom:10px; font-size:16px;">Detail Produk</h3>
        <?php while ($item = $detail->fetch_assoc()): ?>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eee; font-size:14px;">
                <span><?= htmlspecialchars($item['nama_produk']) ?> &times; <?= $item['jumlah'] ?></span>
                <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
            </div>
        <?php endwhile; ?>

        <div style="margin-top:16px; font-size:14px; color:#444; line-height:1.8;">
            <div style="display:flex; justify-content:space-between;"><span>Subtotal Produk</span><span>Rp <?= number_format($pesanan['subtotal_produk'], 0, ',', '.') ?></span></div>
            <div style="display:flex; justify-content:space-between;"><span>Ongkos Kirim (<?= htmlspecialchars($pesanan['metode_pengiriman']) ?>)</span><span>Rp <?= number_format($pesanan['biaya_pengiriman'], 0, ',', '.') ?></span></div>
        </div>
        <hr style="margin:14px 0; border:none; border-top:1px solid #eee;">
        <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:700; color:#ff8a00;">
            <span>Total Bayar</span>
            <span>Rp <?= number_format($pesanan['total_bayar'], 0, ',', '.') ?></span>
        </div>

        <hr style="margin:20px 0; border:none; border-top:1px dashed #ccc;">

        <div style="font-size:14px; color:#444; line-height:1.9;">
            <p><strong>📦 Dikirim ke:</strong> <?= htmlspecialchars($pesanan['nama_penerima']) ?> (<?= htmlspecialchars($pesanan['no_hp']) ?>)</p>
            <p style="margin-left:20px; color:#666;"><?= nl2br(htmlspecialchars($pesanan['alamat_pengiriman'])) ?></p>
            <p><strong>🚚 Metode Pengiriman:</strong> <?= htmlspecialchars($pesanan['metode_pengiriman']) ?></p>
            <p><strong>💳 Metode Pembayaran:</strong> <?= htmlspecialchars($pesanan['metode_pembayaran']) ?></p>
            <p><strong>Status:</strong> <span style="background:#fff3cd; color:#856404; padding:3px 10px; border-radius:12px; font-size:12px;"><?= htmlspecialchars($pesanan['status']) ?></span></p>
        </div>

        <?php if ($pesanan['metode_pembayaran'] === 'Transfer Bank'): ?>
            <div class="alert" style="background:#e7f1ff; color:#1e3c72; border:1px solid #b3d1ff; margin-top:16px;">
                Silakan transfer ke rekening <strong>BCA 1234567890 a.n. Toko Kamera Online</strong>, lalu konfirmasi ke admin.
            </div>
        <?php elseif ($pesanan['metode_pembayaran'] === 'E-Wallet'): ?>
            <div class="alert" style="background:#e7f1ff; color:#1e3c72; border:1px solid #b3d1ff; margin-top:16px;">
                Silakan lakukan pembayaran ke nomor E-Wallet <strong>0812-3456-7890</strong>, lalu konfirmasi ke admin.
            </div>
        <?php else: ?>
            <div class="alert" style="background:#e7f1ff; color:#1e3c72; border:1px solid #b3d1ff; margin-top:16px;">
                Siapkan uang pas sejumlah total bayar saat kurir tiba.
            </div>
        <?php endif; ?>

        <div style="text-align:center; margin-top:24px;">
            <a href="produk.php" class="btn" style="width:auto; padding:12px 28px; display:inline-block;">Lanjut Belanja</a>
        </div>
    </div>
</div>

<footer>
    &copy; 2024 - <?= date('Y') ?> Toko Kamera Online. Semua hak dilindungi.
</footer>

<script src="js/script.js"></script>
</body>
</html>