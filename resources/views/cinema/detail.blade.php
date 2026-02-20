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

    <link rel="stylesheet" href="{{ asset('assets-cinema/') }}/load/load.css">
    <script src="{{ asset('assets-cinema/') }}/load/load.js"></script>
    <title>BuPeSta - Kegiatan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('fitral/') }}/img/favicon.png">

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
    <style>
      /* POPUP OVERLAY */
      .popup-overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.5);
          display: none;
          justify-content: center;
          align-items: center;
          z-index: 1000;
          padding: 20px;
          box-sizing: border-box;
      }

      .popup-card {
          background: white;
          border-radius: 20px;
          width: 100%;
          max-width: 70%;
          max-height: 90vh;
          /* batas tinggi maksimum */
          display: flex;
          flex-direction: column;
          overflow: hidden;
          /* sembunyikan scroll luar */
          box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      }

      /* Judul tetap di atas */
      .popup-header {
          flex-shrink: 0;
          padding: 20px;
          border-bottom: 1px solid #eee;
          background: white;
          display: flex;
          justify-content: space-between;
          align-items: center;
          position: sticky;
          top: 0;
          z-index: 2;
      }

      /* Konten form bisa scroll */
      .popup-form {
          overflow-y: auto;
          padding: 20px;
          flex-grow: 1;
          box-sizing: border-box;
      }

      /* Batasi tinggi preview image atau file upload jika ditampilkan nanti */
      .popup-form input[type="file"] {
          margin-bottom: 15px;
      }

      .popup-header h2 {
          margin: 0;
          font-size: 20px;
      }

      .close-icon {
          background: transparent;
          border: none;
          font-size: 24px;
          cursor: pointer;
          color: #aaa;
          transition: 0.2s;
      }

      .close-icon:hover {
          color: #000;
      }

      /* FORM */
      .popup-form input {
          width: 100%;
          padding: 10px 15px;
          border-radius: 10px;
          border: 1px solid #ccc;
          margin-bottom: 15px;
          font-size: 15px;
          outline: none;
          transition: border 0.3s ease;
      }

      .popup-form input:focus {
          border-color: #00aaff;
      }

      /* ACTION BUTTONS */
      .form-actions {
          display: flex;
          justify-content: flex-end;
          gap: 10px;
          margin-top: 10px;
      }

      .submit-btn {
          background: #00a86b;
          color: white;
          border: none;
          padding: 10px 16px;
          border-radius: 8px;
          cursor: pointer;
          transition: 0.2s;
      }

      .submit-btn:hover {
          background: #007d51;
      }

      .cancel-btn {
          background: #ddd;
          color: #333;
          border: none;
          padding: 10px 16px;
          border-radius: 8px;
          cursor: pointer;
      }

      .cancel-btn:hover {
          background: #ccc;
      }

      .radio-chip-group {
          display: flex;
          gap: 10px;
          margin-bottom: 15px;
          flex-wrap: wrap;
      }

      .radio-chip-group input[type="radio"] {
          display: none;
      }

      .radio-chip-group .chip {
          display: inline-block;
          padding: 10px 20px;
          border: 2px solid #ccc;
          border-radius: 25px;
          cursor: pointer;
          transition: all 0.3s ease;
          font-size: 14px;
          font-weight: 500;
          background: #f9f9f9;
          user-select: none;
      }

      .radio-chip-group input[type="radio"]:checked+.chip {
          background-color: #007bff;
          color: white;
          border-color: #007bff;
          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .radio-chip-group .chip:hover {
          border-color: #007bff;
      }

      .custom-upload {
          display: flex;
          align-items: center;
          gap: 12px;
          margin-bottom: 15px;
          flex-wrap: wrap;
      }

      .upload-btn {
          background-color: #f9f9f9;
          color: black;
          padding: 8px 16px;
          border-radius: 25px;
          cursor: pointer;
          font-weight: 500;
          transition: background-color 0.3s;
          border: none;
          display: inline-block;
      }

      .upload-btn:hover {
          background-color: #007bff;
          color: white;
      }

      .custom-upload input[type="file"] {
          display: none;
      }

      .filename {
          font-size: 14px;
          color: #555;
          max-width: 200px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }

      .preview-thumb {
          margin-top: 10px;
      }
      .preview-thumb img {
          border-radius: 6px;
          border: 1px solid #ddd;
      }

      /* Container responsif untuk lebar penuh */
        .form-wrap {
        max-width: 900px;   /* sesuaikan jika perlu */
        margin: 0 auto;
        padding: 16px;
        }
        .gform-iframe {
        width: 100%;
        height: 1200px;    /* atur tinggi agar tidak muncul scroll di dalam iframe */
        border: 0;
        }
        @media (max-width: 600px) {
        .gform-iframe { height: 1600px; } /* beri tinggi lebih di mobile */
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets-cinema/') }}/style/header.css">
    <link rel="stylesheet" href="{{ asset('assets-cinema/') }}/style/detail-cinema.css">
        <link rel="stylesheet" href="{{ asset('assets-cinema/') }}/style/potrait-warning.css">
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

                <!-- Breadcrumb -->
                <nav class="breadcrumb-wrapper">
                    <div class="breadcrumb">
                        <a href="/cinema">Beranda</a>
                        <span class="divider">›</span>
                        <span class="current">{{ $data['cinema']->judul_kegiatan }}</span>
                    </div>
                </nav>

                <div class="button-group-center">
                  <button id="editBtn" class="btn-modern edit"  onclick="openPopup()"><i class="fas fa-edit"></i> Edit</button>
                  <button id="deleteBtn" class="btn-modern delete" data-id="{{ $data['cinema']->id }}"><i class="fas fa-trash-alt"></i> Hapus</button>
                </div>

                  <!-- MODERN POPUP -->
                  <div class="popup-overlay" id="popup">
                    <div class="popup-card">
                        <div class="popup-header">
                            <h2>Edit Kegiatan</h2>
                            <button class="close-icon" onclick="closePopup()">×</button>
                        </div>
                        <form class="popup-form" action="{{ url('/edit-kegiatan/' . $data['cinema']->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label>Judul Kegiatan</label>
                            <input type="text" name="judul_kegiatan" placeholder="Contoh: Sosialisasi SBO" value="{{ $data['cinema']->judul_kegiatan }}" required />

                            <label>Tanggal Kegiatan</label>
                            <input type="date" name="tanggal_kegiatan" value="{{ $data['cinema']->tanggal_kegiatan }}" required />

                            <label>Pukul</label>
                            <input type="time" name="pukul" value="{{ $data['cinema']->pukul }}" required />

                            <label>Peserta</label>
                            <div class="radio-chip-group">
                              <input type="radio" id="prov" name="peserta" value="BPS Prov"
                                  onclick="toggleZoom(false)" required
                                  {{ $data['cinema']->peserta === 'BPS Prov' ? 'checked' : '' }} />
                              <label for="prov" class="chip">BPS Prov</label>
                          
                              <input type="radio" id="kabkota" name="peserta" value="BPS Kab Kota"
                                  onclick="toggleZoom(true)"
                                  {{ $data['cinema']->peserta === 'BPS Kab Kota' ? 'checked' : '' }} />
                              <label for="kabkota" class="chip">BPS Kab Kota</label>
                          </div>

                            <div id="zoom-link-container" style="display: none;">
                                <label>Link Zoom</label>
                                <input type="url" name="zoom_kegiatan" value="{{ $data['cinema']->zoom_kegiatan }}" placeholder="https://zoom.us/..." />
                            </div>

                            <label>Tempat Kegiatan</label>
                            <input type="text" name="tempat_kegiatan" value="{{ $data['cinema']->tempat_kegiatan }}" placeholder="Contoh: Ruang Rapat Lantai 2"
                                required />

                            <label>Pemateri</label>
                            <input type="text" name="pemateri" value="{{ $data['cinema']->pemateri }}" placeholder="" required />

                            <label>Link Presensi</label>
                            <input type="text" name="presensi_kegiatan" value="{{ $data['cinema']->presensi_kegiatan }}" placeholder="" />

                            <label>Link Rekaman</label>
                            <input type="text" name="rekaman_kegiatan" value="{{ $data['cinema']->rekaman_kegiatan }}" placeholder="" />

                            <label>Link Materi</label>
                            <input type="text" name="materi_kegiatan" value="{{ $data['cinema']->materi_kegiatan }}" placeholder="" />

                            <label>Link Sertifikat</label>
                            <input type="text" name="sertifikat_kegiatan" value="{{ $data['cinema']->sertifikat_kegiatan }}" placeholder="" />

                            <div class="form-group">
                              <label for="flyer-upload">Flyer</label>
                              <div class="custom-upload">
                                  <label for="flyer-upload" class="upload-btn">📁 Pilih Gambar</label>
                                  <span id="flyer-filename" class="filename">
                                      {{ $data['cinema']->lok_flyer ? basename($data['cinema']->name_flyer) : 'Belum ada file' }}
                                  </span>
                                  <input type="file" id="flyer-upload" name="flyer-upload" accept="image/*"
                                      onchange="showFileName(this, 'flyer-filename')" />
                              </div>
                          
                              <div class="preview-thumb">
                                  <img id="flyer-preview" src="{{ $data['cinema']->lok_flyer ? asset('storage/' . $data['cinema']->lok_flyer) : '#' }}" 
                                       alt="Flyer Preview" height="100"
                                       style="display: {{ $data['cinema']->lok_flyer ? 'block' : 'none' }};">
                              </div>
                          </div>
                          <br>
                          <div class="form-group">
                              <label for="backdrop-upload">Backdrop</label>
                              <div class="custom-upload">
                                  <label for="backdrop-upload" class="upload-btn">📁 Pilih Gambar</label>
                                  <span id="backdrop-filename" class="filename">
                                      {{ $data['cinema']->lok_backdrop ? basename($data['cinema']->name_backdrop) : 'Belum ada file' }}
                                  </span>
                                  <input type="file" id="backdrop-upload" name="backdrop-upload" accept="image/*"
                                      onchange="showFileName(this, 'backdrop-filename')" />
                              </div>
                          
                              <div class="preview-thumb">
                                  <img id="backdrop-preview" src="{{ $data['cinema']->lok_backdrop ? asset('storage/' . $data['cinema']->lok_backdrop) : '#' }}" 
                                       alt="Backdrop Preview" height="100"
                                       style="display: {{ $data['cinema']->lok_backdrop ? 'block' : 'none' }};">
                              </div>
                          </div>

                            <div class="form-actions">
                                <button type="submit" class="submit-btn">Simpan</button>
                                <button type="button" class="cancel-btn" onclick="closePopup()">Batal</button>
                            </div>
                        </form>

                    </div>
                </div>

                <main class="movie-container">

                    <!-- kiri: banner/header image -->
                    <div class="movie-banner">
                      @if ($data['cinema']->lok_backdrop == null)
                          <img src="{{ asset('assets-cinema/') }}/img/Backdrop_belum.png" alt="{{ $data['cinema']->judul_kegiatan }}" />
                      @else
                        <img src="{{ asset('storage/' . $data['cinema']->lok_backdrop) }}" alt="Banner Movie">
                      @endif
                    </div>
                    <!-- kanan: informasi detail -->
                    <aside class="movie-info">
                        <h1 class="movie-title">{{ $data['cinema']->judul_kegiatan }}</h1>
                        <ul class="info-list">
                            <li><span>Pemateri&nbsp;&nbsp;&nbsp;</span>:&nbsp;
                                <span>{{ $data['cinema']->pemateri }}</span>
                            </li>
                            <li><span>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>:&nbsp;
                                <span>{{ \Carbon\Carbon::parse($data['cinema']->tanggal_kegiatan)->translatedFormat('d F
                                    Y') }}</span>
                            </li>
                            <li><span>Tempat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>:&nbsp;
                                <span>{{ $data['cinema']->tempat_kegiatan }}</span>
                            </li>
                            <li><span>Pukul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>:&nbsp;
                                <span>{{ \Carbon\Carbon::createFromFormat('H:i:s',
                                    $data['cinema']->pukul)->format('H:i') }}</span>
                            </li>
                        </ul>
                    </aside>
                </main>

                <!-- tabs -->
                <div class="movie-tabs">
                    <button class="tab active" data-tab="rekaman">Rekaman</button>
                    <button class="tab" data-tab="materi">Materi
                      <a href="{{ $data['cinema']->materi_kegiatan }}" target="_blank" style="margin-left: 8px; font-size: 0.85em; color: rgb(0, 60, 255); text-decoration: underline;">
                        <i class="fas fa-external-link-alt"></i>
                      </a>
                    </button>
                    <button class="tab" data-tab="sertifikat">Sertifikat
                      <a href="{{ $data['cinema']->sertifikat_kegiatan }}" target="_blank" style="margin-left: 8px; font-size: 0.85em; color: rgb(0, 60, 255); text-decoration: underline;">
                        <i class="fas fa-external-link-alt"></i>
                      </a>
                    </button>
                    <button class="tab" data-tab="quiz">Quiz</button>
                </div>

                <div class="tab-content active" id="rekaman">
                    @if ($data['cinema']->rekaman_kegiatan)
                    <div class="video-embed">
                        <iframe src="https://www.youtube.com/embed/{{ basename(parse_url($data['cinema']->rekaman_kegiatan, PHP_URL_PATH)) }}" title="YouTube video" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                    @else
                      <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-muted fst-italic">Link Belum Tersedia</p>
                      </div>
                    @endif
                </div>
                <div class="tab-content" id="materi">
                    @if ($data['cinema']->materi_kegiatan)
                    <div class="gdrive-folder">
                        @php
                            preg_match('/\/folders\/([a-zA-Z0-9_-]+)/', $data['cinema']->materi_kegiatan, $matches);
                            $folderId = $matches[1] ?? null;
                        @endphp
                        <iframe
                            src="https://drive.google.com/embeddedfolderview?id={{ $folderId }}#list"
                            width="100%" height="500" frameborder="0">
                        </iframe>
                    </div>
                    @else
                      <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-muted fst-italic">Link Belum Tersedia</p>
                      </div>
                    @endif
                </div>

                <div class="tab-content" id="sertifikat">
                    @if ($data['cinema']->sertifikat_kegiatan)
                    <div class="gdrive-folder">
                        @php
                            preg_match('/\/folders\/([a-zA-Z0-9_-]+)/', $data['cinema']->sertifikat_kegiatan, $matches);
                            $folderId = $matches[1] ?? null;
                        @endphp
                        <iframe
                            src="https://drive.google.com/embeddedfolderview?id={{ $folderId }}#list"
                            width="100%" height="500" frameborder="0">
                        </iframe>
                    </div>
                    @else
                      <div style="display: flex; justify-content: center; align-items: center;">
                        <p class="text-muted fst-italic">Link Belum Tersedia</p>
                      </div>
                    @endif
                </div>

                <div class="tab-content" id="quiz">
                    <div class="form-wrap">
                        <h1>Pendaftaran Kegiatan</h1>
                        <iframe
                        class="gform-iframe"
                        src="https://docs.google.com/forms/d/e/1FAIpQLSfP7cAPokxBuCLGbC62MsWY55KmHDthnDge8-B3f1olp-O32w/viewform?embedded=true"
                        allowfullscreen
                        loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
            <!-- Tambahkan tab-content lainnya sesuai kebutuhan -->

        </div>
    </div>

    <br>
    <br>
    <br>
    <br>


    <footer>
        <?php include 'fitral/php/footer.php'; ?>
    </footer>
    </div>
    <form id="deleteForm" method="POST" style="display: none;">
      @csrf
      @method('DELETE')
    </form>
</body>

<script src="{{ asset('assets-cinema/') }}/style/potrait-warning.js"></script>
<script>
    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            // Nonaktifkan semua tab dan konten
            tabs.forEach((t) => t.classList.remove("active"));
            contents.forEach((c) => c.classList.remove("active"));

            // Aktifkan tab dan konten terkait
            tab.classList.add("active");
            const target = document.getElementById(tab.dataset.tab);
            if (target) target.classList.add("active");
        });
    });
</script>

<!-- SweetAlert CDN -->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  document.getElementById("deleteBtn").addEventListener("click", function () {
    const id = this.getAttribute("data-id");

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data kegiatan ini akan dihapus permanen.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Arahkan form DELETE ke URL yang sesuai
        const form = document.getElementById("deleteForm");
        form.action = `/hapus-kegiatan/${id}`;
        form.submit();
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
  function showFileName(input, filenameSpanId) {
      const file = input.files[0];
      const filenameSpan = document.getElementById(filenameSpanId);
      if (file) {
          filenameSpan.textContent = file.name;

          // Tampilkan preview jika gambar
          const previewId = filenameSpanId === 'flyer-filename' ? 'flyer-preview' : 'backdrop-preview';
          const previewImg = document.getElementById(previewId);
          if (previewImg) {
              previewImg.src = URL.createObjectURL(file);
          }
      }
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

</html>