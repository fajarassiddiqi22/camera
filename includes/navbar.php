<?php
// File ini di-include di halaman yang butuh navbar.
// Pastikan session sudah dimulai (lewat config/db.php) sebelum include file ini.
$current = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';
?>
<nav class="navbar">
    <div class="logo">Toko<span>Kamera</span></div>
    <ul>
        <li><a href="beranda.php" class="<?= $current === 'beranda.php' ? 'active' : '' ?>">Beranda</a></li>
        <li><a href="produk.php" class="<?= $current === 'produk.php' ? 'active' : '' ?>">Produk</a></li>
        <?php if ($isLoggedIn): ?>
            <li><a href="keranjang.php" class="cart-icon <?= $current === 'keranjang.php' ? 'active' : '' ?>">Keranjang</a></li>
            <?php if ($isAdmin): ?>
                <li><a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['nama']) ?>)</a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?= $current === 'login.php' ? 'active' : '' ?>">Login</a></li>
            <li><a href="register.php" class="<?= $current === 'register.php' ? 'active' : '' ?>">Daftar</a></li>
        <?php endif; ?>
    </ul>
</nav>
