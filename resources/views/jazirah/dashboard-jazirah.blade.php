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
        $userSatker = $userActive->kode_Satker ?? '';

        $isAdmin = $userRole === 'admin';
        $isAdminOrKepala = in_array($userRole, ['admin', 'kepala-umum']);

        // Cek Role Admin Jazirah
        $isJazirahAdmin = isset($data['role']) && strtolower($data['role']) === 'admin';

        $opsiPegawaiHtml = '<option value="">-- Pilih Pegawai / PJK --</option>';
        if (!empty($data['pegawai_prov'])) {
            foreach ($data['pegawai_prov'] as $pegawai) {
                $opsiPegawaiHtml .= '<option value="' . $pegawai->nip_pegawai . '">' . $pegawai->name . '</option>';
            }
        }
    @endphp

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
        <header>@include('layout2.navbar-se2026')</header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>

            <div class="posisitengah">
                <div class="posisitengah" style="width: 95%; max-width: 95%; margin: 0 auto;">
                    <div class="jazirah-container">

                        {{-- 1. BANNER & TYPEWRITER --}}
                        {{-- <div class="welcome-banner">
                            <div class="welcome-content">
                                <div class="character-container">
                                    <img src="{{ asset('assets-se2026/img/bungitung.gif') }}" class="char-animation"
                                        alt="Maskot BPS">
                                </div>
                                <h2><span id="typewriter"></span><span class="cursor">|</span></h2>
                            </div>
                        </div> --}}

                        {{-- 2. TOMBOL TAMBAH MENU (Terpisah dari slider agar statis) --}}
                        @if ($isJazirahAdmin)
                            <div style="text-align: right; margin-bottom: 10px;">
                                <button class="btn-tambah-menu" onclick="openMenuModal()">
                                    <i class="fa-solid fa-plus"></i> Tambah Menu
                                </button>
                            </div>
                        @endif

                        {{-- 3. MENU DINAMIS DARI DATABASE --}}
                        <div class="jazirah-menu-wrapper modern-scrollbar">
                            <div class="jazirah-menu-container">
                                @foreach ($data['menus'] ?? [] as $menu)
                                    <div class="jazirah-menu-card-wrapper" style="position: relative;">
                                        <a href="{{ $menu->url }}" class="jazirah-menu-card">
                                            <div class="jazirah-menu-icon" style="background: {{ $menu->bg }};">
                                                {!! $menu->icon !!}
                                            </div>
                                            <h3 class="jazirah-menu-title">{{ $menu->title }}</h3>
                                        </a>

                                        {{-- Aksi Edit/Delete Khusus Admin --}}
                                        @if ($isJazirahAdmin)
                                            <div class="admin-menu-actions">
                                                <button type="button" class="btn-aksi btn-edit-menu"
                                                    onclick="editMenu({{ json_encode($menu) }})" title="Edit Menu"><i
                                                        class="fa-solid fa-pen"></i></button>
                                                <form action="{{ url('/jazirah-menu/' . $menu->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Hapus menu ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-aksi btn-delete-menu"
                                                        title="Hapus Menu"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <br>

                        {{-- 4. KONTROL FILTER TABEL --}}
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
                                            Rekap Detail Per Satker</option>
                                        <option value="lintas_satker"
                                            {{ request('mode') == 'lintas_satker' ? 'selected' : '' }}>Rekap Lintas
                                            Satker (Se-Provinsi)</option>
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
                                            Triwulan 1</option>
                                        <option value="tw2" {{ request('periode') == 'tw2' ? 'selected' : '' }}>
                                            Triwulan 2</option>
                                        <option value="tw3" {{ request('periode') == 'tw3' ? 'selected' : '' }}>
                                            Triwulan 3</option>
                                        <option value="tw4" {{ request('periode') == 'tw4' ? 'selected' : '' }}>
                                            Triwulan 4</option>
                                    </select>
                                </div>

                                @if (request('mode', 'rekap_satker') == 'lintas_satker')
                                    <div class="jazirah-input-group">
                                        <label for="jenis_data">Pilih Data:</label>
                                        <select name="jenis_data" id="jenis_data" onchange="this.form.submit()"
                                            class="jazirah-select">
                                            <optgroup label="Data Metrik (Jumlah)">
                                                <option value="target_setahun"
                                                    {{ request('jenis_data') == 'target_setahun' ? 'selected' : '' }}>
                                                    Jumlah Target Setahun</option>
                                                <option value="target_periode"
                                                    {{ request('jenis_data') == 'target_periode' ? 'selected' : '' }}>
                                                    Jumlah Target Periode</option>
                                                <option value="realisasi_periode"
                                                    {{ request('jenis_data') == 'realisasi_periode' ? 'selected' : '' }}>
                                                    Jumlah Realisasi</option>
                                                <option value="perlu_di_periksa"
                                                    {{ request('jenis_data') == 'perlu_di_periksa' ? 'selected' : '' }}>
                                                    Jumlah Perlu Diperiksa</option>
                                                <option value="perlu_tindak_lanjut"
                                                    {{ request('jenis_data') == 'perlu_tindak_lanjut' ? 'selected' : '' }}>
                                                    Jumlah Perlu Tindak Lanjut</option>
                                                <option value="sudah_validasi"
                                                    {{ request('jenis_data') == 'sudah_validasi' ? 'selected' : '' }}>
                                                    Jumlah Sudah Validasi</option>
                                            </optgroup>
                                            <optgroup label="Data Kinerja (Persentase)">
                                                <option value="persentase_penetapan_target"
                                                    {{ request('jenis_data', 'persentase_penetapan_target') == 'persentase_penetapan_target' ? 'selected' : '' }}>
                                                    % Penetapan Target</option>
                                                <option value="persentase_realisasi"
                                                    {{ request('jenis_data') == 'persentase_realisasi' ? 'selected' : '' }}>
                                                    % Realisasi</option>
                                                <option value="persentase_evaluasi"
                                                    {{ request('jenis_data') == 'persentase_evaluasi' ? 'selected' : '' }}>
                                                    % Evaluasi</option>
                                                <option value="persentase_tindaklanjut"
                                                    {{ request('jenis_data') == 'persentase_tindaklanjut' ? 'selected' : '' }}>
                                                    % Tindak Lanjut</option>
                                                <option value="persentase_dokumen_selesai"
                                                    {{ request('jenis_data') == 'persentase_dokumen_selesai' ? 'selected' : '' }}>
                                                    % Dokumen Selesai/Validasi</option>
                                            </optgroup>
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
                                                    {{ $satker }}
                                                </option>
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

                        {{-- 5. TABEL MONITORING (SEMUA KOLOM) --}}
                        <div class="jazirah-table-responsive" id="tabel-monitoring">
                            @php
                                $isPercentage = str_contains(
                                    $data['jenis_data'] ?? 'persentase_penetapan_target',
                                    'persentase',
                                );

                                $getBadgeClass = function ($val, $targetSetahun = 1, $targetPeriode = 1) use (
                                    $isPercentage,
                                ) {
                                    if ($targetSetahun > 0 && $targetPeriode == 0) {
                                        return 'badge-notarget';
                                    }
                                    if ($val === null) {
                                        return 'badge-belum-isi';
                                    }

                                    if ($isPercentage) {
                                        if ($val >= 100) {
                                            return 'badge-sempurna';
                                        }
                                        if ($val > 0 && $val < 100) {
                                            return 'badge-sebagian';
                                        }
                                        if ($val === '0' || $val === 0 || $val === '0.00') {
                                            return 'badge-nol';
                                        }
                                    } else {
                                        return 'badge-sebagian';
                                    }
                                    return 'badge-kosong';
                                };

                                $getTeks = function ($val, $targetSetahun = 1, $targetPeriode = 1) use ($isPercentage) {
                                    if ($targetSetahun > 0 && $targetPeriode == 0) {
                                        return 'Tidak Ada Target';
                                    }
                                    if ($val === null) {
                                        return 'Belum Isi';
                                    }
                                    if ($val === '') {
                                        return '-';
                                    }

                                    return $isPercentage ? $val . '%' : $val;
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
                                                <td><strong>{{ $row['indikator'] === 'I.' ? 'Pemenuhan' : 'Reform' }}</strong>
                                                </td>
                                                <td style="text-align: left;"><strong>{{ $row['pilar'] }}</strong>
                                                </td>
                                                @foreach ($data['satkers'] ?? [] as $satker)
                                                    @php $valNilai = $row[$satker] ?? null; @endphp
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
                                            <th rowspan="2">Kode</th>
                                            <th rowspan="2" style="text-align: left; vertical-align: middle;">Pilar
                                            </th>
                                            <th colspan="8"
                                                style="border-bottom: 1px solid rgba(255,255,255,0.2);">Monitoring
                                                Evaluasi</th>
                                        </tr>
                                        <tr>
                                            <th title="Jumlah Target Setahun">T. Setahun</th>
                                            <th title="Jumlah Target Sampai Periode Ini">T. Periode</th>
                                            <th title="Jumlah Realisasi">Realisasi</th>
                                            <th title="% Realisasi Periode Ini">% Realisasi</th>
                                            <th title="Jumlah Dokumen Yang Perlu Diperiksa Validator">Perlu Diperiksa
                                            </th>
                                            <th title="Jumlah Dokumen Yang Perlu Ditindaklanjuti">Perlu T. Lanjut</th>
                                            <th title="Jumlah Dokumen Valid">Valid</th>
                                            <th title="% Validasi Periode Ini">% Validasi</th>

                                            {{-- <th title="% Penetapan Target">% Penetapan</th> --}}
                                            {{-- <th title="% Evaluasi Periode Ini">% Evaluasi</th> --}}
                                            {{-- <th title="% Tindak Lanjut Periode Ini">% T. Lanjut</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['rekapData'] ?? [] as $item)
                                            <tr>
                                                <td><strong>{{ $item->kode_2 === 'I.' ? 'Pemenuhan' : 'Reform' }}</strong>
                                                </td>
                                                <td style="text-align: left;"><strong>{{ $item->kode_3 }}</strong>
                                                </td>

                                                <td style="text-align: center; font-weight: 500;">
                                                    {{ $item->target_setahun }}</td>
                                                <td style="text-align: center; font-weight: 500;">
                                                    {{ $item->target_periode }}</td>
                                                <td style="text-align: center; font-weight: 500;">
                                                    {{ $item->realisasi_periode }}</td>
                                                <td
                                                    class="{{ $getBadgeClass($item->persentase_realisasi, $item->target_setahun, $item->target_periode) }}">
                                                    {{ $getTeks($item->persentase_realisasi, $item->target_setahun, $item->target_periode) }}
                                                </td>
                                                <td style="text-align: center; font-weight: 500; color: #d97706;">
                                                    {{ $item->perlu_di_periksa }}</td>
                                                <td style="text-align: center; font-weight: 500; color: #dc2626;">
                                                    {{ $item->perlu_tindak_lanjut }}</td>
                                                <td style="text-align: center; font-weight: 500; color: #059669;">
                                                    {{ $item->sudah_validasi }}</td>
                                                <td
                                                    class="{{ $getBadgeClass($item->persentase_dokumen_selesai, $item->target_setahun, $item->target_periode) }}">
                                                    {{ $getTeks($item->persentase_dokumen_selesai, $item->target_setahun, $item->target_periode) }}
                                                </td>

                                                {{-- <td
                                                    class="{{ $getBadgeClass($item->persentase_penetapan_target, 1, 1) }}">
                                                    {{ $getTeks($item->persentase_penetapan_target, 1, 1) }}
                                                </td> --}}
                                                {{-- <td
                                                    class="{{ $getBadgeClass($item->persentase_evaluasi, $item->target_setahun, $item->target_periode) }}">
                                                    {{ $getTeks($item->persentase_evaluasi, $item->target_setahun, $item->target_periode) }}
                                                </td>
                                                <td
                                                    class="{{ $getBadgeClass($item->persentase_tindaklanjut, $item->target_setahun, $item->target_periode) }}">
                                                    {{ $getTeks($item->persentase_tindaklanjut, $item->target_setahun, $item->target_periode) }}
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13"
                                                    style="text-align: center; font-style: italic; color: #718096; padding: 25px;">
                                                    Tidak ada data untuk Satker ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @endif

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
                                * Data <strong>{{ $namaSatker }}</strong> Periode
                                <strong>{{ $periodeLabel }}</strong> pada {{ $waktuTarikData }} WIB.
                            </div>
                        </div>

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

            {{-- Modal Form Menu (Khusus Admin) --}}
            @if ($isJazirahAdmin)
                <div class="modal-overlay" id="modalMenuForm" style="display: none;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="menuModalTitle">Tambah Menu Baru</h3>
                        </div>
                        <form id="formMenuAction" action="{{ url('/jazirah-menu') }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="menuMethod" value="POST">
                            <div class="modal-body" style="padding-top: 5px;">
                                <div class="grup-input">
                                    <label>Judul Menu</label>
                                    <input type="text" name="title" id="menuTitle" class="input-form"
                                        placeholder="Contoh: Lembar Kerja" required>
                                </div>
                                <div class="grup-input">
                                    <label>URL / Tautan</label>
                                    <input type="text" name="url" id="menuUrl" class="input-form"
                                        placeholder="{{ url('/path-tujuan') }}" required>
                                </div>
                                <div class="grup-input">
                                    <label>Pilih Background Warna (Gradient)</label>
                                    <select name="bg" id="menuBg" class="input-form" required
                                        style="cursor: pointer; appearance: auto;">
                                        <option value="">-- Pilih Warna Background --</option>
                                        <option value="linear-gradient(135deg, #3b82f6, #06b6d4)">🔵 Biru Cyan</option>
                                        <option value="linear-gradient(135deg, #fbbf24, #eab308)">🟡 Kuning Emas
                                        </option>
                                        <option value="linear-gradient(135deg, #6366f1, #2563eb)">🟦 Biru Indigo
                                        </option>
                                        <option value="linear-gradient(135deg, #a855f7, #6366f1)">🟪 Ungu Muda</option>
                                        <option value="linear-gradient(135deg, #34d399, #14b8a6)">🟩 Hijau Tosca
                                        </option>
                                        <option value="linear-gradient(135deg, #fb7185, #ef4444)">🟥 Merah Pink
                                        </option>
                                        <option value="linear-gradient(135deg, #d946ef, #9333ea)">🟪 Ungu Tua</option>
                                        <option value="linear-gradient(135deg, #fb923c, #c2410c)">🟧 Oranye Merah
                                        </option>
                                        <option value="linear-gradient(135deg, #22d3ee, #3b82f6)">🌐 Biru Langit
                                        </option>
                                        <option value="linear-gradient(135deg, #4ade80, #059669)">🌿 Hijau Segar
                                        </option>
                                        <option value="linear-gradient(135deg, #2dd4bf, #10b981)">🌲 Hijau Emerald
                                        </option>
                                        <option value="linear-gradient(135deg, #475569, #1e293b)">⬛ Abu-Abu Gelap
                                        </option>
                                    </select>
                                </div>
                                <div class="grup-input">
                                    <label>Icon (Script HTML FontAwesome)</label>
                                    <input type="text" name="icon" id="menuIcon" class="input-form"
                                        placeholder='<i class="fa-solid fa-star"></i>' required>
                                </div>
                                <div class="grup-input">
                                    <label>Urutan Tampil</label>
                                    <input type="number" name="urutan" id="menuUrutan" class="input-form"
                                        value="0" required>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding: 15px 30px; border-top: 1px solid #f3f4f6;">
                                <button type="button"
                                    style="background: #94a3b8; color: white; padding: 10px 22px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-right: 10px;"
                                    onclick="closeMenuModal()">Batal</button>
                                <button type="submit" class="btn-simpan">Simpan Menu</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <br>
        </div>
        <footer>
            <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
        </footer>
    </div>

    {{-- Bridge PHP to JS (Disempurnakan) --}}
    <script>
        window.BupestaConfig = {
            userName: "{{ $userActive->name ?? 'Guest' }}",
            successMessage: "{{ session('success') }}",
            opsiPegawaiHtml: `{!! $opsiPegawaiHtml !!}`
        };
        window.userActiveNip = "{{ $userNip }}";
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-jazirah/style/jazirah-dashboard.js') }}"></script>

</body>

</html>
