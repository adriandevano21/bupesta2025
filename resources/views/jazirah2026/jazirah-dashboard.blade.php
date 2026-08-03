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
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/jazirah-dashboard.css') }}">
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
                <div class="posisitengah" style="width: 95%; max-width: 95%; margin: 0 auto;">
                    <div class="jazirah-container">

                        <div class="welcome-banner">
                            <div class="welcome-content">
                                <div class="character-container">
                                    <img src="{{ asset('assets-se2026/img/bungitung.gif') }}" class="char-animation"
                                        alt="Maskot BPS">
                                </div>
                                <h2><span id="typewriter"></span><span class="cursor">|</span></h2>
                            </div>
                        </div>

                        @php
                            $menus = [
                                [
                                    'title' => 'New Lembar Kerja',
                                    'url' => url('/jazirah-lembarkerja'),
                                    'bg' => 'linear-gradient(135deg, #3b82f6, #06b6d4)',
                                    'icon' => '<i class="fa-solid fa-file-lines"></i>',
                                ],
                                [
                                    'title' => 'Seulanga',
                                    'url' => url('/seulanga'),
                                    'bg' => 'linear-gradient(135deg, #fbbf24, #eab308)',
                                    'icon' => '<i class="fa-solid fa-star"></i>',
                                ],
                                [
                                    'title' => 'Rangkuman',
                                    'url' => url('/rangkuman'),
                                    'bg' => 'linear-gradient(135deg, #6366f1, #2563eb)',
                                    'icon' => '<i class="fa-solid fa-chart-pie"></i>',
                                ],
                                [
                                    'title' => 'Pengisian Matriks Aksi',
                                    'url' => url('/matriks-aksi'),
                                    'bg' => 'linear-gradient(135deg, #a855f7, #6366f1)',
                                    'icon' => '<i class="fa-solid fa-pen-to-square"></i>',
                                ],
                                [
                                    'title' => 'Pedoman ZI',
                                    'url' => url('/pedoman-zi'),
                                    'bg' => 'linear-gradient(135deg, #34d399, #14b8a6)',
                                    'icon' => '<i class="fa-solid fa-book"></i>',
                                ],
                                [
                                    'title' => 'SOP',
                                    'url' => url('/sop'),
                                    'bg' => 'linear-gradient(135deg, #fb7185, #ef4444)',
                                    'icon' => '<i class="fa-solid fa-file-shield"></i>',
                                ],
                                [
                                    'title' => 'LHE TPP ZI 2024',
                                    'url' => url('/lhe-tpp-2024'),
                                    'bg' => 'linear-gradient(135deg, #d946ef, #9333ea)',
                                    'icon' => '<i class="fa-solid fa-award"></i>',
                                ],
                                [
                                    'title' => 'LKE Satker 2024',
                                    'url' => url('/lke-satker-2024'),
                                    'bg' => 'linear-gradient(135deg, #fb923c, #c2410c)',
                                    'icon' => '<i class="fa-solid fa-clipboard-check"></i>',
                                ],
                                [
                                    'title' => 'Event Jazirah',
                                    'url' => url('/jazirah/event'),
                                    'bg' => 'linear-gradient(135deg, #22d3ee, #3b82f6)',
                                    'icon' => '<i class="fa-solid fa-calendar-days"></i>',
                                ],
                                [
                                    'title' => 'Satker Lolos TPI',
                                    'url' => url('/satker-tpi'),
                                    'bg' => 'linear-gradient(135deg, #4ade80, #059669)',
                                    'icon' => '<i class="fa-solid fa-circle-check"></i>',
                                ],
                                [
                                    'title' => 'QNA',
                                    'url' => url('/qna'),
                                    'bg' => 'linear-gradient(135deg, #38bdf8, #06b6d4)',
                                    'icon' => '<i class="fa-solid fa-circle-question"></i>',
                                ],
                                [
                                    'title' => 'Narahubung',
                                    'url' => url('/narahubung'),
                                    'bg' => 'linear-gradient(135deg, #2dd4bf, #10b981)',
                                    'icon' => '<i class="fa-solid fa-headset"></i>',
                                ],
                            ];
                        @endphp

                        <div class="jazirah-menu-wrapper modern-scrollbar">
                            <div class="jazirah-menu-container">
                                @foreach ($menus as $menu)
                                    <a href="{{ $menu['url'] }}" class="jazirah-menu-card">
                                        <div class="jazirah-menu-icon" style="background: {{ $menu['bg'] }};">
                                            {!! $menu['icon'] !!}
                                        </div>
                                        <h3 class="jazirah-menu-title">{{ $menu['title'] }}</h3>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <br>

                        <div class="jazirah-controls">
                            <form method="GET" action="{{ url()->current() }}" class="jazirah-form-filter"
                                id="form-filter">
                                <input type="hidden" name="tahun" value="{{ request('tahun', '2026') }}">

                                <div class="jazirah-input-group">
                                    <label for="mode">Mode:</label>
                                    <select name="mode" id="mode" onchange="this.form.submit()"
                                        class="jazirah-select">
                                        <option value="rekap_satker"
                                            {{ request('mode', 'rekap_satker') == 'rekap_satker' ? 'selected' : '' }}>
                                            Rekap
                                            Detail Per Satker</option>
                                        <option value="lintas_satker"
                                            {{ request('mode') == 'lintas_satker' ? 'selected' : '' }}>Persentase Se
                                            Provinsi Aceh</option>
                                    </select>
                                </div>

                                <div class="jazirah-input-group">
                                    <label for="periode">Periode:</label>
                                    <select name="periode" id="periode" onchange="this.form.submit()"
                                        class="jazirah-select">
                                        <option value="bulan_berjalan"
                                            {{ request('periode', 'bulan_berjalan') == 'bulan_berjalan' ? 'selected' : '' }}>
                                            Bulan Berjalan</option>
                                        <option value="tw1" {{ request('periode') == 'tw1' ? 'selected' : '' }}>
                                            Triwulan
                                            1</option>
                                        <option value="tw2" {{ request('periode') == 'tw2' ? 'selected' : '' }}>
                                            Triwulan
                                            2</option>
                                        <option value="tw3" {{ request('periode') == 'tw3' ? 'selected' : '' }}>
                                            Triwulan
                                            3</option>
                                        <option value="tw4" {{ request('periode') == 'tw4' ? 'selected' : '' }}>
                                            Triwulan
                                            4</option>
                                    </select>
                                </div>

                                @if (request('mode', 'rekap_satker') == 'lintas_satker')
                                    <div class="jazirah-input-group">
                                        <label for="jenis_data">Pilih Data:</label>
                                        <select name="jenis_data" id="jenis_data" onchange="this.form.submit()"
                                            class="jazirah-select">
                                            <option value="persentase_penetapan_target"
                                                {{ request('jenis_data', 'persentase_penetapan_target') == 'persentase_penetapan_target' ? 'selected' : '' }}>
                                                Persentase Penetapan Target</option>
                                            <option value="persentase_realisasi"
                                                {{ request('jenis_data') == 'persentase_realisasi' ? 'selected' : '' }}>
                                                Persentase Realisasi</option>
                                            <option value="persentase_tindaklanjut"
                                                {{ request('jenis_data') == 'persentase_tindaklanjut' ? 'selected' : '' }}>
                                                Persentase Tindak Lanjut</option>
                                            <option value="persentase_dokumen_selesai"
                                                {{ request('jenis_data') == 'persentase_dokumen_selesai' ? 'selected' : '' }}>
                                                Persentase Validasi/Selesai</option>
                                        </select>
                                    </div>
                                @elseif(request('mode', 'rekap_satker') == 'rekap_satker')
                                    <div class="jazirah-input-group">
                                        <label for="selected_satker">Satker:</label>
                                        <select name="selected_satker" id="selected_satker"
                                            onchange="this.form.submit()" class="jazirah-select">
                                            @foreach ($data['satkers'] ?? [] as $satker)
                                                <option value="{{ $satker }}"
                                                    {{ request('selected_satker', $data['selected_satker'] ?? '') == $satker ? 'selected' : '' }}>
                                                    {{ $satker }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </form>

                            <button id="downloadBtn" class="jazirah-btn-download">
                                <i class="fa-solid fa-download"></i>
                                <span>Download Image</span>
                            </button>
                        </div>

                        <div class="jazirah-table-responsive" id="tabel-monitoring">
                            @php
                                // Helper fungsi badge dan teks
                                $getBadgeClass = function ($val, $targetSetahun = 1, $targetPeriode = 1) {
                                    if ($targetSetahun > 0 && $targetPeriode == 0) {
                                        return 'badge-notarget';
                                    }
                                    if ($val === null) {
                                        return 'badge-belum-isi';
                                    }
                                    if ($val >= 100) {
                                        return 'badge-sempurna';
                                    }
                                    if ($val > 0 && $val < 100) {
                                        return 'badge-sebagian';
                                    }
                                    if ($val === '0' || $val === 0 || $val === '0.00') {
                                        return 'badge-nol';
                                    }
                                    return 'badge-kosong';
                                };

                                $getTeks = function ($val, $targetSetahun = 1, $targetPeriode = 1) {
                                    if ($targetSetahun > 0 && $targetPeriode == 0) {
                                        return 'Tidak Ada Target';
                                    }
                                    if ($val === null) {
                                        return 'Belum Isi';
                                    }
                                    if ($val === '') {
                                        return '-';
                                    }
                                    return $val . '%';
                                };
                            @endphp

                            @if ($data['mode'] === 'lintas_satker')
                                <table id="dataTableMonitoring" class="jazirah-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Kode</th>
                                            <th rowspan="2" style="text-align: left;">Pilar</th>
                                            <th colspan="{{ count($data['satkers'] ?? []) }}">Satuan Kerja</th>
                                        </tr>
                                        <tr>
                                            @foreach ($data['satkers'] ?? [] as $satker)
                                                <th>{{ $satker }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['pivotData'] ?? [] as $row)
                                            <tr>
                                                <td><strong>
                                                        @if ($row['indikator'] === 'I.')
                                                            Pemenuhan
                                                        @else
                                                            Reform
                                                        @endif
                                                    </strong></td>
                                                <td style="text-align: left;"><strong>{{ $row['pilar'] }}</strong>
                                                </td>
                                                @foreach ($data['satkers'] ?? [] as $satker)
                                                    @php
                                                        $valNilai = $row[$satker] ?? null;
                                                    @endphp
                                                    <td class="{{ $getBadgeClass($valNilai) }}">
                                                        {{ $getTeks($valNilai) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @elseif($data['mode'] === 'rekap_satker')
                                <table id="dataTableMonitoring" class="jazirah-table">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th style="text-align: left;">Pilar</th>
                                            <th title="% Penetapan Target">% Penetapan</th>
                                            <th title="% Realisasi Periode Ini">% Realisasi</th>
                                            <th title="% Tindak Lanjut Periode Ini">% Tindak Lanjut</th>
                                            <th title="% Validasi Periode Ini">% Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['rekapData'] ?? [] as $item)
                                            <tr>
                                                <td><strong>
                                                        @if ($item->kode_2 === 'I.')
                                                            Pemenuhan
                                                        @else
                                                            Reform
                                                        @endif
                                                    </strong></td>
                                                <td style="text-align: left;"><strong>{{ $item->kode_3 }}</strong>
                                                </td>

                                                <td class="{{ $getBadgeClass($item->persentase_penetapan_target, 1, 1) }}"
                                                    title="Target Setahun: {{ $item->target_setahun }}">
                                                    {{ $getTeks($item->persentase_penetapan_target, 1, 1) }}
                                                </td>

                                                <td class="{{ $getBadgeClass($item->persentase_realisasi, $item->target_setahun, $item->target_periode) }}"
                                                    title="Target s.d Periode: {{ $item->target_periode }} | Realisasi s.d Periode: {{ $item->realisasi_periode }}">
                                                    {{ $getTeks($item->persentase_realisasi, $item->target_setahun, $item->target_periode) }}
                                                </td>

                                                <td class="{{ $getBadgeClass($item->persentase_tindaklanjut, $item->target_setahun, $item->target_periode) }}"
                                                    title="Target s.d Periode: {{ $item->target_periode }}">
                                                    {{ $getTeks($item->persentase_tindaklanjut, $item->target_setahun, $item->target_periode) }}
                                                </td>

                                                <td class="{{ $getBadgeClass($item->persentase_dokumen_selesai, $item->target_setahun, $item->target_periode) }}"
                                                    title="Target s.d Periode: {{ $item->target_periode }}">
                                                    {{ $getTeks($item->persentase_dokumen_selesai, $item->target_setahun, $item->target_periode) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"
                                                    style="text-align: center; font-style: italic; color: #718096; padding: 25px;">
                                                    Tidak ada data untuk Satker ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        @php
                            $modeTampil = request('mode', 'rekap_satker');
                            $namaSatker =
                                $modeTampil === 'rekap_satker'
                                    ? request('selected_satker', $data['satkers'][0] ?? 'Satker')
                                    : 'Se-Provinsi Aceh';

                            $periodeRaw = request('periode', 'bulan_berjalan');
                            $periodeLabels = [
                                'bulan_berjalan' => 'Bulan Berjalan',
                                'tw1' => 'Triwulan 1',
                                'tw2' => 'Triwulan 2',
                                'tw3' => 'Triwulan 3',
                                'tw4' => 'Triwulan 4',
                            ];
                            $periodeLabel = $periodeLabels[$periodeRaw] ?? 'Bulan Berjalan';

                            \Carbon\Carbon::setLocale('id');
                            $waktuTarikData = \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i');
                        @endphp

                        <div
                            style="text-align: right; margin-top: 12px; font-size: 12px; font-style: italic; color: #718096; padding-right: 5px;">
                            * Data <strong>{{ $namaSatker }}</strong> Periode <strong>{{ $periodeLabel }}</strong>
                            pada
                            {{ $waktuTarikData }} WIB.
                        </div>

                        <footer>
                            <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
                        </footer>

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
    <script src="{{ asset('assets-jazirah/style/jazirah-dashboard.js') }}"></script>

</body>

</html>
