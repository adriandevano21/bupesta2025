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
                                            <div class="ist-tabs-header"
                                                style="margin-bottom: 25px; border-bottom: 2px solid #cbd5e1;">
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

                                            {{-- ====================================================== --}}
                                            {{-- KONTEN TAB 1: TAHAP 1.1 (YANG LAMA) --}}
                                            {{-- ====================================================== --}}
                                            <div id="mon-tab-1_1"
                                                style="display: block; animation: tabFadeIn 0.4s ease;">
                                                <h4 style="margin-bottom: 15px; color: #1e293b;"><i
                                                        class="fa-solid fa-chart-bar"></i> Rekapitulasi Partisipasi</h4>
                                                <div
                                                    style="overflow-x: auto; margin-bottom: 30px; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                                                    <table class="ist-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Satuan Kerja</th>
                                                                <th style="text-align: center;">Total Pegawai</th>
                                                                <th style="text-align: center; color: #10b981;">Sudah
                                                                    Menilai</th>
                                                                <th style="text-align: center; color: #ef4444;">Belum
                                                                    Menilai</th>
                                                                <th width="25%">Progress Partisipasi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($monitoringData['rekap'] as $satker => $dataMon)
                                                                @php $persen = $dataMon['total'] > 0 ? round(($dataMon['sudah'] / $dataMon['total']) * 100) : 0; @endphp
                                                                <tr>
                                                                    <td style="font-weight: 600; color: #334155;">
                                                                        {{ $satker }}</td>
                                                                    <td style="text-align: center; font-weight: bold;">
                                                                        {{ $dataMon['total'] }}</td>
                                                                    <td
                                                                        style="text-align: center; font-weight: bold; color: #10b981;">
                                                                        {{ $dataMon['sudah'] }}</td>
                                                                    <td
                                                                        style="text-align: center; font-weight: bold; color: #ef4444;">
                                                                        {{ $dataMon['belum'] }}</td>
                                                                    <td>
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
                                                <div
                                                    style="overflow-x: auto; border-radius: 8px; border: 1px solid #e2e8f0; background: #fff;">
                                                    <table class="ist-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Satuan Kerja</th>
                                                                <th style="text-align: center;">Status Penetapan</th>
                                                                <th>Nama Perwakilan (Kandidat Terpilih)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($monitoringData['tahap1_2'] as $satker => $dataMon2)
                                                                <tr>
                                                                    <td style="font-weight: 600; color: #334155;">
                                                                        {{ $satker }}</td>
                                                                    <td style="text-align: center;">
                                                                        @if ($dataMon2['status'] == 'Sudah')
                                                                            <span
                                                                                style="background: #dcfce7; color: #16a34a; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid #86efac;">
                                                                                <i class="fa-solid fa-check"></i> Sudah
                                                                                Ditetapkan
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                style="background: #fee2e2; color: #dc2626; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid #fca5a5;">
                                                                                <i class="fa-solid fa-xmark"></i> Belum
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td
                                                                        style="font-weight: {{ $dataMon2['status'] == 'Sudah' ? 'bold' : 'normal' }}; color: {{ $dataMon2['status'] == 'Sudah' ? '#1e293b' : '#94a3b8' }};">
                                                                        {{ $dataMon2['nama_kandidat'] }}
                                                                    </td>
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

                        {{-- Tombol Manajemen Pertanyaan & Pengaturan Periode yang sebelumnya tetap di bawah sini... --}}
                        @if (in_array($data['user_active']->ist ?? '', ['admin', 'panitia']))
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
                                <div class="ist-step-text">Persiapan<br>Tahap 1</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 2 ? 'completed' : '' }} {{ $activeStep == 2 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-users"></i></div>
                                <div class="ist-step-text">Tahap 1.1<br>(Pemilihan)</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap1_1 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap1_1 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 3 ? 'completed' : '' }} {{ $activeStep == 3 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-user-check"></i></div>
                                <div class="ist-step-text">Tahap 1.2<br>(Penetapan)</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap1_2 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap1_2 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 4 ? 'completed' : '' }} {{ $activeStep == 4 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-arrows-rotate"></i></div>
                                <div class="ist-step-text">Persiapan<br>Tahap 2</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 5 ? 'completed' : '' }} {{ $activeStep == 5 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-folder-open"></i></div>
                                <div class="ist-step-text">Tahap 2.1<br>(Berkas)</div>
                                <div class="ist-step-tooltip">{{ formatTanggal($periode->mulai_tahap2_1 ?? null) }} -
                                    {{ formatTanggal($periode->akhir_tahap2_1 ?? null) }}</div>
                            </li>
                            <li
                                class="ist-step {{ $activeStep >= 6 ? 'completed' : '' }} {{ $activeStep == 6 ? 'active' : '' }}">
                                <div class="ist-step-marker"><i class="fa-solid fa-clipboard-check"></i></div>
                                <div class="ist-step-text">Tahap 2.2<br>(Penilaian)</div>
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

                                    {{-- PANEL FINALISASI --}}
                                    <div id="panel-finalisasi"
                                        style="display:none; background: #ecfdf5; border: 2px dashed #10b981; padding: 25px; border-radius: 12px; text-align: center; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.1);">
                                        <h3 style="color: #059669; margin-bottom: 10px;"><i
                                                class="fa-solid fa-party-horn"></i> Luar Biasa!</h3>
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
                    @endif

                    {{-- ==================================================================== --}}
                    {{-- KONTEN TAHAP 1.2: PENETAPAN KANDIDAT SATKER --}}
                    {{-- ==================================================================== --}}
                    @if ($activeStep == 3 && $periode && $periode->is_active)

                        {{-- BLOK 1: TAMPILAN PILIHAN USER (READ-ONLY UNTUK SEMUA ROLE) --}}
                        <div class="ist-info-box" style="text-align: center; margin-top: 20px;">
                            <h3 style="color: #64748b; margin-bottom: 15px;"><i class="fa-solid fa-lock"></i>
                                Penilaian Tahap 1.1 Telah Ditutup</h3>
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
                                    penilaian pada Tahap 1.1.
                                </div>
                            @endif
                        </div>

                        {{-- BLOK 2: KLASEMEN REKAPITULASI & PENETAPAN (KHUSUS PEJABAT/PANITIA) --}}
                        @if ($isPejabat)
                            <div class="ist-info-box" style="margin-top: 25px;">
                                <div class="ist-info-header">
                                    <h3><i class="fa-solid fa-ranking-star"></i> Klasemen & Penetapan Perwakilan Satker
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
                                            <form action="{{ route('ist.batalkan') }}" method="POST"
                                                class="form-batalkan">
                                                @csrf
                                                <input type="hidden" name="periode_id"
                                                    value="{{ $periode->id }}">
                                                <input type="hidden" name="nip_kandidat"
                                                    value="{{ $kandidatTerpilih->nip_kandidat }}">
                                                <button type="button" class="btn-hapus-nilai btn-batalkan"
                                                    style="font-size: 12px; padding: 8px 15px; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px);">
                                                    <i class="fa-solid fa-xmark"></i> Batalkan
                                                </button>
                                            </form>
                                        </div>

                                        <h4 class="ist-winner-title">
                                            <i class="fa-solid fa-medal fa-bounce"></i> Perwakilan Satker Telah
                                            Ditetapkan!
                                        </h4>

                                        <div class="ist-winner-photo-container">
                                            @if (!empty($kandidatTerpilih->url_foto))
                                                <img src="{{ $kandidatTerpilih->url_foto }}"
                                                    class="ist-winner-photo"
                                                    onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user ist-winner-photo-fallback\'></i>'">
                                            @else
                                                <i class="fa-solid fa-circle-user ist-winner-photo-fallback"></i>
                                            @endif
                                        </div>

                                        <h3 class="ist-winner-name">{{ $kandidatTerpilih->nama }}</h3>

                                        {{-- Jabatan dan Golongan --}}
                                        <p
                                            style="color: #475569; font-size: 14px; margin-top: 8px; margin-bottom: 5px; position: relative; z-index: 5;">
                                            {{ $kandidatTerpilih->gol_akhir }} &bull;
                                            {{ $kandidatTerpilih->jabatan }}
                                        </p>

                                        {{-- Tampilan Satker Asal --}}
                                        <div class="ist-winner-badge">
                                            <i class="fa-solid fa-building-user"></i>
                                            {{ $kandidatTerpilih->wilayah ?? 'Satuan Kerja' }}
                                        </div>

                                        <div style="margin-top: 30px; position: relative; z-index: 5;">
                                            <span class="ist-winner-score">
                                                <i class="fa-solid fa-trophy"
                                                    style="color: #fbbf24; margin-right: 5px;"></i> Skor Akumulasi:
                                                {{ $kandidatTerpilih->total_skor }} Poin
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    {{-- JIKA BELUM DITETAPKAN, TAMPILKAN TABEL KLASEMEN --}}
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
                                                    <tr style="{{ $index === 0 ? 'background: #fffbeb;' : '' }}">
                                                        <td
                                                            style="text-align: center; font-weight: 900; font-size: 18px; color: {{ $index === 0 ? '#fbbf24' : ($index === 1 ? '#94a3b8' : ($index === 2 ? '#b45309' : '#cbd5e1')) }};">
                                                            #{{ $index + 1 }}
                                                        </td>
                                                        <td>
                                                            <div
                                                                style="display: flex; align-items: center; gap: 12px;">
                                                                @if (!empty($rv->url_foto))
                                                                    <img src="{{ $rv->url_foto }}"
                                                                        style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid {{ $index === 0 ? '#fbbf24' : '#e2e8f0' }};"
                                                                        onerror="this.outerHTML='<i class=\'fa-solid fa-circle-user\' style=\'color: #94a3b8; font-size: 45px; margin-right: 10px;\'></i>'">
                                                                @else
                                                                    <i class="fa-solid fa-circle-user"
                                                                        style="color: #94a3b8; font-size: 45px; margin-right: 10px;"></i>
                                                                @endif
                                                                <div>
                                                                    <strong
                                                                        style="color: #1e293b; font-size: 13px;">{{ $rv->nama }}</strong><br>
                                                                    <small
                                                                        style="color: #64748b;">{{ $rv->jabatan }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span
                                                                style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: bold; font-size: 12px;">
                                                                <i class="fa-solid fa-users"></i>
                                                                {{ $rv->total_pemilih }} Suara
                                                            </span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span
                                                                style="color: var(--warna-utama); font-weight: 900; font-size: 18px;">{{ $rv->total_skor }}</span>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <form action="{{ route('ist.tetapkan') }}"
                                                                method="POST" class="form-tetapkan">
                                                                @csrf
                                                                <input type="hidden" name="periode_id"
                                                                    value="{{ $periode->id }}">
                                                                <input type="hidden" name="nip_kandidat"
                                                                    value="{{ $rv->nip }}">
                                                                <button type="button" class="btn-simpan btn-tetapkan"
                                                                    style="padding: 8px 15px; font-size: 12px; border-radius: 30px;">
                                                                    <i class="fa-solid fa-check-to-slot"></i> Tetapkan
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" style="text-align: center; padding: 20px;">
                                                            Belum ada satupun pegawai yang melakukan penilaian di Satker
                                                            ini.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif
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

</body>

</html>
