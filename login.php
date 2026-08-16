<?php
require_once __DIR__ . '/config/db.php';

$error = "";

// Jika sudah login, langsung arahkan ke beranda
if (isset($_SESSION['user_id'])) {
    header("Location: beranda.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email === "" || $password === "") {
        $error = "Email dan password wajib diisi.";
    } else {
        $stmt = $koneksi->prepare("SELECT id, nama, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: beranda.php");
                }
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Email tidak terdaftar.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - Toko Kamera Online</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-wrapper">
        <div class="form-card">
            <h2>Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <label>Email</label>
                <input type="email" name="email" placeholder="contoh@email.com" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>

                <button type="submit" class="btn">Login</button>
            </form>

            <p class="switch-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            <p class="switch-link" style="margin-top:6px;font-size:12px;color:#999;">
                Akun admin default: admin@tokokamera.com / admin123
            </p>
        </div>
    </div>
</body>
</html>
