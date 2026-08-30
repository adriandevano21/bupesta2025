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
        $userSatker = $userActive->kode_Satker ?? ''; // Asumsi key: kode_Satker

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
                                    <input type="text" class="input-form" value="{{ $userActive->name }}" disabled>
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
