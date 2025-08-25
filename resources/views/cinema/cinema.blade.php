<!DOCTYPE html>
<html>

    <head>
        <!-- Responsive -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Font Poppins Google -->
        <link
            href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
            rel="stylesheet">
        <!-- Icon Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <!-- Fitral CSS -->

        <link rel="stylesheet" href="assets-cinema/load/load.css">
        <script src="assets-cinema/load/load.js"></script>
        <title>BuPeSta - {{ $data["judul"] }}</title>
        <link rel="icon" type="image/x-icon" href="fitral/img/favicon.png">

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-61TSDP49BB"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-61TSDP49BB');
        </script>
        <link rel="stylesheet" href="assets-cinema/style/header.css">
        <link rel="stylesheet" href="assets-cinema/style/cinema.css">
        <link rel="stylesheet" href="assets-cinema/style/potrait-warning.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

        <!-- Peringatan Landscape -->
        <div id="orientation-warning" style="display: none;">
            <h1>Putar Perangkat Anda</h1>
            <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
            
            <!-- Animasi HP diputar -->
            <div class="phone-wrapper">
            <div class="screen"></div>
            <div class="button"></div>
            </div>
        </div>
        <div id="loading">
            <div id="loader-wrapper">
                <div id="loader"></div>
                <div class="loader-section section-left"></div>
                <div class="loader-section section-right"></div>
            </div>
        </div>
        <div id="page" style="display: block;">
            <header>
                @include('layout2.navbar-cinema')
            </header>

            <div class="konten">
                <!-- <img style="width:100%;" src="fitral/img/gedung1.jpg"> -->
                <?php include 'fitral/php/animasitextbps.php'; ?>

                <br>

                <div class="posisitengah">

                    <div class="welcome-banner">
                        <h2><span id="typewriter"></span><span class="cursor">|</span></h2>
                    </div>

                    <div class="modern-search-actions">
                        <div class="modern-search-box">
                            <span class="search-icon">🔍</span>
                            <input type="text" name="searchInput" id="searchInput" placeholder="Cari judul atau kegiatan" />
                        </div>
                        <button class="add-button" onclick="openPopup()">
                            <span>Tambah Kegiatan</span>
                        </button>
                    </div>

                    <!-- MODERN POPUP -->
                    <div class="popup-overlay" id="popup">
                        <div class="popup-card">
                            <div class="popup-header">
                                <h2>Tambah Kegiatan</h2>
                                <button class="close-icon" onclick="closePopup()">×</button>
                            </div>
                            <form class="popup-form" action="{{ url('/cinema') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <label>Judul Kegiatan</label>
                                <input type="text" name="judul_kegiatan" placeholder="Contoh: Sosialisasi SBO" required />

                                <label>Tanggal Kegiatan</label>
                                <input type="date" name="tanggal_kegiatan" required />

                                <label>Pukul</label>
                                <input type="time" name="pukul" required />

                                <label>Peserta</label>
                                <div class="radio-chip-group">
                                    <input type="radio" id="prov" name="peserta" value="BPS Prov"
                                        onclick="toggleZoom(false)" required />
                                    <label for="prov" class="chip">BPS Prov</label>

                                    <input type="radio" id="kabkota" name="peserta" value="BPS Kab Kota"
                                        onclick="toggleZoom(true)" />
                                    <label for="kabkota" class="chip">BPS Kab Kota</label>
                                </div>

                                <div id="zoom-link-container" style="display: none;">
                                    <label>Link Zoom</label>
                                    <input type="url" name="zoom_kegiatan" placeholder="https://zoom.us/..." />
                                </div>

                                <label>Tempat Kegiatan</label>
                                <input type="text" name="tempat_kegiatan" placeholder="Contoh: Ruang Rapat Lantai 2"
                                    required />

                                <label>Pemateri</label>
                                <input type="text" name="pemateri" placeholder="Contoh: Dr. Rudi Hartono" required />

                                <div class="form-group">
                                    <label for="flyer-upload">Flyer</label>
                                    <div class="custom-upload">
                                        <label for="flyer-upload" class="upload-btn">📁 Pilih Gambar</label>
                                        <span id="flyer-filename" class="filename">Belum ada file</span>
                                        <input type="file" id="flyer-upload" name="flyer-upload" accept="image/*"
                                            onchange="showFileName(this, 'flyer-filename')" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="backdrop-upload">Backdrop</label>
                                    <div class="custom-upload">
                                        <label for="backdrop-upload" class="upload-btn">📁 Pilih Gambar</label>
                                        <span id="backdrop-filename" class="filename">Belum ada file</span>
                                        <input type="file" id="backdrop-upload" name="backdrop-upload" accept="image/*"
                                            onchange="showFileName(this, 'backdrop-filename')" />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="submit-btn">Simpan</button>
                                    <button type="button" class="cancel-btn" onclick="closePopup()">Batal</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div id="movieContainer">
                    @include('cinema._list', ['cinema' => $data['cinema']])
                    </div>
                </div>
            </div>
            <br>
            <br>


            <footer>
                <?php include 'fitral/php/footer.php'; ?>
            </footer>
        </div>
    </body>

<script src="assets-cinema/style/potrait-warning.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#searchInput').on('keyup', function () {
        let query = $(this).val();
        $.ajax({
            url: "{{ route('cinema.search') }}",
            type: "GET",
            data: { q: query },
            success: function (data) {
                $('#movieContainer').html(data);
            }
        });
    });
</script>

<script>
    function toggleZoom(show) {
        const zoomInput = document.getElementById("zoom-link-container");
        zoomInput.style.display = show ? "block" : "none";
    }

    function openPopup() {
        document.getElementById("popup").style.display = "flex";
    }

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }
</script>

<script>
    function showFileName(input, labelId) {
        const fileName = input.files[0]?.name || "Belum ada file";
        document.getElementById(labelId).textContent = fileName;
    }
</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const title = this.dataset.title;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `Hapus kegiatan "${title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim form delete pakai JS
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/hapus-kegiatan/' + id;
                    form.innerHTML = `
            @csrf
            @method('DELETE')
          `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>

<script>
    function showFileName(input, spanId) {
        const file = input.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (file) {
            if (file.size > maxSize) {
                // Gunakan SweetAlert2 untuk popup
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran Terlalu Besar!',
                    text: 'Ukuran file maksimal 5MB. Silakan pilih file yang lebih kecil.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });

                input.value = "";
                document.getElementById(spanId).textContent = "Belum ada file";
            } else {
                document.getElementById(spanId).textContent = file.name;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
    const element = document.getElementById("typewriter");
    const username = @json(Auth::user()->name ?? 'Pengguna');
    const fullText = `👋 Selamat Datang, ${username}.. Mau nonton apa hari ini?`;
    const chars = Array.from(fullText); // aman untuk emoji & karakter non-ASCII
    let i = 0;
    let isDeleting = false;

    function type() {
      if (!isDeleting) {
        i++;
        element.textContent = chars.slice(0, i).join('');
        if (i === chars.length) {
          isDeleting = true;
          setTimeout(type, 2000); // tunggu sebelum menghapus
          return;
        }
      } else {
        i--;
        element.textContent = chars.slice(0, i).join('');
        if (i <= 0) {
          i = 0;
          isDeleting = false;
        }
      }

      const speed = isDeleting ? 50 : 100;
      setTimeout(type, speed);
    }

    type();
  });
</script>

{{-- <script>
  $(document).ajaxStart(function () {
      $('#movieContainer').html('<p>Sedang memuat...</p>');
  });
</script> --}}
</html>