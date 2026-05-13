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
                {{-- Tombol Tambah (Admin/Kepala Umum) --}}
                @if (in_array($data['user_active']->bupesta, ['admin', 'kepala-umum']))
                    <div class="header-aksi" style="margin-bottom: 20px; text-align: right;">
                        <button class="btn-tambah-tim" onclick="bukaModalTambah()">
                            <i class="fa-solid fa-plus"></i> Tambah Tim Kerja Baru
                        </button>
                    </div>
                @endif

                <div class="container-kartu">
                    @foreach ($data['tim_kerja'] as $tim)
                        <div class="tiga-kartu">
                            {{-- Data JSON untuk Modal Edit --}}
                            <script type="application/json" id="data-tim-{{ $tim->kode_tim_kerja }}">
                                {!! json_encode([
                                    'kode_tim_kerja' => $tim->kode_tim_kerja,
                                    'nama_tim_kerja' => $tim->nama_tim_kerja,
                                    'nip_ketua_tim' => $tim->nip_ketua_tim,
                                    'kegiatan' => $tim->kegiatan->map(fn($k) => [
                                        'kode_kegiatan' => $k->kode_kegiatan,
                                        'nama_kegiatan' => $k->nama_kegiatan,
                                        'pjk_1100' => $k->pjk_1100
                                    ])->toArray()
                                ]) !!}
                            </script>

                            {{-- Tombol Edit (Hak Akses) --}}
                            @if (in_array($data['user_active']->bupesta, ['admin', 'kepala-umum']) ||
                                    $data['user_active']->nip_pegawai === $tim->nip_ketua_tim)
                                <button class="btn-edit" onclick="bukaModalEdit('{{ $tim->kode_tim_kerja }}')">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            @endif

                            <div class="header-kartu">
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
                                        <span class="nama-ketua-clickable" data-nip="{{ $profilKetua->nip_pegawai }}"
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
                                                        {{-- TAMBAHKAN BARIS INI --}}

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
                                    <option value="">-- Pilih Ketua Tim --</option>
                                    @foreach ($data['pegawai_prov'] as $pegawai)
                                        <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                    @endforeach
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
                                            <option value="">-- Pilih Penanggung Jawab --</option>
                                            @foreach ($data['pegawai_prov'] as $pegawai)
                                                <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}
                                                </option>
                                            @endforeach
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
                                    <option value="">-- Pilih Ketua Tim --</option>
                                    @foreach ($data['pegawai_prov'] as $pegawai)
                                        <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                    @endforeach
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
            @if (isset($data['user_active']) && empty($data['user_active']->no_hp))
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
                                    <input type="text" class="input-form"
                                        value="{{ $data['user_active']->name }}" disabled>
                                </div>
                                <div class="grup-input">
                                    <label>Username</label>
                                    <input type="text" class="input-form"
                                        value="{{ $data['user_active']->username }}" disabled>
                                </div>
                                <input type="hidden" name="nip_pegawai"
                                    value="{{ $data['user_active']->nip_pegawai }}">
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
                <h3>Detail Lengkap Kegiatan</h3>
                <span class="tutup-modal" onclick="tutupModalBesar()">&times;</span>
            </div>
            <div class="modal-tabs">
                <button class="tab-link" onclick="openTab(event, 'tab-info')"><i class="fas fa-info-circle"></i>
                    Informasi Umum</button>
                <button class="tab-link active" onclick="openTab(event, 'tab-pjk')"><i
                        class="fas fa-map-marker-alt"></i> PJK Kab/Kota</button>
            </div>
            <div class="modal-body">
                <div id="tab-info" class="tab-content">
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
                        <div class="teks-isian area-teks" id="teks_detail_anggota_tim">-</div>
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

                <div id="tab-pjk" class="tab-content show">
                    <input type="hidden" id="aktif_kode_kegiatan" value="">
                    <div class="list-satker-pjk">
                        @foreach ($data['satkers'] as $satker)
                            <div class="baris-satker">
                                @php
                                    // 1. Cek Admin
                                    $isAdmin = $data['user_active']->bupesta === 'admin';

                                    // 2. Cek Kepala-kako / Kasubbag DAN satkernya sama
                                    $isKepalaOrKasubbagLocal =
                                        in_array($data['user_active']->bupesta, ['kepala-kako', 'kasubbag']) &&
                                        $data['user_active']->kode_satker == $satker->kode_satker;

                                    // 3. Tentukan apakah tombol tampil default atau disembunyikan untuk dicek via JS
                                    $tampilDefault = $isAdmin || $isKepalaOrKasubbagLocal;
                                    $classTambahan = !$tampilDefault ? 'pjk-butuh-cek-js' : '';
                                @endphp

                                <div class="aksi-kiri {{ $classTambahan }}"
                                    style="display: {{ $tampilDefault ? 'block' : 'none' }};">
                                    <button type="button" class="btn-edit-inline"
                                        onclick="toggleEditPjk('{{ $satker->kode_satker }}')" title="Edit PJK">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </div>

                                <div class="info-satker">
                                    <span class="nama-satker">{{ $satker->nama_satker }}</span>
                                </div>

                                <div class="aksi-pjk" id="tampilan_pjk_{{ $satker->kode_satker }}">
                                    <button type="button" class="btn-profil-pjk"
                                        id="btn_pjk_{{ $satker->kode_satker }}" onclick="lihatProfilPegawai(this)">
                                        <i class="fas fa-user-circle"></i> <span class="teks-nama-pjk">Belum ada
                                            PJK</span>
                                    </button>
                                </div>

                                <div class="area-edit-pjk" id="area_edit_pjk_{{ $satker->kode_satker }}"
                                    style="display: none;">
                                    <select id="select_pjk_{{ $satker->kode_satker }}"
                                        class="input-form select-mini">
                                        <option value="">-- Kosongkan / Pilih PJK --</option>

                                        {{-- JIKA ARRAY ALL USERS JUGA MENGGUNAKAN KEY KODE SATKER, UBAH JUGA DI SINI --}}
                                        @foreach ($data['all_users'][$satker->kode_satker] ?? [] as $pegawai)
                                            <option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn-simpan-mini"
                                        onclick="simpanPjk('{{ $satker->kode_satker }}')" title="Simpan"><i
                                            class="fas fa-check"></i></button>
                                    <button type="button" class="btn-batal-mini"
                                        onclick="toggleEditPjk('{{ $satker->kode_satker }}')" title="Batal"><i
                                            class="fas fa-times"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                <div class="wadah-foto" style="margin-bottom: 15px;">
                    {{-- <img id="profil_foto" src="" alt="Foto Profil" class="foto-profil"
                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;"> --}}
                </div>
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

    {{-- Bridge PHP to JS --}}
    <script>
        // 1. Kita buat dulu kerangka awal opsi dropdown-nya
        let htmlOpsiPegawai = `<option value="">-- Kosongkan / Pilih PJK --</option>`;

        // 2. Kita looping data $data['pegawai_prov'] dari Controller
        @if (isset($data['pegawai_prov']))
            @foreach ($data['pegawai_prov'] as $pegawai)
                htmlOpsiPegawai += `<option value="{{ $pegawai->nip_pegawai }}">{{ $pegawai->name }}</option>`;
            @endforeach
        @endif

        // 3. Masukkan ke dalam objek global BupestaConfig
        window.BupestaConfig = {
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}",
            successMessage: "{{ session('success') }}",
            opsiPegawaiHtml: htmlOpsiPegawai // String HTML akan otomatis masuk ke sini
        };
    </script>

    <script>
        window.userActiveNip = "{{ $data['user_active']->nip_pegawai ?? '' }}";
    </script>

    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-bupesta/timkerja.js') }}"></script>

</body>

</html>
