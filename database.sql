CREATE DATABASE IF NOT EXISTS toko_kamera;
USE toko_kamera;

-- Tabel user (login & register)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel produk kamera
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(12,2) NOT NULL,
    stok INT DEFAULT 0,
    gambar VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel keranjang belanja
CREATE TABLE keranjang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- Akun admin default (password: 230040151)
INSERT INTO users (nama, email, password, role) VALUES
('Fajar', 'admin@tokokamera.com', '230040151', 'admin');
-- NOTE: hash di atas contoh bcrypt untuk "230040151" (lihat catatan di README)

-- Data produk kamera (nama file gambar harus sama persis dengan yang ada di folder images/produk/)
INSERT INTO produk (nama_produk, deskripsi, harga, stok, gambar) VALUES
('Canon EOS R6 Mark', 'Kamera mirrorless full-frame dengan lensa RF 24-105mm, cocok untuk foto & video profesional.', 42500000, 6, 'canon_eos_r6_mark.jpg'),
('Canon EOS R50', 'Kamera mirrorless APS-C ringkas dengan lensa RF-S 18-45mm, cocok untuk pemula & konten kreator.', 11500000, 12, 'canon_eos_r50.png'),
('DJI Osmo Pocket 3', 'Kamera gimbal genggam dengan layar putar, cocok untuk vlog & konten cepat.', 8500000, 18, 'dji_osmo_pocket_3.jpg'),
('Fujifilm X-T5', 'Kamera mirrorless APS-C 40.2MP dengan bodi klasik dan kualitas gambar tinggi.', 26500000, 5, 'fujifilm_x_t5.jpg'),
('Panasonic Lumix S5', 'Kamera mirrorless full-frame dengan lensa 20-60mm, unggul untuk hybrid foto & video.', 32000000, 4, 'panasonic_lumix_s5.jpg'),
('Nikon Z6', 'Kamera mirrorless full-frame dengan lensa NIKKOR Z 24-70mm f/4 S.', 27500000, 6, 'nikon_z6.png'),
('Sony Alpha 7', 'Kamera mirrorless full-frame dengan mount E, sensor kualitas tinggi.', 25500000, 7, 'sony_a7.png'),
('Sony ZV-E10', 'Kamera mirrorless ringkas dengan lensa 16-50mm, dirancang khusus untuk vlogging.', 10500000, 14, 'sony_zv_e10.jpg');
