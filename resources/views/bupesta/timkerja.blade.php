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

    <link rel="stylesheet" href="{{ asset('assets-se2026/') }}/load/load.css">
    <script src="{{ asset('assets-se2026/') }}/load/load.js"></script>
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/') }}/img/favicon.png">

    <link rel="stylesheet" href="{{ asset('assets-bupesta/') }}/timkerja.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">
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
            @include('layout2.navbar-se2026')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')

            <br>

            <div class="posisitengah">

                {{-- <div class="welcome-banner">
                    <div class="welcome-content">

                        <div class="character-container">
                            <img src="{{ asset('assets-se2026/') }}/img/bungitung.gif" class="char-animation"
                                alt="Robot Animasi">
                        </div>

                        <h2><span id="typewriter"></span><span class="cursor">|</span></h2>

                    </div>
                </div> --}}

                @if ($data['user_active']->bupesta === 'admin' || $data['user_active']->bupesta === 'kepala-umum')
                    <div class="header-aksi" style="margin-bottom: 20px; text-align: right;">
                        <button class="btn-tambah-tim" onclick="bukaModalTambah()">
                            <i class="fa-solid fa-plus"></i> Tambah Tim Kerja Baru
                        </button>
                    </div>
                @endif

                <div class="container-kartu">
                    @foreach ($data['tim_kerja'] as $tim)
                        <div class="tiga-kartu">
                            {{-- Tombol Edit memanggil Modal berdasarkan Kode Tim --}}

                            @if (
                                $data['user_active']->bupesta === 'admin' ||
                                    $data['user_active']->bupesta === 'kepala-umum' ||
                                    $data['user_active']->nip_pegawai === $tim->nip_ketua_tim)
                                <button class="btn-edit" onclick="bukaModalEdit('{{ $tim->kode_tim_kerja }}')">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            @endif

                            {{-- Header Kartu: Ikon & Judul Sejajar --}}
                            <div class="header-kartu">
                                <div class="ikon">{!! $tim->icon_tim_kerja !!}</div>
                                <div class="judul">{{ $tim->nama_tim_kerja }}</div>
                            </div>

                            <div class="isi-kartu">
                                <div class="ketua">
                                    Ketua:
                                    @php
                                        // Mencari data detail ketua dari array/collection pegawai_prov
                                        $profilKetua = collect($data['pegawai_prov'])->firstWhere(
                                            'nip_pegawai',
                                            $tim->nip_ketua_tim,
                                        );
                                    @endphp

                                    @if ($profilKetua)
                                        {{-- Jika data ketua ditemukan, buat menjadi tombol yang bisa di-klik --}}
                                        <span class="nama-ketua-clickable"
                                            onclick="bukaModalProfil('{{ $tim->kode_tim_kerja }}')">
                                            {{ $profilKetua->name }}
                                        </span>
                                    @else
                                        {{-- Jika belum ada ketua, tampilkan teks biasa --}}
                                        <span class="text-muted">Belum ada ketua</span>
                                    @endif
                                </div>
                                <div class="jumlah-kegiatan">
                                    <b>{{ $tim->kegiatan->count() }} Bu Hidang : </b>
                                </div>

                                {{-- Area Daftar yang bisa di-scroll --}}
                                <div class="daftar-scroll">
                                    @if ($tim->kegiatan->isEmpty())
                                        <p class="text-kosong">belum ada yang diinput</p>
                                    @else
                                        <ul class="daftarkegiatan">
                                            @foreach ($tim->kegiatan as $kegiatan)
                                                <li>
                                                    <a
                                                        href="{{ url('detailkegiatan?kegiatan=' . $kegiatan->kode_kegiatan) }}">
                                                        {{ $kegiatan->nama_kegiatan }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{-- MODAL TAMBAH TIM KERJA BARU --}}
                        <div id="modalTambahTim" class="modal-overlay" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Tambah Tim Kerja Baru</h3>
                                    <span class="tutup-modal" onclick="tutupModalTambah()">&times;</span>
                                </div>

                                <div class="modal-body">
                                    {{-- Sesuaikan action dengan route store Anda --}}
                                    <form action="{{ url('/timkerja/store') }}" method="POST">
                                        @csrf

                                        <div class="grup-input">
                                            <label>Nama Tim Kerja</label>
                                            <input type="text" name="nama_tim_kerja"
                                                placeholder="Masukkan nama tim..." class="input-form" required>
                                        </div>

                                        <div class="grup-input">
                                            <label>Nama Ketua</label>
                                            {{-- Hapus select-pegawai, cukup gunakan input-form --}}
                                            <select name="nama_ketua" class="input-form" style="cursor: pointer;"
                                                required>
                                                <option value="">-- Pilih Ketua Tim --</option>
                                                @foreach ($data['pegawai_prov'] as $pegawai)
                                                    <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <hr class="garis-pembatas">
                                        <br>
                                        <div class="grup-input">
                                            <label>Daftar Kegiatan Awal</label>

                                            {{-- Wadah untuk input kegiatan dinamis (menggunakan id 'baru') --}}
                                            <div id="wadah-kegiatan-baru">
                                                <div class="baris-kegiatan">
                                                    <input type="text" name="nama_kegiatan_baru[]"
                                                        placeholder="Ketik kegiatan baru..." class="input-form">
                                                    <select name="nip_pegawai_baru[]" class="input-form select-pegawai">
                                                        <option value="">-- Pilih Penanggung Jawab --</option>
                                                        @foreach ($data['pegawai_prov'] as $pegawai)
                                                            <option value="{{ $pegawai->nip_pegawai }}">
                                                                {{ $pegawai->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn-hapus-baris"
                                                        onclick="hapusBaris(this)"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </div>

                                            {{-- Tombol untuk menambah baris kegiatan menggunakan fungsi JS yang sama --}}
                                            <button type="button" class="btn-tambah-kegiatan"
                                                onclick="tambahBarisKegiatan('baru')">
                                                + Tambah Kegiatan
                                            </button>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn-simpan">Simpan Tim Baru</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL DIPINDAHKAN KE DALAM LOOPING --}}
                        <div id="modalEdit-{{ $tim->kode_tim_kerja }}" class="modal-overlay" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3>Edit Tim Kerja</h3>
                                    <span class="tutup-modal"
                                        onclick="tutupModalEdit('{{ $tim->kode_tim_kerja }}')">&times;</span>
                                </div>

                                <div class="modal-body">
                                    {{-- Form Edit Laravel --}}
                                    <form action="{{ url('/timkerja/update/' . $tim->kode_tim_kerja) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="grup-input">
                                            <label>Nama Tim Kerja</label>
                                            <input type="text" name="nama_tim_kerja"
                                                value="{{ $tim->nama_tim_kerja }}" class="input-form">
                                        </div>

                                        <div class="grup-input">
                                            <label>Nama Ketua</label>
                                            {{-- Hapus select-pegawai, cukup gunakan input-form --}}
                                            <select name="nama_ketua" class="input-form" style="cursor: pointer;"
                                                required>
                                                <option value="">-- Pilih Ketua Tim --</option>
                                                @foreach ($data['pegawai_prov'] as $pegawai)
                                                    <option value="{{ $pegawai->nip_pegawai }}"
                                                        {{ $tim->nip_ketua_tim == $pegawai->nip_pegawai ? 'selected' : '' }}>
                                                        {{ $pegawai->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <hr class="garis-pembatas">

                                        <div class="grup-input">
                                            <br>
                                            <label>Daftar Kegiatan</label>

                                            {{-- Wadah untuk daftar input kegiatan dinamis --}}
                                            <div id="wadah-kegiatan-{{ $tim->kode_tim_kerja }}">
                                                @foreach ($tim->kegiatan as $kegiatan)
                                                    <div class="baris-kegiatan">
                                                        <input type="hidden" name="id_kegiatan[]"
                                                            value="{{ $kegiatan->kode_kegiatan }}">

                                                        {{-- Input Nama Kegiatan --}}
                                                        <input type="text" name="nama_kegiatan[]"
                                                            value="{{ $kegiatan->nama_kegiatan }}"
                                                            class="input-form">

                                                        {{-- Dropdown NIP Pegawai --}}
                                                        <select name="nip_pegawai[]"
                                                            class="input-form select-pegawai">
                                                            <option value="">-- Pilih Penanggung Jawab --
                                                            </option>
                                                            @foreach ($data['pegawai_prov'] as $pegawai)
                                                                <option value="{{ $pegawai->nip_pegawai }}"
                                                                    {{ $kegiatan->pjk_1100 == $pegawai->nip_pegawai ? 'selected' : '' }}>
                                                                    {{ $pegawai->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <button type="button" class="btn-hapus-baris"
                                                            onclick="hapusBaris(this)"><i
                                                                class="fa-solid fa-trash"></i></button>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Tombol pemicu JS untuk menambah baris input baru --}}
                                            <button type="button" class="btn-tambah-kegiatan"
                                                onclick="tambahBarisKegiatan('{{ $tim->kode_tim_kerja }}')">
                                                + Tambah Kegiatan
                                            </button>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if ($profilKetua)
                            {{-- MODAL PROFIL KETUA TIM --}}
                            <div id="modalProfil-{{ $tim->kode_tim_kerja }}" class="modal-overlay"
                                style="display: none;">
                                <div class="modal-content modal-profil">
                                    <div class="modal-header">
                                        <h3>Profil Ketua Tim</h3>
                                        <span class="tutup-modal"
                                            onclick="tutupModalProfil('{{ $tim->kode_tim_kerja }}')">&times;</span>
                                    </div>

                                    <div class="modal-body text-center">
                                        {{-- Foto Profil --}}
                                        <div class="wadah-foto">
                                            <img src="https://community.bps.go.id/images/avatar/340060473_20220614202049.jpg"
                                                alt="Foto {{ $profilKetua->name }}" class="foto-profil">
                                        </div>

                                        {{-- Nama & NIP --}}
                                        <h4 class="nama-pegawai">{{ $profilKetua->name }}</h4>
                                        <p class="nip-pegawai">NIP. {{ $profilKetua->nip_pegawai }}</p>

                                        {{-- Detail Informasi --}}
                                        <div class="detail-profil">
                                            <div class="info-item">
                                                <i class="fa-solid fa-briefcase"></i>
                                                <div class="info-teks">
                                                    <small>Jabatan</small>
                                                    <span>{{ $profilKetua->jabatan ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class="fa-solid fa-layer-group"></i>
                                                <div class="info-teks">
                                                    <small>Golongan</small>
                                                    {{-- <span>{{ $profilKetua->golongan ?? '-' }}</span> --}}
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class="fa-solid fa-building"></i>
                                                <div class="info-teks">
                                                    <small>Kode Satker</small>
                                                    <span>{{ $profilKetua->kode_satker ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <div class="info-item">
                                                <i class="fa-solid fa-phone"></i>
                                                <div class="info-teks">
                                                    <small>No. Handphone</small>
                                                    <span>{{ $profilKetua->no_hp ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>

            {{-- Cek apakah data user_active tersedia dan no_hp-nya kosong --}}
            @if (isset($data['user_active']) && (is_null($data['user_active']->no_hp) || $data['user_active']->no_hp == ''))
                {{-- Overlay Background --}}
                <div id="modalLengkapiProfil" class="modal-overlay" style="display: flex;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Lengkapi Profil Anda</h3>
                            {{-- Sengaja tidak ada tombol X (Tutup) agar user wajib mengisi --}}
                        </div>

                        <div class="modal-body">
                            <p style="margin-bottom: 15px; color: #e53e3e; font-size: 0.9rem;">
                                * Mohon lengkapi nomor HP dan data profil Anda sebelum melanjutkan.
                            </p>

                            <form action="{{ route('profil.updateLengkap') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="grup-input">
                                    <label for="no_hp">Nomor HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="input-form"
                                        placeholder="Contoh: 08123456789" required>
                                </div>

                                <div class="grup-input">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" class="input-form"
                                        value="{{ $data['user_active']->name }}" disabled>
                                </div>

                                <div class="grup-input">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="input-form"
                                        value="{{ $data['user_active']->username }}" disabled>
                                </div>

                                <input type="text" id="nip_pegawai" name="nip_pegawai" class="input-form"
                                    value="{{ $data['user_active']->nip_pegawai }}" hidden>

                                <div class="modal-footer"
                                    style="margin-top: 20px; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                                    <button type="submit" class="btn-simpan">Simpan Profil</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <br>

            <footer>
                <?php include 'fitral/php/footer.php'; ?>
            </footer>
        </div>
    </div>
</body>

<script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>

<script>
    // Fungsi untuk membuka modal Tambah Data
    function bukaModalTambah() {
        document.getElementById('modalTambahTim').style.display = 'flex';
    }

    // Fungsi untuk menutup modal Tambah Data
    function tutupModalTambah() {
        document.getElementById('modalTambahTim').style.display = 'none';
    }

    // Fungsi Buka Modal Profil
    function bukaModalProfil(id) {
        document.getElementById('modalProfil-' + id).style.display = 'flex';
    }

    // Fungsi Tutup Modal Profil
    function tutupModalProfil(id) {
        document.getElementById('modalProfil-' + id).style.display = 'none';
    }
</script>

<script>
    // Membuka modal berdasarkan ID Tim Kerja
    function bukaModalEdit(id) {
        document.getElementById('modalEdit-' + id).style.display = 'flex';
    }

    // Menutup modal berdasarkan ID Tim Kerja
    function tutupModalEdit(id) {
        document.getElementById('modalEdit-' + id).style.display = 'none';
    }

    // Fungsi untuk menambah baris input kegiatan baru
    function tambahBarisKegiatan(id) {
        const wadah = document.getElementById('wadah-kegiatan-' + id);

        const divBaris = document.createElement('div');
        divBaris.className = 'baris-kegiatan';

        // HTML untuk input baru (perhatikan array nama_kegiatan_baru[])
        divBaris.innerHTML = `
        <input type="text" name="nama_kegiatan_baru[]" placeholder="Ketik kegiatan baru..." class="input-form">
        <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)">
            <i class="fa-solid fa-trash"></i>
        </button>
    `;

        wadah.appendChild(divBaris);
    }

    // Fungsi untuk menghapus baris input kegiatan
    function hapusBaris(elemenTombol) {
        // Menghapus elemen induk (div.baris-kegiatan) dari tombol yang diklik
        elemenTombol.parentElement.remove();
    }
</script>

<script>
    // Menyimpan opsi pegawai ke dalam variabel JS dari Blade agar bisa digunakan berulang kali
    const opsiPegawaiHtml = `
        <option value="">-- Pilih Penanggung Jawab --</option>
        @foreach ($data['pegawai_prov'] as $pegawai)
            <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
        @endforeach
    `;

    // Fungsi untuk menambah baris input kegiatan baru
    function tambahBarisKegiatan(id) {
        const wadah = document.getElementById('wadah-kegiatan-' + id);
        const divBaris = document.createElement('div');
        divBaris.className = 'baris-kegiatan';

        // Menambahkan input teks dan dropdown select baru
        divBaris.innerHTML = `
            <input type="text" name="nama_kegiatan_baru[]" placeholder="Ketik kegiatan baru..." class="input-form">
            <select name="nip_pegawai_baru[]" class="input-form select-pegawai">
                ${opsiPegawaiHtml}
            </select>
            <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;

        wadah.appendChild(divBaris);
    }

    function hapusBaris(elemenTombol) {
        elemenTombol.parentElement.remove();
    }
</script>


@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Memberikan jeda 2000 milidetik (2 detik) sebelum mengeksekusi Swal.fire
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }, 1000); // <-- Angka 2000 ini adalah durasi delay-nya
        });
    </script>
@endif

{{-- SCRIPT SELAMAT DATANG --}}
<script>
    // Script JavaScript tidak ada perubahan
    document.addEventListener('DOMContentLoaded', () => {
        const element = document.getElementById("typewriter");
        // Ganti baris ini dengan data blade Anda yang asli:
        const username = @json($data['user_active'][0]->name ?? 'Pengguna');
        // const username = "User"; // Contoh data dummy

        const fullText = `Halo, ${username}! Selamat Datang..`;
        const chars = Array.from(fullText);
        let i = 0;
        let isDeleting = false;

        function type() {
            if (!isDeleting) {
                i++;
                element.textContent = chars.slice(0, i).join('');
                if (i === chars.length) {
                    isDeleting = true;
                    setTimeout(type, 2000);
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

</html>
