# 📷 Toko Kamera Online (PHP + MySQL)

Website penjualan kamera dengan fitur: **index, login, daftar (sign in), logout, dashboard admin, beranda, produk, keranjang, checkout (metode pengiriman & pembayaran), konfirmasi pesanan, tambah produk, hapus produk**.

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
├── produk.php             -> daftar semua produk + tambah ke keranjang
├── login.php / register.php / logout.php
├── dashboard.php          -> khusus admin (statistik & kelola produk)
├── tambah_produk.php      -> khusus admin (upload foto produk)
├── hapus.php              -> proses hapus produk (khusus admin)
├── keranjang.php          -> keranjang belanja user
├── checkout.php           -> form alamat, metode pengiriman & pembayaran
└── konfirmasi.php         -> bukti pesanan berhasil dibuat
```

## Cara Instalasi (XAMPP/Laragon)
1. Copy folder `kamera_web` ke `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin** → tab **SQL** → paste seluruh isi `database.sql` → klik **Go**.
   File ini sudah otomatis membuat database `toko_kamera` beserta semua tabel
   (`users`, `produk`, `keranjang`, `pesanan`, `pesanan_detail`) dan data contoh.
3. Buka `config/db.php`, sesuaikan `$user` dan `$pass` dengan pengaturan MySQL kamu
   (default XAMPP biasanya `root` dan password kosong).
4. Akses melalui browser: `http://localhost/kamera_web/`

## Akun Login Default (Admin)
- Email: `admin@tokokamera.com`
- Password: `admin123`

Hash password di `database.sql` sudah bcrypt yang **valid & teruji** untuk password
di atas, jadi tidak perlu generate ulang — tinggal pakai.

## Tempat Foto Produk Kamera
Semua foto produk disimpan di folder **`images/produk/`**, filenya harus sama
persis dengan kolom `gambar` di tabel `produk`:

| File | Produk |
|---|---|
| `canon_eos_r6_mark.jpg` | Canon EOS R6 Mark III |
| `canon_eos_r50.png` | Canon EOS R50 |
| `dji_osmo_pocket_3.jpg` | DJI Osmo Pocket 3 |
| `fujifilm_x_t5.jpg` | Fujifilm X-T5 |
| `nikon_z6.png` | Nikon Z6 II |
| `panasonic_lumix_s5.jpg` | Panasonic Lumix S5 IIX |
| `sony_a7.png` | Sony Alpha 7 |
| `sony_zv_e10.jpg` | Sony ZV-E10 |
| `default.jpg` | Foto cadangan jika gambar produk lain tidak ditemukan |

Mau tambah produk baru? Cukup lewat halaman **Tambah Produk** (admin) — foto otomatis
tersimpan ke folder ini, **tidak perlu edit kode PHP sama sekali**.

## Alur Belanja (User)
1. **Daftar** akun / **Login**
2. Buka **Produk** → klik **🛒 Keranjang** di produk yang diinginkan
3. Buka **Keranjang** → atur jumlah / hapus item → klik **Checkout**
4. Isi nama penerima, alamat, no HP → pilih **metode pengiriman**
   (Reguler / Express / Same Day, ongkir beda-beda) → pilih **metode pembayaran**
   (Transfer Bank / E-Wallet / COD) → klik **Buat Pesanan**
5. Muncul halaman **Konfirmasi** berisi nomor pesanan, rincian belanja, total bayar,
   dan instruksi pembayaran sesuai metode yang dipilih

## Fitur
| Fitur | Keterangan |
|---|---|
| Index | Redirect otomatis ke beranda |
| Login | Autentikasi dengan email & password (bcrypt) |
| Daftar (Sign In) | Registrasi akun user baru |
| Logout | Menghapus session |
| Beranda | Hero section + tombol menuju halaman produk |
| Produk | Daftar semua kamera + tambah ke keranjang |
| Keranjang | Kelola jumlah, hapus item, lihat total harga |
| Checkout | Form alamat + pilih pengiriman & pembayaran |
| Konfirmasi Pesanan | Ringkasan pesanan & instruksi bayar setelah checkout |
| Dashboard | Statistik & kelola produk (khusus admin) |
| Tambah Produk | Form tambah kamera + upload foto (khusus admin) |
| Hapus Produk | Hapus produk beserta foto dari server (khusus admin) |

## Desain
Latar belakang seluruh halaman menggunakan **gradient warna biru ke oranye**
(bisa diubah di `css/style.css` bagian `body { background: ... }`). Foto produk
ditampilkan utuh (`object-fit: contain`) supaya tidak terpotong.

---

## 🚀 Upload Project ke GitHub via Terminal

Jalankan langkah-langkah ini di dalam folder `kamera_web` (buka terminal / Git Bash
di folder tersebut).

**1. Install Git** (kalau belum ada) — cek dulu:
```bash
git --version
```
Kalau belum terinstall, download di [git-scm.com](https://git-scm.com/downloads).

**2. Buat repository baru di GitHub**
Buka github.com → klik **New repository** → beri nama (misal `toko-kamera-online`)
→ **jangan** centang "Add README" → klik **Create repository**.

**3. Inisialisasi Git di folder project**
```bash
cd kamera_web
git init
```

**4. Buat file `.gitignore`** (supaya file sensitif tidak ikut ke-upload)
```bash
echo "config/db.php" >> .gitignore
```
> Opsional tapi disarankan — supaya password database tidak ikut ter-upload publik.
> Kalau mau tetap upload `config/db.php` apa adanya, lewati langkah ini.

**5. Tambahkan semua file & buat commit pertama**
```bash
git add .
git commit -m "Initial commit - Toko Kamera Online"
```

**6. Hubungkan ke repository GitHub**
Ganti `USERNAME` dan `NAMA-REPO` sesuai punya kamu:
```bash
git remote add origin https://github.com/USERNAME/NAMA-REPO.git
```

**7. Set nama branch utama & push**
```bash
git branch -M main
git push -u origin main
```

**8. Login GitHub saat diminta**
Kalau muncul prompt username/password, gunakan **Personal Access Token** (bukan
password akun biasa) — buat di GitHub: **Settings → Developer settings →
Personal access tokens → Generate new token**, lalu tempel token itu sebagai
password.

**Selesai!** Project sudah bisa dilihat di `https://github.com/USERNAME/NAMA-REPO`

### Update project setelah ada perubahan
```bash
git add .
git commit -m "Deskripsi perubahan"
git push
```