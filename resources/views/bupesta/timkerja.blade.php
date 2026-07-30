<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets-se2026/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-bupesta/timkerja.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">

    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>

    {{-- AWAL BLOK OPTIMASI LOGIKA & DATA REUSABLE --}}
    @php
        $userActive = $data['user_active'] ?? null;
        $userRole = $userActive->bupesta ?? '';
        $userNip = $userActive->nip_pegawai ?? '';
        $userSatker = $userActive->kode_satker ?? ''; // Asumsi key: kode_satker

        // 1. Caching Otorisasi
        $isAdmin = $userRole === 'admin';
        $isAdminOrKepala = in_array($userRole, ['admin', 'kepala-umum']);

        // 2. Render Opsi Dropdown Pegawai Sekali Saja untuk Dipakai Berkali-kali
        $opsiPegawaiHtml = '<option value="">-- Pilih Pegawai / PJK --</option>';
        if (!empty($data['pegawai_prov'])) {
            foreach ($data['pegawai_prov'] as $pegawai) {
                $opsiPegawaiHtml .= '<option value="' . $pegawai->nip_pegawai . '">' . $pegawai->name . '</option>';
            }
        }
    @endphp
    {{-- AKHIR BLOK OPTIMASI --}}

    <div id="orientation-warning" style="display: none;">
        <h1>Putar Perangkat Anda</h1>
        <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
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

    <div id="page">
        <header>
            @include('layout2.navbar-se2026')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>

            <div class="posisitengah">
                {{-- Tombol Tambah (Menggunakan variabel caching) --}}

                <div class="header-aksi"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">

                    {{-- Judul Halaman (Kiri) --}}
                    <h2 style="margin: 0; font-size: 1.5rem; color: #f79039; font-weight: 600;">
                        <i class="fas fa-users-cog" style="margin-right: 8px;"></i> Daftar Tim Kerja
                    </h2>

                    {{-- Tombol Tambah (Kanan) - Hanya untuk Admin/Kepala Umum --}}
                    {{-- @if (in_array($data['user_active']->bupesta, ['admin', 'kepala-umum']))
                        <button class="btn-tambah-tim" onclick="bukaModalTambah()">
                            <i class="fa-solid fa-plus"></i> Tambah Tim Kerja Baru
                        </button>
                    @endif --}}
                </div>

                {{-- ================= BAGIAN ATAS (STATUS 1) ================= --}}
                <div class="container-kartu">
                    @foreach ($data['tim_kerja'] as $tim)
                        @if ($tim->status == 1)
                            <div class="tiga-kartu">
                                {{-- 1. TAMBAHKAN 23 VARIABEL KE DALAM JSON --}}
                                <script type="application/json" id="data-tim-{{ $tim->kode_tim_kerja }}">
                                    {!! json_encode([
                                        'kode_tim_kerja' => $tim->kode_tim_kerja,
                                        'nama_tim_kerja' => $tim->nama_tim_kerja,
                                        'nip_ketua_tim'  => $tim->nip_ketua_tim,
                                        'ketuatim_1101'  => $tim->ketuatim_1101,
                                        'ketuatim_1102'  => $tim->ketuatim_1102,
                                        'ketuatim_1103'  => $tim->ketuatim_1103,
                                        'ketuatim_1104'  => $tim->ketuatim_1104,
                                        'ketuatim_1105'  => $tim->ketuatim_1105,
                                        'ketuatim_1106'  => $tim->ketuatim_1106,
                                        'ketuatim_1107'  => $tim->ketuatim_1107,
                                        'ketuatim_1108'  => $tim->ketuatim_1108,
                                        'ketuatim_1109'  => $tim->ketuatim_1109,
                                        'ketuatim_1110'  => $tim->ketuatim_1110,
                                        'ketuatim_1111'  => $tim->ketuatim_1111,
                                        'ketuatim_1112'  => $tim->ketuatim_1112,
                                        'ketuatim_1113'  => $tim->ketuatim_1113,
                                        'ketuatim_1114'  => $tim->ketuatim_1114,
                                        'ketuatim_1115'  => $tim->ketuatim_1115,
                                        'ketuatim_1116'  => $tim->ketuatim_1116,
                                        'ketuatim_1117'  => $tim->ketuatim_1117,
                                        'ketuatim_1118'  => $tim->ketuatim_1118,
                                        'ketuatim_1171'  => $tim->ketuatim_1171,
                                        'ketuatim_1172'  => $tim->ketuatim_1172,
                                        'ketuatim_1173'  => $tim->ketuatim_1173,
                                        'ketuatim_1174'  => $tim->ketuatim_1174,
                                        'ketuatim_1175'  => $tim->ketuatim_1175,
                                        'kegiatan'       => $tim->kegiatan->map(fn($k) => [
                                            'kode_kegiatan' => $k->kode_kegiatan,
                                            'nama_kegiatan' => $k->nama_kegiatan,
                                            'pjk_1100'      => $k->pjk_1100
                                        ])->toArray()
                                    ]) !!}
                                </script>

                                @if ($isAdminOrKepala || $userNip === $tim->nip_ketua_tim)
                                    <button class="btn-edit" onclick="bukaModalEdit('{{ $tim->kode_tim_kerja }}')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                @endif

                                {{-- 2. MODIFIKASI HEADER KARTU --}}
                                <div class="header-kartu header-hoverable"
                                    onclick="klikHeaderTim('{{ $tim->kode_tim_kerja }}')"
                                    title="Klik untuk melihat detail tim">
                                    <div class="ikon">{!! $tim->icon_tim_kerja !!}</div>
                                    <div class="judul">{{ $tim->nama_tim_kerja }}</div>
                                </div>

                                <div class="isi-kartu">
                                    <div class="ketua">
                                        Ketua:
                                        @php
                                            $profilKetua = collect($data['pegawai_prov'])->firstWhere(
                                                'nip_pegawai',
                                                $tim->nip_ketua_tim,
                                            );
                                        @endphp

                                        @if ($profilKetua)
                                            <span class="nama-ketua-clickable"
                                                data-nip="{{ $profilKetua->nip_pegawai }}"
                                                onclick="lihatProfilPegawai(this)">
                                                {{ $profilKetua->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Belum ada ketua</span>
                                        @endif
                                    </div>
                                    <div class="jumlah-kegiatan">
                                        <b>{{ $tim->kegiatan->count() }} Kegiatan : </b>
                                    </div>

                                    <div class="daftar-scroll">
                                        @if ($tim->kegiatan->isEmpty())
                                            <p class="text-kosong">belum ada yang diinput</p>
                                        @else
                                            <ul class="daftarkegiatan" style="padding-left: 0; margin-top: 10px;">
                                                @foreach ($tim->kegiatan as $kegiatan)
                                                    <li>
                                                        <a href="javascript:void(0)" class="item-kegiatan"
                                                            data-id="{{ $kegiatan->kode_kegiatan }}"
                                                            data-nip-ketua="{{ $tim->nip_ketua_tim }}"
                                                            data-pjk-1100="{{ $kegiatan->pjk_1100 }}">
                                                            <div class="kegiatan-info">
                                                                <span
                                                                    class="judul-kegiatan">{{ $kegiatan->nama_kegiatan }}</span>
                                                                <span class="pjk-kegiatan">
                                                                    PJK:
                                                                    {{ $kegiatan->penanggungJawab->name ?? 'Belum ada PJK' }}
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- ================= GARIS PEMISAH ================= --}}
                <hr style="margin: 30px 0; border: 1px solid #e5e7eb;">

                {{-- ================= TOMBOL TAMBAH (SEBELUM STATUS 2) ================= --}}
                <div class="header-aksi"
                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">

                    {{-- Judul Halaman (Kiri) --}}
                    <h2 style="margin: 0; font-size: 1.5rem; color: #f79039; font-weight: 600;">
                        <i class="fas fa-users-cog" style="margin-right: 8px;"></i> Daftar Tim Ad Hoc
                    </h2>

                    {{-- Tombol Tambah (Kanan) - Hanya untuk Admin/Kepala Umum --}}
                    @if (in_array($data['user_active']->bupesta, ['admin', 'kepala-umum']))
                        <button class="btn-tambah-tim" onclick="bukaModalTambah()">
                            <i class="fa-solid fa-plus"></i> Tambah Tim Kerja Baru
                        </button>
                    @endif
                </div>

                {{-- ================= BAGIAN BAWAH (STATUS 2) ================= --}}
                <div class="container-kartu">
                    @foreach ($data['tim_kerja'] as $tim)
                        @if ($tim->status == 2)
                            <div class="tiga-kartu">
                                {{-- 1. TAMBAHKAN 23 VARIABEL KE DALAM JSON --}}
                                <script type="application/json" id="data-tim-{{ $tim->kode_tim_kerja }}">
                                    {!! json_encode([
                                        'kode_tim_kerja' => $tim->kode_tim_kerja,
                                        'nama_tim_kerja' => $tim->nama_tim_kerja,
                                        'nip_ketua_tim'  => $tim->nip_ketua_tim,
                                        'ketuatim_1101'  => $tim->ketuatim_1101,
                                        'ketuatim_1102'  => $tim->ketuatim_1102,
                                        'ketuatim_1103'  => $tim->ketuatim_1103,
                                        'ketuatim_1104'  => $tim->ketuatim_1104,
                                        'ketuatim_1105'  => $tim->ketuatim_1105,
                                        'ketuatim_1106'  => $tim->ketuatim_1106,
                                        'ketuatim_1107'  => $tim->ketuatim_1107,
                                        'ketuatim_1108'  => $tim->ketuatim_1108,
                                        'ketuatim_1109'  => $tim->ketuatim_1109,
                                        'ketuatim_1110'  => $tim->ketuatim_1110,
                                        'ketuatim_1111'  => $tim->ketuatim_1111,
                                        'ketuatim_1112'  => $tim->ketuatim_1112,
                                        'ketuatim_1113'  => $tim->ketuatim_1113,
                                        'ketuatim_1114'  => $tim->ketuatim_1114,
                                        'ketuatim_1115'  => $tim->ketuatim_1115,
                                        'ketuatim_1116'  => $tim->ketuatim_1116,
                                        'ketuatim_1117'  => $tim->ketuatim_1117,
                                        'ketuatim_1118'  => $tim->ketuatim_1118,
                                        'ketuatim_1171'  => $tim->ketuatim_1171,
                                        'ketuatim_1172'  => $tim->ketuatim_1172,
                                        'ketuatim_1173'  => $tim->ketuatim_1173,
                                        'ketuatim_1174'  => $tim->ketuatim_1174,
                                        'ketuatim_1175'  => $tim->ketuatim_1175,
                                        'kegiatan'       => $tim->kegiatan->map(fn($k) => [
                                            'kode_kegiatan' => $k->kode_kegiatan,
                                            'nama_kegiatan' => $k->nama_kegiatan,
                                            'pjk_1100'      => $k->pjk_1100
                                        ])->toArray()
                                    ]) !!}
                                </script>

                                @if ($isAdminOrKepala || $userNip === $tim->nip_ketua_tim)
                                    <button class="btn-edit" onclick="bukaModalEdit('{{ $tim->kode_tim_kerja }}')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                @endif

                                {{-- 2. MODIFIKASI HEADER KARTU --}}
                                <div class="header-kartu header-hoverable"
                                    onclick="klikHeaderTim('{{ $tim->kode_tim_kerja }}')"
                                    title="Klik untuk melihat detail tim">
                                    <div class="ikon">{!! $tim->icon_tim_kerja !!}</div>
                                    <div class="judul">{{ $tim->nama_tim_kerja }}</div>
                                </div>

                                <div class="isi-kartu">
                                    <div class="ketua">
                                        Ketua:
                                        @php
                                            $profilKetua = collect($data['pegawai_prov'])->firstWhere(
                                                'nip_pegawai',
                                                $tim->nip_ketua_tim,
                                            );
                                        @endphp

                                        @if ($profilKetua)
                                            <span class="nama-ketua-clickable"
                                                data-nip="{{ $profilKetua->nip_pegawai }}"
                                                onclick="lihatProfilPegawai(this)">
                                                {{ $profilKetua->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Belum ada ketua</span>
                                        @endif
                                    </div>
                                    <div class="jumlah-kegiatan">
                                        <b>{{ $tim->kegiatan->count() }} Kegiatan : </b>
                                    </div>

                                    <div class="daftar-scroll">
                                        @if ($tim->kegiatan->isEmpty())
                                            <p class="text-kosong">belum ada yang diinput</p>
                                        @else
                                            <ul class="daftarkegiatan" style="padding-left: 0; margin-top: 10px;">
                                                @foreach ($tim->kegiatan as $kegiatan)
                                                    <li>
                                                        <a href="javascript:void(0)" class="item-kegiatan"
                                                            data-id="{{ $kegiatan->kode_kegiatan }}"
                                                            data-nip-ketua="{{ $tim->nip_ketua_tim }}"
                                                            data-pjk-1100="{{ $kegiatan->pjk_1100 }}">
                                                            <div class="kegiatan-info">
                                                                <span
                                                                    class="judul-kegiatan">{{ $kegiatan->nama_kegiatan }}</span>
                                                                <span class="pjk-kegiatan">
                                                                    PJK:
                                                                    {{ $kegiatan->penanggungJawab->name ?? 'Belum ada PJK' }}
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Modal Tambah Tim --}}
            <div id="modalTambahTim" class="modal-overlay" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Tambah Tim Kerja Baru</h3>
                        <span class="tutup-modal" onclick="tutupModalTambah()">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/timkerja/store') }}" method="POST">
                            @csrf
                            <div class="grup-input">
                                <label>Nama Tim Kerja</label>
                                <input type="text" name="nama_tim_kerja" placeholder="Masukkan nama tim..."
                                    class="input-form" required>
                            </div>
                            <div class="grup-input">
                                <label>Nama Ketua</label>
                                <select name="nama_ketua" class="input-form" style="cursor: pointer;" required>
                                    {!! $opsiPegawaiHtml !!} {{-- Pemanggilan variabel HTML, jauh lebih pendek --}}
                                </select>
                            </div>
                            <hr class="garis-pembatas"><br>
                            <div class="grup-input">
                                <label>Daftar Kegiatan Awal</label>
                                <div id="wadah-kegiatan-baru">
                                    <div class="baris-kegiatan">
                                        <input type="text" name="nama_kegiatan_baru[]"
                                            placeholder="Ketik kegiatan baru..." class="input-form">
                                        <select name="nip_pegawai_baru[]" class="input-form select-pegawai">
                                            {!! $opsiPegawaiHtml !!}
                                        </select>
                                        <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="btn-tambah-kegiatan"
                                    onclick="tambahBarisKegiatan('baru')">+ Tambah Kegiatan</button>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn-simpan">Simpan Tim Baru</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Edit Tim Global --}}
            <div id="modalEditTimGlobal" class="modal-overlay" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Edit Tim Kerja</h3>
                        <span class="tutup-modal" onclick="tutupModalEdit()">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="formEditTim" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grup-input">
                                <label>Nama Tim Kerja</label>
                                <input type="text" id="edit_nama_tim_kerja" name="nama_tim_kerja"
                                    class="input-form" required>
                            </div>
                            <div class="grup-input">
                                <label>Nama Ketua</label>
                                <select id="edit_nama_ketua" name="nama_ketua" class="input-form"
                                    style="cursor: pointer;" required>
                                    {!! $opsiPegawaiHtml !!}
                                </select>
                            </div>
                            <hr class="garis-pembatas"><br>
                            <div class="grup-input">
                                <label>Daftar Kegiatan</label>
                                <div id="wadah-kegiatan-edit"></div>
                                <button type="button" class="btn-tambah-kegiatan"
                                    onclick="tambahBarisKegiatan('edit')">+ Tambah Kegiatan</button>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Lengkapi Profil --}}
            @if ($userActive && empty($userActive->no_hp))
                <div id="modalLengkapiProfil" class="modal-overlay" style="display: flex;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Lengkapi Profil Anda</h3>
                        </div>
                        <div class="modal-body">
                            <p style="margin-bottom: 15px; color: #e53e3e; font-size: 0.9rem;">* Mohon lengkapi nomor
                                HP dan data profil Anda sebelum melanjutkan.</p>
                            <form action="{{ route('profil.updateLengkap') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="grup-input">
                                    <label for="no_hp">Nomor HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="input-form"
                                        placeholder="Contoh: 08123456789" required>
                                </div>
                                <div class="grup-input">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="input-form" value="{{ $userActive->name }}"
                                        disabled>
                                </div>
                                <div class="grup-input">
                                    <label>Username</label>
                                    <input type="text" class="input-form" value="{{ $userActive->username }}"
                                        disabled>
                                </div>
                                <input type="hidden" name="nip_pegawai" value="{{ $userNip }}">
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
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
            </footer>
        </div>
    </div>

    {{-- Modal Detail Kegiatan --}}
    <div id="modalKegiatanBesar" class="modal-overlay" style="display: none;">
        <div class="modal-content modal-xl">
            <div class="modal-header">
                {{-- 1. Judul ditambahkan span untuk disisipkan nama kegiatan via JS --}}
                <h3>Detail Lengkap Kegiatan - <span id="judul_dinamis_kegiatan"></span></h3>
                <span class="tutup-modal" onclick="tutupModalBesar()">&times;</span>
            </div>

            <div class="modal-tabs">
                <button class="tab-link active" onclick="openTab(event, 'tab-info')"><i
                        class="fas fa-info-circle"></i>
                    Informasi Umum</button>
                <button class="tab-link" onclick="openTab(event, 'tab-pjk')"><i class="fas fa-map-marker-alt"></i>
                    PJK Kab/Kota</button>

                {{-- 2. Tab Edit Informasi Umum (Class dan style disamakan dengan validasi tombol Edit PJK) --}}
                @php
                    // Validasi tampilan global untuk Tab Edit, sesuaikan dengan role admin/pimpinan
                    $tampilEditGlobal = $isAdmin ?? false;
                @endphp
                <button class="tab-link {{ !$tampilEditGlobal ? 'pjk-butuh-cek-js' : '' }}"
                    style="display: {{ $tampilEditGlobal ? 'inline-block' : 'none' }};"
                    onclick="openTab(event, 'tab-edit-info')">
                    <i class="fas fa-edit"></i> Edit Informasi Umum
                </button>
            </div>

            <div class="modal-body">
                <div id="tab-info" class="tab-content show">
                    <div class="grid-multi-kolom">
                        <div class="grup-tampil">
                            <label>Nama Kegiatan</label>
                            <div class="teks-isian" id="teks_nama_kegiatan">-</div>
                        </div>
                        <div class="grup-tampil">
                            <label>Tahun</label>
                            <div class="teks-isian" id="teks_tahun_kegiatan">-</div>
                        </div>
                    </div>
                    <div class="grup-tampil" style="margin-top: 15px;">
                        <label>Detail Anggota Tim</label>
                        {{-- Diubah menjadi flex wrap agar badge tersusun rapi ke samping --}}
                        <div class="teks-isian" id="teks_detail_anggota_tim"
                            style="display: flex; flex-wrap: wrap; gap: 5px; padding: 10px 0;">
                            -
                        </div>
                    </div>
                    <div class="grup-tampil">
                        <label>Informasi Penting</label>
                        <div class="teks-isian area-teks" id="teks_detail_informasi_penting">-</div>
                    </div>
                    <div class="grup-tampil">
                        <label>Jadwal</label>
                        <div class="teks-isian area-teks" id="teks_detail_jadwal">-</div>
                    </div>
                </div>

                <div id="tab-pjk" class="tab-content">
                    <input type="hidden" id="aktif_kode_kegiatan" value="">
                    <div class="list-satker-pjk">
                        @foreach ($data['satkers'] as $satker)
                            <div class="baris-satker">
                                @php
                                    $isPimpinanLokal =
                                        in_array($userRole ?? '', ['kepala-kako', 'kasubbag']) &&
                                        ($userSatker ?? '') == $satker->kode_Satker;
                                    $tampilDefault = ($isAdmin ?? false) || $isPimpinanLokal;
                                @endphp

                                <div class="aksi-kiri {{ !$tampilDefault ? 'pjk-butuh-cek-js' : '' }}"
                                    style="display: {{ $tampilDefault ? 'block' : 'none' }};">
                                    <button type="button" class="btn-edit-inline"
                                        onclick="toggleEditPjk('{{ $satker->kode_Satker }}')" title="Edit PJK">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>

                                <div class="info-satker">
                                    <span class="nama-satker">{{ $satker->nama_satker }}</span>
                                </div>

                                <div class="aksi-pjk" id="tampilan_pjk_{{ $satker->kode_Satker }}">
                                    <button type="button" class="btn-profil-pjk"
                                        id="btn_pjk_{{ $satker->kode_Satker }}" onclick="lihatProfilPegawai(this)">
                                        <i class="fas fa-user-circle"></i>
                                        <span class="teks-nama-pjk">Belum ada
                                            PJK</span>
                                    </button>
                                </div>

                                <div class="area-edit-pjk" id="area_edit_pjk_{{ $satker->kode_Satker }}"
                                    style="display: none;">
                                    <select id="select_pjk_{{ $satker->kode_Satker }}"
                                        class="input-form select-mini">
                                        <option value="">-- Kosongkan /
                                            Pilih PJK --</option>
                                        @foreach ($data['all_users'][$satker->kode_Satker] ?? [] as $pegawai)
                                            <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn-simpan-mini"
                                        onclick="simpanPjk('{{ $satker->kode_Satker }}')" title="Simpan"><i
                                            class="fas fa-check"></i></button>
                                    <button type="button" class="btn-batal-mini"
                                        onclick="toggleEditPjk('{{ $satker->kode_Satker }}')" title="Batal"><i
                                            class="fas fa-times"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="tab-edit-info" class="tab-content">
                    {{-- 3. Form untuk Edit Detail Lengkap Kegiatan --}}
                    <form action="/kegiatan/update-info-umum" method="POST" id="formEditInfoKegiatan">
                        @csrf
                        <input type="hidden" name="kode_kegiatan" id="form_edit_kode_kegiatan">

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="input_nama_kegiatan" class="input-form"
                                style="width: 100%;" required>
                        </div>

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Tahun Kegiatan</label>
                            <input type="text" name="tahun_kegiatan" id="input_tahun_kegiatan" class="input-form"
                                style="width: 100%;" required>
                        </div>

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Detail Anggota Tim</label>

                            {{-- 1. Wadah Visual untuk Menampilkan Label Nama yang Dipilih --}}
                            <div id="wadah_badges_anggota" class="wadah-badges">
                                <span style="color:#9ca3af; font-size:12px; font-style:italic;">Belum ada anggota
                                    dipilih.</span>
                            </div>

                            {{-- 2. Dropdown Biasa (Hanya untuk Memilih, bukan untuk submit) --}}
                            <select id="pilih_anggota_dummy" class="input-form select-mini" style="width: 100%;"
                                onchange="tambahAnggotaTim(this.value)">
                                <option value="">+ Klik untuk Menambahkan Anggota Tim...</option>
                                @foreach ($data['all_users']['1100'] ?? [] as $pegawai)
                                    <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                @endforeach
                            </select>

                            {{-- 3. Select Multiple Asli (Disembunyikan, ini yang dikirim ke Controller) --}}
                            <select name="detail_anggota_tim[]" id="select_anggota_tim_multiple" multiple
                                style="display: none;">
                                @foreach ($data['all_users']['1100'] ?? [] as $pegawai)
                                    <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Informasi Penting</label>
                            <textarea name="detail_informasi_penting" id="input_detail_informasi_penting" class="input-form area-teks"
                                style="width: 100%; height: 80px;"></textarea>
                        </div>

                        <div class="grup-tampil" style="margin-bottom: 15px;">
                            <label>Jadwal</label>
                            <textarea name="detail_jadwal" id="input_detail_jadwal" class="input-form area-teks"
                                style="width: 100%; height: 80px;"></textarea>
                        </div>

                        <div style="text-align: right;">
                            <button type="submit" class="btn-simpan-mini"
                                style="padding: 8px 15px; width: auto; height: auto; border-radius: 5px;">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Profil Pegawai --}}
    <div id="modalProfilPegawai" class="modal-overlay" style="display: none; z-index: 10000;">
        <div class="modal-content modal-profil" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Profil Pegawai</h3>
                <span class="tutup-modal" onclick="tutupModalProfilPegawai()">&times;</span>
            </div>
            <div class="modal-body text-center">
                <div class="wadah-foto" style="margin-bottom: 15px;"></div>
                <h4 id="profil_nama">-</h4>
                <p style="color: #666; margin-bottom: 20px;">NIP. <span id="profil_nip">-</span></p>
                <div class="detail-profil" style="text-align: left;">
                    <div class="info-item"><i class="fa-solid fa-briefcase"></i>
                        <div class="info-teks"><small>Jabatan</small><span id="profil_jabatan">-</span></div>
                    </div>
                    <div class="info-item"><i class="fa-solid fa-layer-group"></i>
                        <div class="info-teks"><small>Golongan</small><span id="profil_golongan">-</span></div>
                    </div>
                    <div class="info-item"><i class="fa-solid fa-building"></i>
                        <div class="info-teks"><small>Kode Satker</small><span id="profil_satker">-</span></div>
                    </div>
                    <div class="info-item">
                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.2rem;"></i>
                        <div class="info-teks">
                            <small>WhatsApp</small>
                            <a id="profil_wa_link" href="#" target="_blank"
                                style="display: inline-block; background: #25D366; color: white; padding: 4px 12px; border-radius: 15px; text-decoration: none; font-weight: bold; margin-top: 5px;">
                                <span id="profil_no_hp">-</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Tim Kerja --}}
    <div id="modalTimKerjaBesar" class="modal-overlay" style="display: none;">
        <div class="modal-content modal-xl">

            {{-- PENTING: ID TIM DISIMPAN DI SINI AGAR BISA DIBACA JAVASCRIPT --}}
            <input type="hidden" id="form_edit_kode_tim" value="">

            <div class="modal-header">
                <h3>Detail Lengkap Tim Kerja - <span id="judul_dinamis_tim"></span></h3>
                <span class="tutup-modal"
                    onclick="document.getElementById('modalTimKerjaBesar').style.display='none'">&times;</span>
            </div>

            <div class="modal-tabs">
                <button class="tab-link-tim active" onclick="openTabTim(event, 'tab-info-tim')">
                    <i class="fas fa-info-circle"></i> Informasi Umum
                </button>
                <button class="tab-link-tim" onclick="openTabTim(event, 'tab-ketua-kabkota')">
                    <i class="fas fa-users"></i> Ketua Tim Kab/Kota
                </button>
            </div>

            <div class="modal-body">

                {{-- TAB 1: INFORMASI UMUM --}}
                <div id="tab-info-tim" class="tab-content-tim show">
                    <div class="grid-multi-kolom">
                        <div class="grup-tampil">
                            <label>Nama Tim Kerja</label>
                            <div class="teks-isian" id="teks_nama_tim_kerja">-</div>
                        </div>
                        <div class="grup-tampil">
                            <label>Ketua Tim (Provinsi)</label>
                            <div class="teks-isian" id="teks_ketua_tim_prov">-</div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: KETUA TIM KAB/KOTA (Mapping 23 Variabel) --}}
                <div id="tab-ketua-kabkota" class="tab-content-tim" style="display: none;">
                    <input type="hidden" id="aktif_kode_tim" value="">
                    <div class="list-satker-pjk">
                        @foreach ($data['satkers'] as $satker)
                            {{-- Hanya tampilkan satker Kab/Kota (Abaikan 1100 Provinsi) --}}
                            @if ($satker->kode_Satker != '1100')
                                <div class="baris-satker">
                                    @php
                                        $isPimpinanLokal =
                                            in_array($userRole ?? '', ['kepala-kako', 'kasubbag']) &&
                                            ($userSatker ?? '') == $satker->kode_Satker;
                                        $tampilDefault = ($isAdmin ?? false) || $isPimpinanLokal;
                                    @endphp

                                    <div class="aksi-kiri {{ !$tampilDefault ? 'tim-butuh-cek-js' : '' }}"
                                        style="display: {{ $tampilDefault ? 'block' : 'none' }};">
                                        <button type="button" class="btn-edit-inline"
                                            onclick="toggleEditKetuaTim('{{ $satker->kode_Satker }}')"
                                            title="Edit Ketua Tim">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>

                                    <div class="info-satker">
                                        <span class="nama-satker">{{ $satker->nama_satker }}</span>
                                    </div>

                                    <div class="aksi-pjk" id="tampilan_ketuatim_{{ $satker->kode_Satker }}">
                                        <button type="button" class="btn-profil-pjk"
                                            id="btn_ketuatim_{{ $satker->kode_Satker }}"
                                            onclick="lihatProfilPegawai(this)">
                                            <i class="fas fa-user-circle"></i>
                                            <span class="teks-nama-ketua">Belum ada Ketua</span>
                                        </button>
                                    </div>

                                    <div class="area-edit-pjk" id="area_edit_ketuatim_{{ $satker->kode_Satker }}"
                                        style="display: none;">
                                        <select id="select_ketuatim_{{ $satker->kode_Satker }}"
                                            class="input-form select-mini">
                                            <option value="">-- Kosongkan / Pilih Ketua --</option>
                                            @foreach ($data['all_users'][$satker->kode_Satker] ?? [] as $pegawai)
                                                <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn-simpan-mini"
                                            onclick="simpanKetuaTim('{{ $satker->kode_Satker }}')" title="Simpan">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn-batal-mini"
                                            onclick="toggleEditKetuaTim('{{ $satker->kode_Satker }}')"
                                            title="Batal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- TAB 3: FORM EDIT INFO UMUM --}}
                <div id="tab-edit-info-tim" class="tab-content-tim" style="display: none;">
                    <form action="/timkerja/update" method="POST" id="formEditInfoTim">
                        @csrf
                        {{-- form_edit_kode_tim SUDAH DIPINDAH KE ATAS AGAR GLOBAL --}}

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Nama Tim Kerja</label>
                            <input type="text" name="nama_tim_kerja" id="input_edit_nama_tim" class="input-form"
                                style="width: 100%;" required>
                        </div>

                        <div class="grup-tampil" style="margin-bottom: 10px;">
                            <label>Ketua Tim (Provinsi)</label>
                            <select name="nip_ketua_tim" id="input_edit_ketua_prov" class="input-form"
                                style="width: 100%;" required>
                                <option value="">-- Pilih Ketua Tim --</option>
                                @foreach ($data['all_users']['1100'] ?? [] as $pegawai)
                                    <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="text-align: right; margin-top:15px;">
                            <button type="submit" class="btn-simpan-mini"
                                style="padding: 8px 15px; width: auto; height: auto; border-radius: 5px;">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Bridge PHP to JS (Sangat Disederhanakan) --}}
    <script>
        window.BupestaConfig = {
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}",
            successMessage: "{{ session('success') }}",
            opsiPegawaiHtml: `{!! $opsiPegawaiHtml !!}` // <- Langsung ambil dari variabel PHP di atas!
        };
        window.userActiveNip = "{{ $userNip }}";
    </script>

    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-bupesta/timkerja.js') }}"></script>

</body>

</html>
