// =========================================
// SCRIPT UMUM - TOKO KAMERA ONLINE
// =========================================

// Konfirmasi sebelum menghapus produk
document.addEventListener('DOMContentLoaded', function () {
    const formHapus = document.querySelectorAll('.form-hapus');
    formHapus.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const yakin = confirm('Yakin ingin menghapus produk ini?');
            if (!yakin) {
                e.preventDefault();
            }
        });
    });

    // Sembunyikan alert otomatis setelah 3 detik
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });

    // Preview gambar sebelum upload (form tambah produk)
    const inputGambar = document.getElementById('gambar');
    const previewGambar = document.getElementById('preview-gambar');
    if (inputGambar && previewGambar) {
        inputGambar.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewGambar.src = e.target.result;
                    previewGambar.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
