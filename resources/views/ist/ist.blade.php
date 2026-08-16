<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon & Fonts -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets-se2026/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-ist/ist.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>

    {{-- AWAL BLOK OPTIMASI LOGIKA & DATA REUSABLE --}}
    @php
        $userActive = $data['user_active'] ?? null;
        $userRole = $userActive->bupesta ?? '';
        $userNip = $userActive->nip_pegawai ?? '';
        $userSatker = $userActive->kode_Satker ?? ''; // Asumsi key: kode_Satker
    @endphp
    {{-- AKHIR BLOK OPTIMASI --}}

    <!-- Warning Landscape Mode -->
    <div id="orientation-warning" style="display: none;">
        <h1>Putar Perangkat Anda</h1>
        <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
        <div class="phone-wrapper">
            <div class="screen"></div>
            <div class="button"></div>
        </div>
    </div>

    <!-- Loading Screen -->
    <div id="loading">
        <div id="loader-wrapper">
            <div id="loader"></div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>

    <!-- Main Page -->
    <div id="page">
        <header>
            @include('layout2.navbar-se2026')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>

            {{-- AWAL BLOK OPTIMASI FORMAT TANGGAL --}}
            @php
                if (!function_exists('formatTanggal')) {
                    function formatTanggal($tanggal)
                    {
                        if (!$tanggal) {
                            return '-';
                        }
                        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        $tgl = date('d', strtotime($tanggal));
                        $bln = $bulan[date('n', strtotime($tanggal)) - 1];
                        $thn = date('Y', strtotime($tanggal));
                        return "$tgl $bln $thn";
                    }
                }
            @endphp
            {{-- AKHIR BLOK OPTIMASI --}}

            <div class="posisitengah">
                {{-- Menggunakan .konten-ist agar tidak ada background oranye bawaan --}}
                <div class="konten-ist">

                    {{-- TOMBOL AKSI (ADMIN, PANITIA, KEPALA, KEPALA-KAKO) --}}
                    <div
                        style="display: flex; justify-content: flex-end; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">

                        @if (in_array($data['user_active']->ist ?? '', ['admin', 'panitia', 'kepala', 'kepala-kako']))
                            <button type="button" class="btn-tambah-tim" style="background-color: #10b981;"
                                onclick="bukaModalMonitoring()">
                                <i class="fa-solid fa-chart-pie"></i> Monitoring Penilaian
                            </button>


                            {{-- MODAL MONITORING PENILAIAN --}}
                            <div id="modalMonitoring" class="modal-overlay">
                                <div class="modal-content modal-xxl">
                                    <div class="modal-header">
                                        <h3><i class="fa-solid fa-chart-pie"></i> Monitoring Partisipasi Penilaian</h3>
                                        <span class="tutup-modal" onclick="tutupModalMonitoring()">&times;</span>
                                    </div>

                                    <div class="modal-body" style="background: #f8fafc; padding: 20px 25px;">
                                        @if (!empty($monitoringData))

                                            {{-- MENU TABS MONITORING --}}
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #cbd5e1; padding-bottom: 0; flex-wrap: wrap; gap: 10px;">
                                                <div class="ist-tabs-header" style="margin-bottom: 0; border-bottom: none;">
                                                    <button type="button" class="ist-tab-btn active" id="btn-mon-1_1"
                                                        onclick="switchMonTab('1_1')">
                                                        <i class="fa-solid fa-users"></i> Tahap 1.1 (Penilaian Pegawai)
                                                    </button>
                                                    <button type="button" class="ist-tab-btn" id="btn-mon-1_2"
                                                        onclick="switchMonTab('1_2')">
                                                        <i class="fa-solid fa-user-check"></i> Tahap 1.2 (Penetapan
                                                        Pemenang)
                                                    </button>
                                                </div>
                                                <div id="mon-download-btn-wrapper" style="padding-bottom: 8px;">
                                                    <button type="button" id="btn-download-mon-1_1" onclick="downloadMonitoringImage('mon-tab-1_1', 'monitoring-tahap-1-1')" style="background: linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 2px 8px rgba(99,102,241,0.35); transition:all 0.2s;">
                                                        <i class="fa-solid fa-image"></i> Download Gambar
                                                    </button>
                                                    <button type="button" id="btn-download-mon-1_2" onclick="downloadMonitoringImage('mon-tab-1_2', 'monitoring-tahap-1-2')" style="background: linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; display:none; align-items:center; gap:6px; box-shadow:0 2px 8px rgba(99,102,241,0.35); transition:all 0.2s;">
                                                        <i class="fa-solid fa-image"></i> Download Gambar
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- ====================================================== --}}
                                            {{-- KONTEN TAB 1: TAHAP 1.1 (YANG LAMA) --}}
                                            {{-- ====================================================== --}}
                                            <div id="mon-tab-1_1"
                                                style="display: block; animation: tabFadeIn 0.4s ease;">
                                                <div id="rekap-partisipasi-wrapper">
                                                <h4 style="margin-bottom: 15px; color: #1e293b;"><i
                                                        class="fa-solid fa-chart-bar"></i> Rekapitulasi Partisipasi</h4>
                                                <div
                                                    style="overflow-x: auto; margin-bottom: 30px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                                                    <table class="ist-table" id="tbl-mon-1_1">
                                                        <thead>
                                                            <tr>
                                                                <th data-sort-col="0" data-sort-type="string" style="cursor:pointer; user-select:none;">Satuan Kerja <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="1" data-sort-type="number" style="text-align: center; cursor:pointer; user-select:none;">Total Pegawai <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="2" data-sort-type="number" style="text-align: center; color: #10b981; cursor:pointer; user-select:none;">Sudah Menilai <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="3" data-sort-type="number" style="text-align: center; color: #ef4444; cursor:pointer; user-select:none;">Belum Menilai <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="4" data-sort-type="number" style="cursor:pointer; user-select:none;" width="25%">Progress Partisipasi <span class="sort-icon">⇅</span></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($monitoringData['rekap'] as $satker => $dataMon)
                                                                @php $persen = $dataMon['total'] > 0 ? round(($dataMon['sudah'] / $dataMon['total']) * 100) : 0; @endphp
                                                                <tr>
                                                                    <td data-val="{{ $satker }}" style="font-weight: 600; color: #334155;">
                                                                        {{ $satker }}</td>
                                                                    <td data-val="{{ $dataMon['total'] }}" style="text-align: center; font-weight: bold;">
                                                                        {{ $dataMon['total'] }}</td>
                                                                    <td data-val="{{ $dataMon['sudah'] }}"
                                                                        style="text-align: center; font-weight: bold; color: #10b981;">
                                                                        {{ $dataMon['sudah'] }}</td>
                                                                    <td data-val="{{ $dataMon['belum'] }}"
                                                                        style="text-align: center; font-weight: bold; color: #ef4444;">
                                                                        {{ $dataMon['belum'] }}</td>
                                                                    <td data-val="{{ $persen }}">
                                                                        <div
                                                                            style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px;">
                                                                            <span>{{ $persen }}% Selesai</span>
                                                                        </div>
                                                                        <div class="ist-progress-bg">
                                                                            <div class="ist-progress-fill"
                                                                                style="width: {{ $persen }}%;">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                </div>{{-- end #rekap-partisipasi-wrapper --}}

                                                <h4
                                                    style="margin-bottom: 15px; color: #1e293b; border-top: 2px dashed #e2e8f0; padding-top: 20px;">
                                                    <i class="fa-solid fa-users-slash"></i> Rincian Pegawai Belum
                                                    Menilai
                                                </h4>

                                                @forelse($monitoringData['detail_belum'] as $satker => $pegawais)
                                                    <div
                                                        style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #ef4444; margin-bottom: 15px; position: relative;">
                                                        <strong
                                                            style="display: block; color: #334155; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 5px; padding-right: 90px;">
                                                            {{ $satker }} ({{ count($pegawais) }} Orang)
                                                        </strong>
                                                        <div style="font-size: 13px; color: #475569; line-height: 1.6;">
                                                            Berikut nama-nama pegawai yang belum melakukan penilaian
                                                            IST:<br>
                                                            @foreach ($pegawais as $index => $p)
                                                                {{ $index + 1 }}. {{ $p->nama }}<br>
                                                            @endforeach
                                                        </div>
                                                        <textarea id="copy-satker-{{ $loop->index }}" style="display:none;">Berikut nama-nama pegawai yang belum melakukan penilaian IST:
@foreach ($pegawais as $index => $p)
{{ $index + 1 }}. {{ $p->nama }}
@endforeach
</textarea>
                                                        <button type="button"
                                                            onclick="copyDaftarWA('copy-satker-{{ $loop->index }}')"
                                                            style="position: absolute; top: 10px; right: 15px; background: #25D366; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 5px rgba(37, 211, 102, 0.3);">
                                                            <i class="fa-brands fa-whatsapp"
                                                                style="font-size: 14px; margin-right: 4px;"></i> Copy WA
                                                        </button>
                                                    </div>
                                                @empty
                                                    <div
                                                        style="text-align: center; padding: 20px; background: #ecfdf5; border-radius: 8px; color: #059669; font-weight: bold;">
                                                        <i class="fa-solid fa-check-double"></i> Sempurna! Seluruh
                                                        pegawai di wilayah pantauan Anda telah melakukan penilaian.
                                                    </div>
                                                @endforelse
                                            </div>

                                            {{-- ====================================================== --}}
                                            {{-- KONTEN TAB 2: TAHAP 1.2 (BARU) --}}
                                            {{-- ====================================================== --}}
                                            <div id="mon-tab-1_2"
                                                style="display: none; animation: tabFadeIn 0.4s ease;">
                                                <h4 style="margin-bottom: 15px; color: #1e293b;"><i
                                                        class="fa-solid fa-clipboard-check"></i> Status Penetapan
                                                    Perwakilan Satker</h4>
                                                <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                                                    <table class="ist-table" id="tbl-mon-1_2" style="min-width: 750px;">
                                                        <thead>
                                                            <tr>
                                                                <th data-sort-col="0" data-sort-type="string" style="cursor:pointer; user-select:none; min-width:160px;">Satuan Kerja <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="1" data-sort-type="string" style="text-align: center; cursor:pointer; user-select:none; white-space:nowrap; width:130px;">Status Penetapan <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="2" data-sort-type="string" style="cursor:pointer; user-select:none; min-width:140px;">Nama Kandidat <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="3" data-sort-type="string" style="text-align: center; cursor:pointer; user-select:none; white-space:nowrap; width:100px;">Dokumen SK <span class="sort-icon">⇅</span></th>
                                                                <th data-sort-col="4" data-sort-type="string" style="text-align: center; cursor:pointer; user-select:none; white-space:nowrap; width:130px;">Status Validasi SK <span class="sort-icon">⇅</span></th>
                                                                {{-- Kolom Aksi hanya untuk admin & panitia (satker 1100) --}}
                                                                @if (in_array($userRole, ['admin', 'panitia']))
                                                                    <th style="text-align: center; white-space:nowrap; width:100px;">Aksi</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($monitoringData['tahap1_2'] as $satker => $dataMon2)
                                                                <tr>
                                                                    <td data-val="{{ $satker }}" style="font-weight: 600; color: #334155; font-size: 12px;">{{ $satker }}</td>
                                                                    <td data-val="{{ $dataMon2['status'] }}" style="text-align: center;">
                                                                        @if ($dataMon2['status'] == 'Sudah')
                                                                            <span style="display:inline-flex; align-items:center; gap:4px; background:#dcfce7; color:#16a34a; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:bold; border:1px solid #86efac; white-space:nowrap;">
                                                                                <i class="fa-solid fa-check"></i> Ditetapkan
                                                                            </span>
                                                                        @else
                                                                            <span style="display:inline-flex; align-items:center; gap:4px; background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:bold; border:1px solid #fca5a5; white-space:nowrap;">
                                                                                <i class="fa-solid fa-xmark"></i> Belum
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td data-val="{{ $dataMon2['nama_kandidat'] }}"
                                                                        style="font-weight: {{ $dataMon2['status'] == 'Sudah' ? '600' : 'normal' }}; color: {{ $dataMon2['status'] == 'Sudah' ? '#1e293b' : '#94a3b8' }}; font-size: 12px;">
                                                                        {{ $dataMon2['nama_kandidat'] }}
                                                                    </td>
                                                                    <td data-val="{{ $dataMon2['link_sk'] ? 'ada' : '-' }}" style="text-align: center;">
                                                                        @if (!empty($dataMon2['link_sk']))
                                                                            <a href="{{ $dataMon2['link_sk'] }}" target="_blank"
                                                                                style="display:inline-flex; align-items:center; gap:5px; background:#eff6ff; color:#1d4ed8; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:700; text-decoration:none; border:1px solid #bfdbfe; white-space:nowrap;">
                                                                                <i class="fa-solid fa-file-pdf"></i> Lihat SK
                                                                            </a>
                                                                        @else
                                                                            <span style="color:#cbd5e1; font-size:11px;">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td data-val="{{ $dataMon2['validasi_sk'] ?? '-' }}" style="text-align: center;">
                                                                        @if ($dataMon2['validasi_sk'] === 'tervalidasi')
                                                                            <span style="display:inline-flex; align-items:center; gap:4px; background:#dcfce7; color:#16a34a; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:bold; border:1px solid #86efac; white-space:nowrap;">
                                                                                <i class="fa-solid fa-circle-check"></i> Tervalidasi
                                                                            </span>
                                                                        @elseif ($dataMon2['validasi_sk'] === 'ditolak')
                                                                            <span style="display:inline-flex; align-items:center; gap:4px; background:#fee2e2; color:#dc2626; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:bold; border:1px solid #fca5a5; white-space:nowrap;">
                                                                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                                                            </span>
                                                                        @elseif (!empty($dataMon2['link_sk']))
                                                                            <span style="display:inline-flex; align-items:center; gap:4px; background:#fefce8; color:#ca8a04; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:bold; border:1px solid #fde68a; white-space:nowrap;">
                                                                                <i class="fa-solid fa-clock"></i> Menunggu
                                                                            </span>
                                                                        @else
                                                                            <span style="color:#cbd5e1; font-size:11px;">-</span>
                                                                        @endif
                                                                    </td>
                                                                    {{-- Tombol Validasi hanya untuk admin & panitia --}}
                                                                    @if (in_array($userRole, ['admin', 'panitia']))
                                                                        <td style="text-align: center;">
                                                                            @if (!empty($dataMon2['link_sk']))
                                                                                <button type="button"
                                                                                    onclick="bukaPopupValidasiSk(
                                                                                        '{{ $dataMon2['kode_wilayah'] }}',
                                                                                        '{{ $satker }}',
                                                                                        '{{ $dataMon2['nama_kandidat'] }}',
                                                                                        '{{ $dataMon2['link_sk'] }}',
                                                                                        '{{ $dataMon2['validasi_sk'] ?? '' }}',
                                                                                        {{ $periode->id }},
                                                                                        '{{ $dataMon2['nip_kandidat'] ?? '' }}'
                                                                                    )"
                                                                                    style="background: linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border:none; padding:5px 12px; border-radius:20px; font-size:10px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px; box-shadow:0 2px 6px rgba(99,102,241,0.35); white-space:nowrap;">
                                                                                    <i class="fa-solid fa-shield-check"></i> Validasi SK
                                                                                </button>
                                                                            @else
                                                                                <span style="color:#cbd5e1; font-size:11px;">-</span>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div style="text-align: center; padding: 30px; color: #64748b;">Data
                                                monitoring belum tersedia.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- ================================================================ --}}
                        {{-- MODAL POPUP VALIDASI SK (KHUSUS ADMIN & PANITIA) --}}
                        {{-- ================================================================ --}}
                        @if (in_array($userRole, ['admin', 'panitia']))
                        <div id="modalValidasiSk" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
                            <div style="background:#fff; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,0.25); width:100%; max-width:520px; overflow:hidden; animation: tabFadeIn 0.3s ease;">

                                {{-- Header --}}
                                <div style="background:linear-gradient(135deg,#6366f1,#4f46e5); padding:20px 25px; display:flex; justify-content:space-between; align-items:center;">
                                    <h3 style="color:#fff; margin:0; font-size:16px; display:flex; align-items:center; gap:10px;">
                                        <i class="fa-solid fa-shield-check"></i> Validasi SK Penetapan
                                    </h3>
                                    <button type="button" onclick="tutupPopupValidasiSk()"
                                        style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:32px; height:32px; border-radius:50%; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1;">&times;</button>
                                </div>

                                {{-- Body --}}
                                <div style="padding:25px;">
                                    <div style="background:#f8fafc; border-radius:12px; padding:16px; margin-bottom:20px; border:1px solid #e2e8f0;">
                                        <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Satuan Kerja</div>
                                        <div id="popup-sk-satker" style="font-size:14px; font-weight:700; color:#1e293b;"></div>
                                        <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-top:12px; margin-bottom:4px;">Nama Kandidat</div>
                                        <div id="popup-sk-nama" style="font-size:14px; font-weight:700; color:#334155;"></div>
                                    </div>

                                    <div style="margin-bottom:20px;">
                                        <div style="font-size:12px; color:#64748b; font-weight:600; margin-bottom:8px;"><i class="fa-solid fa-file-pdf"></i> Dokumen SK</div>
                                        <a id="popup-sk-link" href="#" target="_blank"
                                            style="display:flex; align-items:center; gap:8px; background:#eff6ff; color:#1d4ed8; padding:10px 16px; border-radius:10px; font-size:13px; font-weight:700; text-decoration:none; border:1px solid #bfdbfe;">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            <span id="popup-sk-link-text">Buka Dokumen SK</span>
                                        </a>
                                    </div>

                                    <div id="popup-sk-status-box" style="text-align:center; margin-bottom:20px;"></div>

                                    {{-- Form Aksi --}}
                                    <form id="form-validasi-sk-popup" action="{{ route('ist.validasiSk') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="periode_id" id="popup-sk-periode-id">
                                        <input type="hidden" name="nip_kandidat" id="popup-sk-nip">
                                        <input type="hidden" name="aksi" id="popup-sk-aksi">
                                    </form>

                                    <div id="popup-sk-buttons-area" style="display:flex; gap:10px; flex-wrap:wrap;">
                                        {{-- Tombol-tombol akan di-render dari JS sesuai status validasi_sk --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL KONFIRMASI (TEMA ORANGE) --}}
                        <div id="modalConfirmSk" style="display:none; position:fixed; inset:0; z-index:10000; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
                            <div style="background:#fff; border-radius:20px; box-shadow:0 25px 60px rgba(0,0,0,0.25); width:100%; max-width:400px; overflow:hidden; animation: tabFadeIn 0.3s ease;">
                                <div style="background:linear-gradient(135deg,#f97316,#ea580c); padding:20px 25px; display:flex; justify-content:space-between; align-items:center;">
                                    <h3 style="color:#fff; margin:0; font-size:16px; display:flex; align-items:center; gap:10px;">
                                        <i class="fa-solid fa-circle-question"></i> Konfirmasi Aksi
                                    </h3>
                                    <button type="button" onclick="document.getElementById('modalConfirmSk').style.display='none'"
                                        style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:32px; height:32px; border-radius:50%; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1;">&times;</button>
                                </div>
                                <div style="padding:25px; text-align:center;">
                                    <p id="teks-konfirmasi-sk" style="font-size:14px; color:#334155; margin-bottom:20px; font-weight:500;"></p>
                                    <div style="display:flex; gap:10px; justify-content:center;">
                                        <button type="button" onclick="document.getElementById('modalConfirmSk').style.display='none'"
                                            style="background:#f1f5f9; color:#64748b; border:none; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer;">
                                            Batal
                                        </button>
                                        <button type="button" id="btn-lanjutkan-sk"
                                            style="background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; border:none; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(249,115,22,0.4);">
                                            Ya, Lanjutkan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- END MODAL POPUP VALIDASI SK --}}

                        {{-- Tombol Manajemen Pertanyaan & Pengaturan Periode yang sebelumnya tetap di bawah sini... --}}
                        @if (in_array($data['user_active']->ist ?? '', ['admin']))
                            <button type="button" class="btn-tambah-tim" style="background-color: #3b82f6;"
                                onclick="bukaModalPertanyaan()">
                                <i class="fa-solid fa-clipboard-question"></i> Manajemen Pertanyaan
                            </button>
                            <button type="button" class="btn-tambah-tim" onclick="bukaModalPeriode()">
                                <i class="fa-solid fa-sliders"></i> Pengaturan Periode IST
                            </button>

                            {{-- MODAL MANAJEMEN PERTANYAAN (MENGGUNAKAN CLASS GLOBAL) --}}
                            <div id="modalPertanyaan" class="modal-overlay">
                                <div class="modal-content modal-xl">
                                    <div class="modal-header">
                                        <h3><i class="fa-solid fa-list-check"></i> Manajemen Pertanyaan Kuesioner</h3>
                                        <span class="tutup-modal" onclick="tutupModalPertanyaan()">&times;</span>
                                    </div>

                                    <form action="{{ route('ist.storePertanyaan') }}" method="POST">
                                        @csrf
                                        <div class="modal-body" id="wadah-pertanyaan">
                                            {{-- Loop Data Pertanyaan yang Sudah Ada --}}
                                            @foreach ($pertanyaanKuesioner as $q)
                                                <div class="grup-input baris-pertanyaan"
                                                    style="flex-direction: row; gap: 15px; align-items: flex-start; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px;">
                                                    <input type="hidden" name="id_pertanyaan[]"
                                                        value="{{ $q->id }}">
                                                    <div style="flex: 1;">
                                                        <textarea name="pertanyaan[]" class="input-form" rows="2" style="padding-top:10px;" required>{{ $q->pertanyaan }}</textarea>
                                                    </div>
                                                    <div style="width: 120px;">
                                                        <select name="is_active[]" class="input-form">
                                                            <option value="1"
                                                                {{ $q->is_active ? 'selected' : '' }}>
                                                                Aktif</option>
                                                            <option value="0"
                                                                {{ !$q->is_active ? 'selected' : '' }}>
                                                                Nonaktif</option>
                                                        </select>
                                                    </div>
                                                    <button type="button" class="btn-hapus-baris"
                                                        onclick="this.closest('.baris-pertanyaan').remove()">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div style="padding: 0 20px;">
                                            <button type="button" class="btn-tambah-kegiatan"
                                                onclick="tambahBarisPertanyaan()">+ Tambah Pertanyaan</button>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn-simpan"><i
                                                    class="fa-solid fa-floppy-disk"></i>
                                                Simpan Pertanyaan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- MODAL PENGATURAN PERIODE (MENGGUNAKAN CLASS GLOBAL) --}}
                            <div id="modalPeriode" class="modal-overlay">
                                {{-- Tambahan class .modal-xl bawaan template --}}
                                <div class="modal-content modal-xl">
                                    <div class="modal-header">
                                        <h3><i class="fa-solid fa-calendar-days"></i> Pengaturan Periode IST
                                            ({{ $tahun }})</h3>
                                        <span class="tutup-modal" onclick="tutupModalPeriode()">&times;</span>
                                    </div>

                                    <form action="{{ route('ist.updatePeriode') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">

                                        <div class="modal-body">
                                            {{-- Menggunakan class .grup-input & .input-form bawaan template --}}
                                            <div class="grup-input">
                                                <label>Status Pelaksanaan</label>
                                                <select name="is_active" class="input-form" required>
                                                    <option value="1"
                                                        {{ ($periode->is_active ?? 0) == 1 ? 'selected' : '' }}>Aktif
                                                        (Berjalan)</option>
                                                    <option value="0"
                                                        {{ ($periode->is_active ?? 0) == 0 ? 'selected' : '' }}>Ditutup
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="grup-input">
                                                <label>Informasi Persiapan Tahap 1</label>
                                                <textarea name="informasi_persiapan" class="input-form" rows="3"
                                                    placeholder="Informasi penting untuk tahapan ini..." style="padding-top:10px;">{{ $periode->informasi_persiapan ?? '' }}</textarea>
                                            </div>

                                            <h4 class="ist-modal-subtitle"><i class="fa-solid fa-clock"></i> Jadwal
                                                Pelaksanaan</h4>

                                            {{-- Grid Khusus IST untuk merapikan tanggal --}}
                                            <div class="ist-grid-jadwal">
                                                {{-- Jadwal Tahap 1 --}}
                                                <div class="ist-card-jadwal">
                                                    <strong>Tahap 1.1 (Pemilihan)</strong>
                                                    <div class="grup-input" style="margin-bottom: 5px;">
                                                        <label>Mulai:</label>
                                                        <input type="date" name="mulai_tahap1_1"
                                                            class="input-form"
                                                            value="{{ $periode->mulai_tahap1_1 ?? '' }}">
                                                    </div>
                                                    <div class="grup-input" style="margin-bottom: 0;">
                                                        <label>Akhir:</label>
                                                        <input type="date" name="akhir_tahap1_1"
                                                            class="input-form"
                                                            value="{{ $periode->akhir_tahap1_1 ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="ist-card-jadwal">
                                                    <strong>Tahap 1.2 (Penetapan)</strong>
                                                    <div class="grup-input" style="margin-bottom: 5px;">
                                                        <label>Mulai:</label>
                                                        <input type="date" name="mulai_tahap1_2"
                                                            class="input-form"
                                                            value="{{ $periode->mulai_tahap1_2 ?? '' }}">
                                                    </div>
                                                    <div class="grup-input" style="margin-bottom: 0;">
                                                        <label>Akhir:</label>
                                                        <input type="date" name="akhir_tahap1_2"
                                                            class="input-form"
                                                            value="{{ $periode->akhir_tahap1_2 ?? '' }}">
                                                    </div>
                                                </div>

                                                {{-- Jadwal Tahap 2 --}}
                                                <div class="ist-card-jadwal">
                                                    <strong>Tahap 2.1 (Berkas)</strong>
                                                    <div class="grup-input" style="margin-bottom: 5px;">
                                                        <label>Mulai:</label>
                                                        <input type="date" name="mulai_tahap2_1"
                                                            class="input-form"
                                                            value="{{ $periode->mulai_tahap2_1 ?? '' }}">
                                                    </div>
                                                    <div class="grup-input" style="margin-bottom: 0;">
                                                        <label>Akhir:</label>
                                                        <input type="date" name="akhir_tahap2_1"
                                                            class="input-form"
                                                            value="{{ $periode->akhir_tahap2_1 ?? '' }}">
                                                    </div>
                                                </div>

                                                <div class="ist-card-jadwal">
                                                    <strong>Tahap 2.2 (Penilaian Provinsi)</strong>
                                                    <div class="grup-input" style="margin-bottom: 5px;">
                                                        <label>Mulai:</label>
                                                        <input type="date" name="mulai_tahap2_2"
                                                            class="input-form"
                                                            value="{{ $periode->mulai_tahap2_2 ?? '' }}">
                                                    </div>
                                                    <div class="grup-input" style="margin-bottom: 0;">
                                                        <label>Akhir:</label>
                                                        <input type="date" name="akhir_tahap2_2"
                                                            class="input-form"
                                                            value="{{ $periode->akhir_tahap2_2 ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tanggal Pengumuman Akhir --}}
                                            <div class="ist-card-jadwal highlight">
                                                <strong>Pengumuman Akhir</strong>
                                                <div class="grup-input" style="margin-bottom: 0;">
                                                    <label>Tanggal Pengumuman:</label>
                                                    <input type="date" name="tanggal_pengumuman"
                                                        class="input-form"
                                                        value="{{ $periode->tanggal_pengumuman ?? '' }}">
                                                </div>
                                            </div>
                                        </div> {{-- End Modal Body --}}

                                        <div class="modal-footer">
                                            {{-- Menggunakan class .btn-simpan bawaan template --}}
                                            <button type="submit" class="btn-simpan"><i
                                                    class="fa-solid fa-floppy-disk"></i>
                                                Simpan Pengaturan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- TIMELINE PROGRESS (TIDAK BERUBAH) --}}
                    <div class="ist-timeline-wrapper">
                        <ul class="ist-timeline">
                            <li
                                class="ist-step {{ $activeStep >= 1 ? 'completed' : '' }} {{ $activeStep == 1 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-flag"></i></div>
                                <div class="ist-step-text">Persiapan</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 2 ? 'completed' : '' }} {{ $activeStep == 2 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-users"></i></div>
                                <div class="ist-step-text">Pemilihan dan Penilaian<br>Satker</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap1_1 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap1_1 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 3 ? 'completed' : '' }} {{ $activeStep == 3 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-user-check"></i></div>
                                <div class="ist-step-text">Penetapan<br>Satker</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap1_2 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap1_2 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 4 ? 'completed' : '' }} {{ $activeStep == 4 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-arrows-rotate"></i></div>
                                <div class="ist-step-text">Pengumuman IST<br>Setiap Satker</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 5 ? 'completed' : '' }} {{ $activeStep == 5 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-folder-open"></i></div>
                                <div class="ist-step-text">Seleksi Berkas<br>Provinsi</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap2_1 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap2_1 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 6 ? 'completed' : '' }} {{ $activeStep == 6 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-clipboard-check"></i></div>
                                <div class="ist-step-text">Penilaian dan Penetapan<br>Perwakilan Provinsi</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap2_2 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap2_2 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 7 ? 'completed' : '' }} {{ $activeStep == 7 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-trophy"></i></div>
                                <div class="ist-step-text">Pengumuman<br>Akhir</div>
                                <div class="ist-step-tooltip">
                                    {{ formatTanggal($periode->tanggal_pengumuman ?? null) }}</div>
                            </li>
                        </ul>
                    </div>

                    {{-- ==================================================================== --}}
                    {{-- KONTEN TAHAP 0: TIDAK ADA PERIODE AKTIF --}}
                    {{-- ==================================================================== --}}
                    @if ($activeStep == 0)
                        <div class="ist-info-box"
                            style="text-align: center; padding: 50px 20px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; margin-top: 20px;">
                            <i class="fa-solid fa-calendar-xmark"
                                style="font-size: 60px; color: #94a3b8; margin-bottom: 20px;"></i>
                            <h3 style="color: #475569; font-size: 24px; margin-bottom: 10px;">Belum Ada Pemilihan</h3>
                            <p style="color: #64748b; font-size: 15px; max-width: 600px; margin: 0 auto;">
                                Saat ini tidak ada periode Insan Statistik Teladan (IST) yang berstatus aktif untuk
                                tahun {{ $tahun }}. Silakan tunggu informasi lebih lanjut dari panitia atau
                                hubungi Admin jika terdapat kekeliruan.
                            </p>
                        </div>
                    @endif

                    @if ($activeStep == 1)
                        {{-- KONTEN TAHAP PERSIAPAN --}}
                        <div class="ist-info-box" style="margin-top: 20px;">
                            <div class="ist-info-header">
                                <h3><i class="fa-solid fa-bullhorn"></i> Informasi Persiapan IST</h3>
                            </div>
                            <div class="ist-info-body">
                                @if ($periode && $periode->informasi_persiapan)
                                    <p>{!! nl2br(e($periode->informasi_persiapan)) !!}</p>
                                @else
                                    <p><em>Belum ada informasi persiapan yang dipublikasikan oleh Panitia/Admin.</em>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- KONTEN TAHAP 1_1: PEMILIHAN PEGAWAI --}}
                    @if ($activeStep == 2 && $periode && $periode->is_active)

                        {{-- Jembatan PHP ke JS untuk memori Edit Nilai --}}
                        <script>
                            window.istInitialPenilaian = {};
                            @if ($sudahMenilai && isset($pilihanUser))
                                @foreach ($pilihanUser as $p)
                                    window.istInitialPenilaian["{{ $p->nip }}"] = {
                                        total: {{ $p->skor_kuesioner }},
                                        answers: {!! is_string($p->detail_jawaban) ? $p->detail_jawaban : json_encode($p->detail_jawaban) !!}
                                    };
                                @endforeach
                            @endif
                        </script>

                        {{-- WADAH HASIL (JIKA SUDAH FINALISASI) --}}
                        <div id="wadah-hasil" style="{{ $sudahMenilai ? 'display:block;' : 'display:none;' }}">
                            <div class="ist-info-box" style="text-align: center; margin-top: 20px;">
                                <h3 style="color: #10b981; margin-bottom: 15px;"><i
                                        class="fa-solid fa-circle-check"></i> Penilaian Selesai</h3>
                                <p style="color: #475569;">Anda telah menyelesaikan penilaian kuesioner untuk 3
                                    kandidat unggulan.</p>

                                <div class="ist-grid-cards" style="margin-top: 25px;">
                                    @foreach ($pilihanUser ?? [] as $pilihan)
                                        <div class="ist-card-pilihan" style="border-top: 4px solid #f79039;">
                                            <img src="{{ $pilihan->url_foto ?? asset('assets/img/default-avatar.png') }}"
                                                class="foto-bulat">
                                            <h4 style="margin-top: 10px;">{{ $pilihan->nama }}</h4>
                                            <div style="font-size: 11px; color:#64748b; margin-bottom: 10px;">NIP.
                                                {{ $pilihan->nip }}</div>
                                            <span class="badge-skor" style="font-size: 14px; padding: 6px 15px;">Skor
                                                Akhir: {{ $pilihan->skor_kuesioner }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn-tambah-tim" style="margin-top: 30px;"
                                    onclick="editPenilaianUlang()">
                                    <i class="fa-solid fa-pen-to-square"></i> Ubah Nilai / Edit Penilaian
                                </button>
                            </div>
                        </div>

                        {{-- WADAH FORM TABEL & PENILAIAN LIVE --}}
                        <div id="wadah-form"
                            style="{{ $sudahMenilai ? 'display:none;' : 'display:block;' }} margin-top: 20px;">
                            <div class="ist-info-box">
                                <div class="ist-info-header">
                                    <h3><i class="fa-solid fa-users-viewfinder"></i> Penilaian Kandidat Satker</h3>
                                </div>

                                <div class="ist-info-body">

                                    <p style="margin-bottom: 12px; font-weight: 600; color: #374151;">Silakan ikuti
                                        langkah-langkah berikut untuk memberikan penilaian:</p>
                                    <ol style="margin-left: 20px; color: #4b5563; line-height: 1.7; font-size: 13px;">
                                        <li>Pilih <strong>3 (tiga) orang pegawai</strong> calon Insan Statistik Teladan
                                            pilihan Anda dari tabel di bawah ini.</li>
                                        <li>Klik tombol <span
                                                style="background-color: #f79039; color: #fff; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;"><i
                                                    class="fa-solid fa-star"></i> Menilai</span> pada kolom aksi di
                                            baris pegawai yang Anda pilih.</li>
                                        <li>Isi skor kuesioner pada jendela <em>modal</em> yang muncul. <em>Live
                                                Score</em> akan otomatis menjumlahkan nilai yang Anda berikan.</li>
                                        <li>Setelah Anda selesai memberikan nilai kepada tepat 3 orang pegawai, kotak
                                            <strong>Finalisasi Penilaian</strong> akan otomatis muncul di atas tabel.
                                        </li>
                                        <li>Klik tombol <strong>"Finalisasi & Kirim Penilaian"</strong> untuk
                                            mengirimkan suara Anda ke sistem.</li>
                                    </ol>

                                    {{-- TOMBOL INFORMASI PERSYARATAN --}}
                                    <div style="text-align: right; margin-bottom: 15px;">
                                        <button type="button" class="ist-btn-outline-small"
                                            id="btn-info-persyaratan"
                                            style="border-color: #f79039; color: #000000; padding: 8px 15px;">
                                            <i class="fa-solid fa-list-check"></i> Persyaratan IST
                                        </button>
                                    </div>
                                    {{-- MODAL PERSYARATAN IST --}}
                                    <div id="modal-persyaratan" class="modal-overlay">
                                        <div class="modal-content" style="max-width: 550px;">
                                            <div class="modal-header" style="background-color: #f79039;">
                                                <h3><i class="fa-solid fa-list-check"></i> Persyaratan Administratif
                                                    IST</h3>
                                                <span class="tutup-modal" id="tutup-modal-persyaratan">&times;</span>
                                            </div>
                                            <div class="modal-body" style="text-align: left; padding: 25px;">
                                                <p
                                                    style="margin-bottom: 15px; font-weight: 600; color: #374151; font-size: 14px;">
                                                    Persyaratan administratif peserta pemilihan Insan Statistik Teladan
                                                    (IST) adalah:
                                                </p>
                                                <ol type="a"
                                                    style="margin-left: 20px; color: #4b5563; line-height: 1.8; font-size: 13.5px;">
                                                    <li>Pejabat Fungsional Jenjang Muda ke bawah, Pejabat Pengawas dan
                                                        Pelaksana;</li>
                                                    <li>Masa Kerja minimal 5 (lima) tahun dari sejak diangkat sebagai
                                                        CPNS;</li>
                                                    <li>Tidak sedang/pernah dijatuhi hukuman disiplin ringan dan sedang
                                                        selama 2 (dua)
                                                        tahun terakhir;</li>
                                                    <li>Tidak sedang/pernah dijatuhi hukuman disiplin tingkat berat
                                                        selama bekerja;</li>
                                                </ol>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-simpan" id="btn-tutup-persyaratan"
                                                    style="background-color: #f79039;">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    {{-- PANEL FINALISASI --}}
                                    <div id="panel-finalisasi"
                                        style="display:none; background: #ecfdf5; border: 2px dashed #10b981; padding: 25px; border-radius: 12px; text-align: center; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.1);">
                                        <h3 style="color: #059669; margin-bottom: 10px;"><i
                                                class="fa-solid fa-party-horn"></i> Terima Kasih!</h3>
                                        <p style="color: #047857; margin-bottom: 20px; font-size: 14px;">Anda telah
                                            selesai menilai 3 kandidat. Silakan periksa kembali rincian skor di tabel,
                                            lalu klik tombol di bawah untuk mengirim data.</p>
                                        <button type="button" class="btn-simpan"
                                            style="background: #10b981; font-size: 16px; padding: 12px 35px; border-radius: 30px;"
                                            onclick="submitFinalisasi()">
                                            <i class="fa-solid fa-paper-plane"></i> Finalisasi & Kirim Penilaian
                                        </button>
                                    </div>

                                    {{-- KOTAK FILTER / PENCARIAN --}}
                                    <div style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                                        <div style="position: relative; width: 100%; max-width: 300px;">
                                            <i class="fa-solid fa-magnifying-glass"
                                                style="position: absolute; left: 15px; top: 12px; color: #94a3b8;"></i>
                                            <input type="text" id="input-cari-kandidat" onkeyup="istFilterTable()"
                                                placeholder="Cari nama atau jabatan..." class="input-form"
                                                style="padding-left: 40px; border-radius: 30px;">
                                        </div>
                                    </div>

                                    {{-- TABEL UTAMA (DENGAN KOLOM GOLONGAN & PENDIDIKAN TERPISAH) --}}
                                    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <table class="ist-table" id="tabel-kandidat">
                                            <thead>
                                                <tr>
                                                    <th width="12%" style="text-align: center;">Aksi Penilaian</th>
                                                    <th width="10%" style="text-align: center; cursor: pointer;"
                                                        onclick="istSortTable(1)">Live Score <i
                                                            class="fa-solid fa-sort" style="opacity: 0.3;"></i></th>
                                                    <th width="25%" style="cursor: pointer;"
                                                        onclick="istSortTable(2)">Profil Kandidat <i
                                                            class="fa-solid fa-sort" style="opacity: 0.3;"></i></th>
                                                    <th width="10%" style="cursor: pointer;"
                                                        onclick="istSortTable(3)">Golongan <i class="fa-solid fa-sort"
                                                            style="opacity: 0.3;"></i></th>
                                                    <th width="13%" style="cursor: pointer;"
                                                        onclick="istSortTable(4)">Pendidikan <i
                                                            class="fa-solid fa-sort" style="opacity: 0.3;"></i></th>
                                                    <th width="15%" style="cursor: pointer;"
                                                        onclick="istSortTable(5)">Jabatan <i class="fa-solid fa-sort"
                                                            style="opacity: 0.3;"></i></th>
                                                    <th width="15%" style="cursor: pointer;"
                                                        onclick="istSortTable(6)">Masa Kerja <i
                                                            class="fa-solid fa-sort" style="opacity: 0.3;"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-kandidat">
                                                @forelse($kandidatSatker as $k)
                                                    <tr data-nip="{{ $k->nip }}"
                                                        data-nama="{{ $k->nama }}" data-assessed="0">
                                                        <td class="aksi-area" style="text-align: center;">
                                                            <button type="button" class="btn-menilai"
                                                                onclick="bukaModalPenilaian('{{ $k->nip }}', '{{ $k->nama }}')">
                                                                <i class="fa-solid fa-star"></i> Menilai
                                                            </button>
                                                        </td>

                                                        <td class="score-area" data-sort="0"
                                                            style="text-align: center; font-weight: bold; color: #94a3b8; font-size: 15px;">
                                                            -</td>

                                                        {{-- Kolom 2: Profil Kandidat --}}
                                                        <td data-sort="{{ $k->nama }}">
                                                            <div
                                                                style="display: flex; align-items: center; gap: 12px;">
                                                                @if (!empty($k->url_foto))
                                                                    <img src="{{ $k->url_foto }}"
                                                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                                                                        onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #f79039; font-size: 40px; vertical-align: middle;\'></i>'">
                                                                @else
                                                                    <i class="fa-solid fa-circle-user"
                                                                        style="color: #f79039; font-size: 40px; vertical-align: middle;"></i>
                                                                @endif
                                                                <div>
                                                                    <strong
                                                                        style="color: #1e293b; font-size: 13px;">{{ $k->nama }}</strong>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        {{-- Kolom 3: Golongan --}}
                                                        <td data-sort="{{ $k->gol_akhir }}">
                                                            <span
                                                                style="font-size: 12px; font-weight: bold; color: #f79039;">{{ $k->gol_akhir }}</span>
                                                        </td>

                                                        {{-- Kolom 4: Pendidikan Terakhir --}}
                                                        <td data-sort="{{ $k->pend_sk }}">
                                                            <span
                                                                style="font-size: 12px; color: #475569;">{{ $k->pend_sk }}</span>
                                                        </td>

                                                        {{-- Kolom 5: Jabatan --}}
                                                        <td data-sort="{{ $k->jabatan }}">
                                                            <span
                                                                style="font-size: 12px; color: #475569;">{{ $k->jabatan }}</span>
                                                        </td>

                                                        {{-- Kolom 6: Masa Kerja --}}
                                                        @php $totalBulan = ($k->mks_thn * 12) + $k->mks_bln; @endphp
                                                        <td data-sort="{{ $totalBulan }}"
                                                            style="font-size: 12px; color: #475569;">
                                                            {{ $k->mks_thn }} Thn, {{ $k->mks_bln }} Bln
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" style="text-align: center; padding: 20px;">
                                                            Tidak ada data kandidat.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- MODAL KUESIONER & LIVE SCORE --}}
                            <div id="modalPenilaian" class="modal-overlay">
                                <div class="modal-content modal-xl">
                                    <div class="modal-header" style="background: var(--warna-utama);">
                                        <h3 style="color:#fff;"><i class="fa-solid fa-clipboard-user"></i> Menilai:
                                            <span id="modal-nama-kandidat"></span>
                                        </h3>
                                        <span class="tutup-modal" style="color:#fff;"
                                            onclick="tutupModalPenilaian()">&times;</span>
                                    </div>

                                    <div class="modal-body" style="background: #f8fafc; position: relative;">

                                        {{-- Box Live Score Mengambang (Sticky) --}}
                                        <div class="live-score-box">
                                            <small>Total Skor</small>
                                            <div class="angka-skor" id="live-score">0</div>
                                        </div>

                                        <div class="ist-kuesioner-grid" style="padding-top: 5px;">
                                            @foreach ($pertanyaanKuesioner as $index => $q)
                                                <div class="ist-question-card">
                                                    <p class="ist-q-text"><strong>{{ $index + 1 }}.</strong>
                                                        {{ $q->pertanyaan }}</p>
                                                    <div class="ist-radio-group">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <label class="ist-radio-label">
                                                                <input type="radio" class="radio-nilai"
                                                                    name="temp_q_{{ $q->id }}"
                                                                    data-qid="{{ $q->id }}"
                                                                    value="{{ $i }}">
                                                                <span>{{ $i }}</span>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-simpan" onclick="simpanNilaiModal()"><i
                                                class="fa-solid fa-check"></i> Simpan Penilaian Sementara</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Hidden (Menampung Data JSON untuk dikirim ke Backend) --}}
                            <form action="{{ route('ist.storePenilaian') }}" method="POST"
                                id="form-penilaian-final" style="display: none;">
                                @csrf
                                {{-- Tambahkan dua baris ini agar terbaca oleh backend --}}
                                <input type="hidden" name="periode_id" value="{{ $periode->id ?? '' }}">
                                <input type="hidden" name="nip_pemilih"
                                    value="{{ $data['user_active']->nip_pegawai ?? '' }}">
                            </form>
                        </div>
                        @if ($isPejabat)
                            <div class="ist-info-box" style="margin-top: 25px;">
                                <div class="ist-info-header">
                                    <h3><i class="fa-solid fa-ranking-star"></i> Rekapitulasi Penilaian dan Penetapan
                                        Perwakilan Satker
                                    </h3>
                                </div>
                                    {{-- Tombol Template SK --}}
                                    <div style="text-align: right; margin-bottom: 12px;">
                                        <a href="https://docs.google.com/document/d/185XHLeBfRZH5xia61ADolSLtKZRpQFOS/edit?usp=sharing&ouid=113779814476523304511&rtpof=true&sd=true"
                                            target="_blank"
                                            style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg,#4285f4,#1a73e8); color:#fff; padding: 9px 18px; border-radius: 30px; font-size: 12px; font-weight: 700; text-decoration: none; box-shadow: 0 3px 10px rgba(66,133,244,0.4); transition: all 0.2s;">
                                            <i class="fa-solid fa-file-word"></i> Unduh Template SK Penetapan
                                        </a>
                                    </div>

                                    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <table class="ist-table">
                                            <thead>
                                                <tr>
                                                    <th width="8%" style="text-align: center;">Rank</th>
                                                    <th width="35%">Profil Kandidat</th>
                                                    <th width="20%" style="text-align: center;">Total Pemilih</th>
                                                    <th width="20%" style="text-align: center;">Akumulasi Skor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($rekapVoting as $index => $rv)
                                                    <tr style="{{ $index === 0 ? 'background: #fffbeb;' : ($index < 3 ? 'background: #f8fafc;' : '') }}">
                                                        <td style="text-align: center; font-weight: 900; font-size: 18px; color: {{ $index === 0 ? '#fbbf24' : ($index === 1 ? '#94a3b8' : ($index === 2 ? '#b45309' : '#cbd5e1')) }};">
                                                            #{{ $index + 1 }}
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                                @if (!empty($rv->url_foto))
                                                                    <img src="{{ $rv->url_foto }}"
                                                                        style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid {{ $index === 0 ? '#fbbf24' : '#e2e8f0' }};"
                                                                        onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #94a3b8; font-size: 45px; margin-right: 10px;\'></i>'">
                                                                @else
                                                                    <i class="fa-solid fa-circle-user" style="color: #94a3b8; font-size: 45px; margin-right: 10px;"></i>
                                                                @endif
                                                                <div>
                                                                    <strong style="color: #1e293b; font-size: 13px;">{{ $rv->nama }}</strong><br>
                                                                    <small style="color: #64748b;">{{ $rv->jabatan }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 12px;">
                                                                <i class="fa-solid fa-users"></i> {{ $rv->total_pemilih }} Suara
                                                            </span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span style="color: var(--warna-utama); font-weight: 900; font-size: 18px;">{{ $rv->total_skor }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" style="text-align: center; padding: 20px;">
                                                            Belum ada satupun pegawai yang melakukan penilaian di Satker ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        @endif
                    @endif

                    {{-- ==================================================================== --}}
                    {{-- KONTEN TAHAP 1.2: PENETAPAN KANDIDAT SATKER --}}
                    {{-- ==================================================================== --}}
                    @if ($activeStep == 3 && $periode && $periode->is_active)

                        {{-- BLOK 2: KLASEMEN REKAPITULASI & PENETAPAN (KHUSUS PEJABAT/PANITIA) --}}
                        @if ($isPejabat)
                            <div class="ist-info-box" style="margin-top: 25px;">
                                <div class="ist-info-header">
                                    <h3><i class="fa-solid fa-ranking-star"></i> Rekapitulasi Penilaian dan Penetapan
                                        Perwakilan Satker
                                    </h3>
                                </div>

                                @if ($kandidatTerpilih)
                                    {{-- JIKA SUDAH ADA YANG DITETAPKAN (DENGAN ANIMASI MERIAH) --}}
                                    <div class="ist-winner-card">

                                        {{-- Dekorasi Animasi Bintang --}}
                                        <i class="fa-solid fa-star ist-star-anim star-1"></i>
                                        <i class="fa-solid fa-star ist-star-anim star-2"></i>
                                        <i class="fa-solid fa-star ist-star-anim star-3"></i>
                                        <i class="fa-solid fa-star ist-star-anim star-4"></i>
                                        <i class="fa-solid fa-sparkles ist-star-anim star-5"></i>

                                        {{-- Tombol Batalkan Penetapan --}}
                                        <div style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                                            <form action="{{ route('ist.batalkan') }}" method="POST" class="form-batalkan">
                                                @csrf
                                                <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                <input type="hidden" name="nip_kandidat" value="{{ $kandidatTerpilih->nip_kandidat }}">
                                                <button type="button" class="btn-hapus-nilai btn-batalkan"
                                                    style="font-size: 12px; padding: 8px 15px; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px);">
                                                    <i class="fa-solid fa-xmark"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>

                                        <h4 class="ist-winner-title">
                                            <i class="fa-solid fa-medal fa-bounce"></i> Perwakilan Satker Telah Ditetapkan!
                                        </h4>

                                        <div class="ist-winner-photo-container">
                                            @if (!empty($kandidatTerpilih->url_foto))
                                                <img src="{{ $kandidatTerpilih->url_foto }}" class="ist-winner-photo"
                                                    onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user ist-winner-photo-fallback\'></i>'">
                                            @else
                                                <i class="fa-solid fa-circle-user ist-winner-photo-fallback"></i>
                                            @endif
                                        </div>

                                        <h3 class="ist-winner-name">{{ $kandidatTerpilih->nama }}</h3>

                                        <p style="color: #475569; font-size: 14px; margin-top: 8px; margin-bottom: 5px; position: relative; z-index: 5;">
                                            {{ $kandidatTerpilih->gol_akhir }} &bull; {{ $kandidatTerpilih->jabatan }}
                                        </p>

                                        <div class="ist-winner-badge">
                                            <i class="fa-solid fa-building-user"></i>
                                            BPS {{ $kandidatTerpilih->wilayah ?? 'Satuan Kerja' }}
                                        </div>

                                        <div style="margin-top: 30px; position: relative; z-index: 5;">
                                            <span class="ist-winner-score">
                                                <i class="fa-solid fa-trophy" style="color: #fbbf24; margin-right: 5px;"></i> Skor Akumulasi:
                                                {{ $kandidatTerpilih->total_skor }} Poin
                                            </span>
                                        </div>

                                        {{-- ============================================================ --}}
                                        {{-- BLOK SK PENETAPAN (Winner Card) --}}
                                        {{-- ============================================================ --}}
                                        <div style="margin-top: 25px; padding: 20px; background: rgba(255,255,255,0.85); border-radius: 14px; border: 1px solid rgba(255,255,255,0.9); backdrop-filter: blur(6px); position: relative; z-index: 5; text-align: left;">

                                            @if ($kandidatTerpilih->validasi_sk === 'tervalidasi')
                                                {{-- ✅ SK SUDAH TERVALIDASI --}}
                                                <div style="text-align: center;">
                                                    <span style="display: inline-flex; align-items: center; gap: 8px; background: #dcfce7; color: #16a34a; padding: 10px 22px; border-radius: 30px; font-size: 13px; font-weight: 700; border: 1px solid #86efac;">
                                                        <i class="fa-solid fa-circle-check"></i> SK Penetapan Tervalidasi
                                                    </span>
                                                    <br><br>
                                                    <a href="{{ $kandidatTerpilih->link_sk }}" target="_blank"
                                                        style="display: inline-flex; align-items: center; gap: 7px; background: linear-gradient(135deg,#4285f4,#1a73e8); color:#fff; padding: 9px 20px; border-radius: 30px; font-size: 12px; font-weight: 700; text-decoration: none; box-shadow: 0 3px 10px rgba(66,133,244,0.35);">
                                                        <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen SK
                                                    </a>
                                                </div>

                                            @elseif ($kandidatTerpilih->validasi_sk === 'ditolak')
                                                {{-- ❌ SK DITOLAK --}}
                                                <div style="text-align: center; margin-bottom: 15px;">
                                                    <span style="display: inline-flex; align-items: center; gap: 8px; background: #fee2e2; color: #dc2626; padding: 10px 22px; border-radius: 30px; font-size: 13px; font-weight: 700; border: 1px solid #fca5a5;">
                                                        <i class="fa-solid fa-circle-xmark"></i> Dokumen SK Ditolak Panitia
                                                    </span>
                                                    <p style="font-size: 12px; color: #dc2626; margin-top: 8px; font-weight: 500;">
                                                        Mohon perbaiki dokumen dan upload ulang link yang benar.
                                                    </p>
                                                </div>

                                                <form action="{{ route('ist.uploadSk') }}" method="POST" style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                                                    @csrf
                                                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                    <input type="hidden" name="nip_kandidat" value="{{ $kandidatTerpilih->nip_kandidat }}">
                                                    <input type="url" name="link_sk" class="input-form" placeholder="https://drive.google.com/..." required
                                                        style="flex: 1; min-width: 200px; font-size: 12px; border-color:#fca5a5;" value="{{ $kandidatTerpilih->link_sk }}">
                                                    <button type="submit" style="background: #f79039; color:#fff; border:none; padding: 10px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                                                        <i class="fa-solid fa-upload"></i> Upload Ulang
                                                    </button>
                                                </form>

                                            @elseif (!empty($kandidatTerpilih->link_sk))
                                                {{-- ⏳ SK SUDAH DIUPLOAD, MENUNGGU VALIDASI --}}
                                                <div style="text-align: center; margin-bottom: 15px;">
                                                    <span style="display: inline-flex; align-items: center; gap: 8px; background: #fefce8; color: #ca8a04; padding: 10px 22px; border-radius: 30px; font-size: 13px; font-weight: 700; border: 1px solid #fde68a;">
                                                        <i class="fa-solid fa-clock"></i> Menunggu Validasi Panitia
                                                    </span>
                                                    <br><br>
                                                    <a href="{{ $kandidatTerpilih->link_sk }}" target="_blank"
                                                        style="display: inline-flex; align-items: center; gap: 7px; background: #f1f5f9; color: #334155; padding: 9px 20px; border-radius: 30px; font-size: 12px; font-weight: 700; text-decoration: none; border: 1px solid #cbd5e1;">
                                                        <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen SK
                                                    </a>
                                                </div>

                                                {{-- Form ganti/upload ulang SK --}}
                                                <details style="margin-top: 15px;">
                                                    <summary style="cursor: pointer; font-size: 12px; color: #64748b; font-weight: 600;"><i class="fa-solid fa-pen"></i> Ganti Link SK</summary>
                                                    <form action="{{ route('ist.uploadSk') }}" method="POST" style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                                                        @csrf
                                                        <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                        <input type="hidden" name="nip_kandidat" value="{{ $kandidatTerpilih->nip_kandidat }}">
                                                        <input type="url" name="link_sk" class="input-form" placeholder="https://drive.google.com/..." required
                                                            style="flex: 1; min-width: 200px; font-size: 12px;" value="{{ $kandidatTerpilih->link_sk }}">
                                                        <button type="submit" style="background: #f79039; color:#fff; border:none; padding: 10px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap;">
                                                            <i class="fa-solid fa-upload"></i> Simpan
                                                        </button>
                                                    </form>
                                                </details>

                                            @else
                                                {{-- 📂 SK BELUM DIUPLOAD --}}
                                                <h5 style="color: #1e293b; font-size: 14px; font-weight: 700; margin-bottom: 12px;">
                                                    <i class="fa-solid fa-file-signature"></i> Upload SK Penetapan
                                                </h5>
                                                <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;">
                                                    Silakan buat SK Penetapan menggunakan template di bawah, lalu upload dalam format PDF ke Google Drive dan masukkan linknya.
                                                </p>
                                                <div style="text-align: center; margin-bottom: 15px;">
                                                    <a href="https://docs.google.com/document/d/185XHLeBfRZH5xia61ADolSLtKZRpQFOS/edit?usp=sharing&ouid=113779814476523304511&rtpof=true&sd=true"
                                                        target="_blank"
                                                        style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg,#4285f4,#1a73e8); color:#fff; padding: 10px 20px; border-radius: 30px; font-size: 12px; font-weight: 700; text-decoration: none; box-shadow: 0 3px 10px rgba(66,133,244,0.4);">
                                                        <i class="fa-solid fa-file-word"></i> Download Template SK
                                                    </a>
                                                </div>
                                                <form action="{{ route('ist.uploadSk') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                    <input type="hidden" name="nip_kandidat" value="{{ $kandidatTerpilih->nip_kandidat }}">
                                                    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                                                        <input type="url" name="link_sk" class="input-form"
                                                            placeholder="Tempel link Google Drive PDF SK di sini..."
                                                            required style="flex: 1; min-width: 200px; font-size: 12px;">
                                                        <button type="submit"
                                                            style="background: linear-gradient(135deg,#f79039,#e8600a); color:#fff; border:none; padding: 12px 22px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 3px 10px rgba(247,144,57,0.4); white-space: nowrap;">
                                                            <i class="fa-solid fa-upload"></i> Upload Link SK
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                        {{-- END BLOK SK PENETAPAN --}}

                                    </div>
                                @else
                                    {{-- JIKA BELUM DITETAPKAN, TAMPILKAN TABEL KLASEMEN --}}

                                    {{-- Tombol Template SK --}}
                                    <div style="text-align: right; margin-bottom: 12px;">
                                        <a href="https://docs.google.com/document/d/185XHLeBfRZH5xia61ADolSLtKZRpQFOS/edit?usp=sharing&ouid=113779814476523304511&rtpof=true&sd=true"
                                            target="_blank"
                                            style="display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg,#4285f4,#1a73e8); color:#fff; padding: 9px 18px; border-radius: 30px; font-size: 12px; font-weight: 700; text-decoration: none; box-shadow: 0 3px 10px rgba(66,133,244,0.4); transition: all 0.2s;">
                                            <i class="fa-solid fa-file-word"></i> Unduh Template SK Penetapan
                                        </a>
                                    </div>

                                    <div style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0;">
                                        <table class="ist-table">
                                            <thead>
                                                <tr>
                                                    <th width="8%" style="text-align: center;">Rank</th>
                                                    <th width="35%">Profil Kandidat</th>
                                                    <th width="20%" style="text-align: center;">Total Pemilih</th>
                                                    <th width="20%" style="text-align: center;">Akumulasi Skor</th>
                                                    <th width="17%" style="text-align: center;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($rekapVoting as $index => $rv)
                                                    <tr style="{{ $index === 0 ? 'background: #fffbeb;' : ($index < 3 ? 'background: #f8fafc;' : '') }}">
                                                        <td style="text-align: center; font-weight: 900; font-size: 18px; color: {{ $index === 0 ? '#fbbf24' : ($index === 1 ? '#94a3b8' : ($index === 2 ? '#b45309' : '#cbd5e1')) }};">
                                                            #{{ $index + 1 }}
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                                @if (!empty($rv->url_foto))
                                                                    <img src="{{ $rv->url_foto }}"
                                                                        style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid {{ $index === 0 ? '#fbbf24' : '#e2e8f0' }};"
                                                                        onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #94a3b8; font-size: 45px; margin-right: 10px;\'></i>'">
                                                                @else
                                                                    <i class="fa-solid fa-circle-user" style="color: #94a3b8; font-size: 45px; margin-right: 10px;"></i>
                                                                @endif
                                                                <div>
                                                                    <strong style="color: #1e293b; font-size: 13px;">{{ $rv->nama }}</strong><br>
                                                                    <small style="color: #64748b;">{{ $rv->jabatan }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 12px;">
                                                                <i class="fa-solid fa-users"></i> {{ $rv->total_pemilih }} Suara
                                                            </span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span style="color: var(--warna-utama); font-weight: 900; font-size: 18px;">{{ $rv->total_skor }}</span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{-- Tombol Tetapkan HANYA untuk 3 besar (index 0,1,2) --}}
                                                            @if ($index < 3)
                                                                <form action="{{ route('ist.tetapkan') }}" method="POST" class="form-tetapkan">
                                                                    @csrf
                                                                    <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                                    <input type="hidden" name="nip_kandidat" value="{{ $rv->nip }}">
                                                                    <button type="button" class="btn-simpan btn-tetapkan"
                                                                        style="padding: 8px 15px; font-size: 12px; border-radius: 30px;">
                                                                        <i class="fa-solid fa-check-to-slot"></i> Tetapkan
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span style="color: #cbd5e1; font-size: 12px;"><i class="fa-solid fa-minus"></i></span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" style="text-align: center; padding: 20px;">
                                                            Belum ada satupun pegawai yang melakukan penilaian di Satker ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- BLOK 1: TAMPILAN PILIHAN USER (READ-ONLY UNTUK SEMUA ROLE) --}}
                        <div class="ist-info-box" style="text-align: center; margin-top: 20px;">
                            <h3 style="color: #64748b; margin-bottom: 15px;"><i class="fa-solid fa-lock"></i>
                                Pemilihan dan Penilaian Satker Telah Ditutup</h3>
                            @if ($sudahMenilai)
                                <p style="color: #475569;">Berikut adalah 3 kandidat unggulan yang telah Anda berikan
                                    nilai pada tahap sebelumnya.</p>
                                <div class="ist-grid-cards" style="margin-top: 25px;">
                                    @foreach ($pilihanUser ?? [] as $pilihan)
                                        <div class="ist-card-pilihan"
                                            style="border-top: 4px solid #94a3b8; filter: grayscale(15%);">
                                            @if (!empty($pilihan->url_foto))
                                                <img src="{{ $pilihan->url_foto }}" class="foto-bulat"
                                                    onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #94a3b8; font-size: 85px; margin-bottom: 10px;\'></i>'">
                                            @else
                                                <i class="fa-solid fa-circle-user"
                                                    style="color: #94a3b8; font-size: 85px; margin-bottom: 10px;"></i>
                                            @endif
                                            <h4 style="margin-top: 10px;">{{ $pilihan->nama }}</h4>
                                            <div style="font-size: 11px; color:#64748b; margin-bottom: 10px;">
                                                {{ $pilihan->gol_akhir }} &bull; {{ $pilihan->pend_sk }}</div>
                                            <span class="badge-skor"
                                                style="font-size: 14px; padding: 6px 15px; background: #f1f5f9; color: #475569;">Skor
                                                Kuesioner: {{ $pilihan->skor_kuesioner }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                    style="padding: 20px; background: #fff1f2; border: 1px dashed #fecdd3; border-radius: 8px; color: #e11d48; margin-top: 15px; font-weight: bold;">
                                    <i class="fa-solid fa-circle-exclamation"></i> Anda tidak melakukan partisipasi
                                    dalam Pemilihan dan Penilaian Satker
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- ==================================================================== --}}
                    {{-- KONTEN TAHAP 2.1 (STEP 4): PENGUMUMAN & PERSIAPAN --}}
                    {{-- ==================================================================== --}}
                    @if ($activeStep == 4 && $periode && $periode->is_active)

                        {{-- BLOK 1: GALERI PENGUMUMAN PERWAKILAN SATKER --}}
                        <div class="ist-info-box"
                            style="margin-top: 20px; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
                            <div
                                style="text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px dashed #e2e8f0;">
                                <h2 style="color: #16a34a; margin-bottom: 10px; font-size: 24px;">
                                    <i class="fa-solid fa-bullhorn fa-shake" style="color: #f79039;"></i> Pengumuman
                                    Hasil Penilaian Tahap 1
                                </h2>
                                <p style="color: #475569; font-size: 15px; max-width: 700px; margin: 0 auto;">
                                    Selamat kepada para pegawai teladan berikut yang telah resmi ditetapkan sebagai
                                    perwakilan dari masing-masing Satuan Kerja untuk melaju ke Tahap 2 (Tingkat
                                    Provinsi).
                                </p>
                            </div>

                            <div class="ist-grid-cards">
                                @forelse($semuaKandidatTahap2 as $k)
                                    <div class="ist-card-pilihan"
                                        style="border-top: 4px solid #22c55e; position: relative; overflow: hidden; animation: tabFadeIn 0.5s ease;">

                                        {{-- Watermark Ikon Penghargaan di Latar --}}
                                        <div
                                            style="position: absolute; top: -15px; right: -15px; color: #bbf7d0; font-size: 90px; z-index: 0; opacity: 0.3; transform: rotate(15deg);">
                                            <i class="fa-solid fa-award"></i>
                                        </div>

                                        {{-- Profil Kandidat --}}
                                        <div style="position: relative; z-index: 1;">
                                            @if (!empty($k->url_foto))
                                                <img src="{{ $k->url_foto }}" class="foto-bulat"
                                                    style="border-color: #22c55e;"
                                                    onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #22c55e; font-size: 85px; margin-bottom: 10px;\'></i>'">
                                            @else
                                                <i class="fa-solid fa-circle-user"
                                                    style="color: #22c55e; font-size: 85px; margin-bottom: 10px;"></i>
                                            @endif

                                            <h4 style="margin-top: 10px; color: #1e293b; font-size: 15px;">
                                                {{ $k->nama }}</h4>
                                            <div style="font-size: 12px; color:#64748b; margin-bottom: 12px;">
                                                {{ $k->gol_akhir }} &bull; {{ $k->jabatan }}
                                            </div>

                                            {{-- Label Satker --}}
                                            <div
                                                style="display: inline-block; background: #dcfce7; color: #15803d; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid #bbf7d0;">
                                                <i class="fa-solid fa-building-user"></i> {{ $k->wilayah }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; background: #fff; border: 1px dashed #cbd5e1; border-radius: 12px; color: #94a3b8;">
                                        <i class="fa-solid fa-hourglass-empty"
                                            style="font-size: 30px; margin-bottom: 15px;"></i><br>
                                        Belum ada satupun perwakilan Satker yang ditetapkan untuk Tahap 2.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- BLOK 2: INFORMASI & PERSIAPAN TAHAP 2 --}}
                        <div class="ist-info-box"
                            style="margin-top: 25px; border-top: 5px solid #3b82f6; background: #eff6ff; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);">
                            <div class="ist-info-header" style="border-bottom-color: #bfdbfe;">
                                <h3 style="color: #1e3a8a;"><i class="fa-solid fa-circle-info"></i> Informasi &
                                    Persiapan Tahap 2</h3>
                            </div>

                            {{-- Mengambil data dari kolom informasi_persiapan --}}
                            <div style="color: #1e40af; font-size: 14px; line-height: 1.8; white-space: pre-wrap;">
                                {!! $periode->informasi_persiapan ??
                                    'Belum ada informasi atau instruksi persiapan yang disampaikan oleh panitia pada periode ini.' !!}</div>
                        </div>
                    @endif

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

            <!-- Footer -->
            <footer>
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp; Tim Pengolahan dan TI - BPS Provinsi Aceh</p>
            </footer>
        </div>
    </div>

    {{-- Bridge PHP to JS (Sangat Disederhanakan) --}}
    <script>
        window.BupestaConfig = {
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}",
            successMessage: "{{ session('success') }}",
        };
        window.userActiveNip = "{{ $userNip }}";
    </script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-ist/ist.js') }}"></script>

    <script>
    // ============================================================
    //  MONITORING MODAL: SORTING & DOWNLOAD IMAGE
    // ============================================================

    (function () {
        // ------ SORTING ------
        var sortState = {}; // { tableId: { col: n, asc: true } }

        function initTableSort(tableId) {
            var table = document.getElementById(tableId);
            if (!table) return;
            var ths = table.querySelectorAll('thead th[data-sort-col]');
            ths.forEach(function (th) {
                th.addEventListener('click', function () {
                    var col = parseInt(th.getAttribute('data-sort-col'));
                    var type = th.getAttribute('data-sort-type') || 'string';
                    if (!sortState[tableId]) sortState[tableId] = { col: -1, asc: true };
                    var state = sortState[tableId];
                    var asc = (state.col === col) ? !state.asc : true;
                    state.col = col;
                    state.asc = asc;

                    // Reset all icons
                    ths.forEach(function (h) {
                        var icon = h.querySelector('.sort-icon');
                        if (icon) icon.textContent = '⇅';
                        h.style.background = '';
                    });
                    var myIcon = th.querySelector('.sort-icon');
                    if (myIcon) myIcon.textContent = asc ? '↑' : '↓';
                    th.style.background = 'rgba(99,102,241,0.08)';

                    var tbody = table.querySelector('tbody');
                    var rows = Array.from(tbody.querySelectorAll('tr'));
                    rows.sort(function (a, b) {
                        var cellA = a.cells[col];
                        var cellB = b.cells[col];
                        if (!cellA || !cellB) return 0;
                        var valA = (cellA.getAttribute('data-val') || cellA.textContent).trim();
                        var valB = (cellB.getAttribute('data-val') || cellB.textContent).trim();
                        var cmp = 0;
                        if (type === 'number') {
                            cmp = parseFloat(valA) - parseFloat(valB);
                        } else {
                            cmp = valA.localeCompare(valB, 'id');
                        }
                        return asc ? cmp : -cmp;
                    });
                    rows.forEach(function (r) { tbody.appendChild(r); });
                });
            });
        }

        // Init sorting for both tables when DOM ready
        document.addEventListener('DOMContentLoaded', function () {
            initTableSort('tbl-mon-1_1');
            initTableSort('tbl-mon-1_2');
        });

        // Expose so switchMonTab can still update download buttons
        window._monSortInited = true;
    })();

    // ------ DOWNLOAD IMAGE ------
    function downloadMonitoringImage(tabId, filename) {
        // Untuk Tab 1.1: hanya capture tabel Rekapitulasi Partisipasi saja
        var sourceEl;
        if (tabId === 'mon-tab-1_1') {
            sourceEl = document.getElementById('rekap-partisipasi-wrapper');
        } else {
            sourceEl = document.getElementById(tabId);
        }
        if (!sourceEl) { alert('Konten tidak ditemukan.'); return; }

        // Create a styled wrapper for cleaner export
        var wrapper = document.createElement('div');
        wrapper.style.cssText = 'background:#f8fafc; padding:24px; font-family:Poppins,sans-serif; min-width:700px; display:inline-block;';

        // Header branding
        var header = document.createElement('div');
        header.style.cssText = 'text-align:center; margin-bottom:18px; padding-bottom:14px; border-bottom:2px solid #e2e8f0;';
        header.innerHTML = '<div style="font-size:18px; font-weight:700; color:#1e293b;"><span style="color:#f79039;">&#9733;</span> BPS Provinsi Aceh &mdash; Monitoring IST</div>' +
            '<div style="font-size:12px; color:#64748b; margin-top:4px;">Diunduh pada: ' + new Date().toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit'}) + '</div>';
        wrapper.appendChild(header);

        // Clone source element
        var clone = sourceEl.cloneNode(true);
        clone.style.display = 'block';
        wrapper.appendChild(clone);

        document.body.appendChild(wrapper);
        wrapper.style.position = 'fixed';
        wrapper.style.top = '-9999px';
        wrapper.style.left = '-9999px';

        var btn = document.getElementById(
            tabId === 'mon-tab-1_1' ? 'btn-download-mon-1_1' : 'btn-download-mon-1_2'
        );
        var origText = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyiapkan...';

        html2canvas(wrapper, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#f8fafc',
            logging: false
        }).then(function (canvas) {
            document.body.removeChild(wrapper);
            if (btn) btn.innerHTML = origText;
            var link = document.createElement('a');
            link.download = filename + '-' + new Date().toISOString().slice(0,10) + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(function (err) {
            document.body.removeChild(wrapper);
            if (btn) btn.innerHTML = origText;
            console.error(err);
            alert('Gagal mengunduh gambar. Pastikan library html2canvas termuat.');
        });
    }

    // Patch switchMonTab to sync download buttons
    document.addEventListener('DOMContentLoaded', function () {
        var origSwitch = window.switchMonTab;
        window.switchMonTab = function (tabKey) {
            if (typeof origSwitch === 'function') origSwitch(tabKey);
            var btn11 = document.getElementById('btn-download-mon-1_1');
            var btn12 = document.getElementById('btn-download-mon-1_2');
            if (btn11) btn11.style.display = tabKey === '1_1' ? 'inline-flex' : 'none';
            if (btn12) btn12.style.display = tabKey === '1_2' ? 'inline-flex' : 'none';
        };
    });

    // ================================================================
    // POPUP VALIDASI SK
    // ================================================================
    function bukaPopupValidasiSk(kodeWilayah, namaSatker, namaKandidat, linkSk, sudahValidasi, periodeId, nipKandidat) {
        // Isi data ke popup
        document.getElementById('popup-sk-satker').textContent   = namaSatker;
        document.getElementById('popup-sk-nama').textContent      = namaKandidat;
        document.getElementById('popup-sk-link').href             = linkSk;
        document.getElementById('popup-sk-link-text').textContent = 'Buka Dokumen SK (' + linkSk.substring(0, 40) + '...)';
        document.getElementById('popup-sk-periode-id').value      = periodeId;
        document.getElementById('popup-sk-nip').value             = nipKandidat;

        // Render status validasi saat ini & tombol aksi
        var statusBox = document.getElementById('popup-sk-status-box');
        var btnArea = document.getElementById('popup-sk-buttons-area');
        
        if (sudahValidasi === 'tervalidasi') {
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#dcfce7;color:#16a34a;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #86efac;"><i class="fa-solid fa-circle-check"></i> Status Saat Ini: Tervalidasi</span>';
            btnArea.innerHTML = '<button type="button" onclick="konfirmasiValidasiPopup(\'cabut\')" style="flex:1; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(249,115,22,0.4);"><i class="fa-solid fa-rotate-left"></i> Cabut Validasi</button>';
        } else if (sudahValidasi === 'ditolak') {
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#fee2e2;color:#dc2626;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #fca5a5;"><i class="fa-solid fa-circle-xmark"></i> Status Saat Ini: Ditolak</span>';
            btnArea.innerHTML = '<button type="button" onclick="konfirmasiValidasiPopup(\'cabut\')" style="flex:1; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(249,115,22,0.4);"><i class="fa-solid fa-rotate-left"></i> Cabut / Reset Status</button>';
        } else {
            // null / kosong / menunggu
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#fefce8;color:#ca8a04;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #fde68a;"><i class="fa-solid fa-clock"></i> Status Saat Ini: Menunggu Validasi</span>';
            btnArea.innerHTML = 
                '<button type="button" onclick="konfirmasiValidasiPopup(\'validasi\')" style="flex:1; min-width:140px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(16,185,129,0.4);"><i class="fa-solid fa-check-double"></i> Validasi SK</button>' +
                '<button type="button" onclick="konfirmasiValidasiPopup(\'tolak\')" style="flex:1; min-width:140px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(239,68,68,0.4);"><i class="fa-solid fa-xmark"></i> Tolak Dokumen</button>';
        }

        // Tampilkan modal validasi
        var modal = document.getElementById('modalValidasiSk');
        modal.style.display = 'flex';
    }

    function tutupPopupValidasiSk() {
        document.getElementById('modalValidasiSk').style.display = 'none';
    }

    function konfirmasiValidasiPopup(aksi) {
        var teks = '';
        if (aksi === 'validasi') teks = 'Yakin ingin memvalidasi dokumen SK ini?';
        else if (aksi === 'tolak') teks = 'Yakin ingin menolak dokumen SK ini? Kandidat akan diminta upload ulang.';
        else if (aksi === 'cabut') teks = 'Yakin ingin mencabut status validasi dokumen SK ini?';
        
        document.getElementById('teks-konfirmasi-sk').textContent = teks;
        var btnLanjut = document.getElementById('btn-lanjutkan-sk');
        
        // Gunakan fungsi anonim untuk menghindari multiple event listener jika onclick ditambah terus
        btnLanjut.onclick = function() {
            document.getElementById('popup-sk-aksi').value = aksi;
            document.getElementById('form-validasi-sk-popup').submit();
        };
        
        // Tutup sementara modal validasi utama atau biarkan tumpang tindih (Z-index confirm lebih tinggi)
        document.getElementById('modalConfirmSk').style.display = 'flex';
    }

    // Tutup popup jika klik di luar area modal
    document.addEventListener('click', function(e) {
        var modal = document.getElementById('modalValidasiSk');
        if (modal && e.target === modal) tutupPopupValidasiSk();
        
        var modalConfirm = document.getElementById('modalConfirmSk');
        if (modalConfirm && e.target === modalConfirm) modalConfirm.style.display = 'none';
    });
    </script>

</body>

</html>