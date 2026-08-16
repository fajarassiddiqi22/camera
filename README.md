# 📷 Toko Kamera Online (PHP + MySQL)

Website penjualan kamera sederhana dengan fitur: **index, login, daftar (sign in), logout, dashboard admin, beranda, produk, keranjang, tambah produk, hapus produk**.

## Struktur Folder
```
kamera_web/
├── config/db.php          -> koneksi database
├── includes/auth.php      -> proteksi halaman (wajib login)
├── includes/navbar.php    -> menu navigasi
├── images/produk/         -> TEMPAT FOTO PRODUK KAMERA (letakkan file .jpg/.png di sini)
├── css/style.css          -> styling + latar belakang gradient berwarna
├── js/script.js           -> konfirmasi hapus, preview foto, dll
├── database.sql           -> struktur & data awal database
├── index.php              -> pintu masuk (redirect ke beranda)
├── beranda.php            -> halaman utama
├── produk.php             -> daftar semua produk
├── login.php / register.php / logout.php
├── dashboard.php          -> khusus admin
├── tambah_produk.php      -> khusus admin (upload foto produk)
├── hapus.php              -> proses hapus produk (khusus admin)
└── keranjang.php          -> keranjang belanja user
```

## Cara Instalasi (XAMPP/Laragon)
1. Copy folder `kamera_web` ke `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin**, buat database baru lalu **import** file `database.sql`
   (atau jalankan isinya langsung di tab SQL).
3. Buka `config/db.php`, sesuaikan `$user` dan `$pass` dengan pengaturan MySQL kamu
   (default XAMPP biasanya `root` dan password kosong).
4. Akses melalui browser: `http://localhost/kamera_web/`

## Akun Login Default (Admin)
- Email: `admin@tokokamera.com`
- Password: `admin123`

> **Catatan penting soal password admin:** hash di `database.sql` adalah CONTOH.
> Supaya pasti berfungsi di server kamu, buat hash baru dengan menjalankan kode
> PHP berikut sekali saja (misalnya lewat file php terpisah), lalu update kolom
> `password` pada tabel `users` untuk akun admin:
> ```php
> echo password_hash("230040151", PASSWORD_BCRYPT);
> ```

## Tempat Foto Produk Kamera
- Semua foto produk disimpan di folder **`images/produk/`**.
- Saat ini folder tersebut sudah diisi foto placeholder berwarna untuk tiap
  produk contoh (`canon_eos_90d.jpg`, `sony_a7_iii.jpg`, dst) dan `default.jpg`
  sebagai foto cadangan jika gambar tidak ditemukan.
- **Ganti file-file tersebut dengan foto kamera asli** (format jpg/png/webp),
  gunakan nama file yang sama, atau upload foto baru lewat halaman
  **Tambah Produk** (admin) — sistem otomatis menyimpan foto ke folder ini.

## Fitur
| Fitur | Keterangan |
|---|---|
| Index | Redirect otomatis ke beranda |
| Login | Autentikasi dengan email & password (bcrypt) |
| Daftar (Sign In) | Registrasi akun user baru |
| Logout | Menghapus session |
| Beranda | Menampilkan produk unggulan |
| Produk | Daftar semua kamera + tambah ke keranjang |
| Keranjang | Kelola jumlah, hapus item, lihat total harga |
| Dashboard | Statistik & kelola produk (khusus admin) |
| Tambah Produk | Form tambah kamera + upload foto (khusus admin) |
| Hapus Produk | Hapus produk beserta foto dari server (khusus admin) |

## Desain
Latar belakang seluruh halaman menggunakan **gradient warna biru ke oranye**
(bisa diubah di `css/style.css` bagian `body { background: ... }`).
