<?php
require_once __DIR__ . '/includes/auth.php';

if (!isAdmin()) {
    header("Location: beranda.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (float) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $namaGambar = 'default.jpg';

    // ===== PROSES UPLOAD FOTO PRODUK KAMERA =====
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $folderTujuan = __DIR__ . '/images/produk/';
        $ekstensi = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ekstensi, $ekstensiDiizinkan)) {
            $namaGambar = uniqid('kamera_') . '.' . $ekstensi;
            $tujuanFile = $folderTujuan . $namaGambar;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuanFile)) {
                $error = "Gagal mengunggah foto produk.";
            }
        } else {
            $error = "Format foto harus jpg, jpeg, png, atau webp.";
        }
    }

    if ($nama_produk === "" || $harga <= 0) {
        $error = "Nama produk dan harga wajib diisi dengan benar.";
    }

    if ($error === "") {
        $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $nama_produk, $deskripsi, $harga, $stok, $namaGambar);
        if ($stmt->execute()) {
            header("Location: produk.php");
            exit;
        } else {
            $error = "Gagal menyimpan produk ke database.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Produk - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<div class="form-wrapper">
    <div class="form-card" style="max-width:480px;">
        <h2>Tambah Produk Kamera</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="tambah_produk.php" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" placeholder="Contoh: Canon EOS R6" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat produk"></textarea>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" placeholder="Contoh: 15000000" required>

            <label>Stok</label>
            <input type="number" name="stok" placeholder="Contoh: 10" required>

            <!-- ===== INPUT FOTO PRODUK KAMERA ===== -->
            <label>Foto Produk</label>
            <input type="file" name="gambar" id="gambar" accept="image/*">
            <img id="preview-gambar" src="#" alt="Preview" style="display:none; width:100%; border-radius:8px; margin-bottom:16px;">

            <button type="submit" class="btn">Simpan Produk</button>
        </form>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
