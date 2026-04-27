// Gunakan DOMContentLoaded: Animasi langsung jalan begitu struktur HTML terbaca,
// tanpa harus menunggu gambar 1.png / 2.png selesai dimuat sepenuhnya.
document.addEventListener("DOMContentLoaded", function () {

  // Fungsi utama untuk menghilangkan loading
  function hilangkanLoading() {
    // 1. Tambahkan class 'loaded' ke body untuk memicu animasi CSS membelah
    document.body.classList.add('loaded');

    // 2. Tampilkan konten utama (pastikan ID-nya benar 'page')
    var page = document.getElementById('page');
    if (page) {
      page.style.display = 'block';
    }
  }

  // Jalankan fungsi setelah jeda singkat (misal 500ms) agar terasa smooth
  setTimeout(hilangkanLoading, 500);
});
