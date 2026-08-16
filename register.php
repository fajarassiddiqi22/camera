<?php
require_once __DIR__ . '/config/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($nama === "" || $email === "" || $password === "") {
        $error = "Semua field wajib diisi.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek email sudah terdaftar atau belum
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email sudah terdaftar, silakan login.";
        } else {
            $hashPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt2 = $koneksi->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt2->bind_param("sss", $nama, $email, $hashPassword);
            if ($stmt2->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Akun - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="form-card">
            <h2>Daftar Akun</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

                <label>Email</label>
                <input type="email" name="email" placeholder="contoh@email.com" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>

                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" placeholder="Ulangi password" required>

                <button type="submit" class="btn">Daftar</button>
            </form>

            <p class="switch-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
