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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/load/load.css">
    <script src="{{ asset('assets-jazirah/') }}/load/load.js"></script>
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/') }}/img/favicon.png">

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
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/header.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/table-lk.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">

    {{-- DataTables CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.5/css/fixedHeader.dataTables.css">

    <style>
        /* Style untuk Overlay Loading Penuh */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Latar belakang hitam transparan */
            z-index: 9999;
            /* Pastikan di atas segalanya (termasuk modal bootstrap) */
            display: none;
            /* Sembunyikan secara default */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }
    </style>
</head>

<body>

    <div id="loading-overlay">
        <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h4 class="mt-3">Sedang Memproses Data...</h4>
        <p>Mohon tunggu, jangan tutup halaman ini.</p>
    </div>

    <!-- Peringatan Landscape -->
    <div id="orientation-warning" style="display: none;">
        <h1>Website khusus mode landscape</h1>
        <p>silakan ubah ke <strong>mode landscape</strong>.</p>

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
            @include('layout2.navbar-jazirah')
        </header>

        <div class="konten">
            <!-- <img style="width:100%;" src="fitral/img/gedung1.jpg"> -->
            <?php include 'fitral/php/animasitextbps.php'; ?>

            <br>

            <div class="posisitengah">

                <div class="welcome-banner">
                    <h2><span id="typewriter"></span><span class="cursor">|</span></h2>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">

                    @php
                        $pilars = ['I', 'II', 'III', 'IV', 'V', 'VI'];
                        $selectedPilar = (string) ($data['pilar'] ?? '');
                        $selectedSatker = (string) ($data['satker_selected'] ?? '');
                    @endphp

                    <form method="GET" action="{{ url()->current() }}" class="filters-mini">
                        <div class="filters-mini__row">

                            {{-- 1. DROPDOWN PILAR --}}
                            <div class="filters-mini__group">
                                <label class="filters-mini__label">Pilar</label>
                                <select name="pilar" id="select-pilar" class="filters-mini__select">
                                    <option value="">Semua Pilar</option>
                                    @foreach ($data['pilars'] as $p)
                                        <option value="{{ $p }}"
                                            {{ $data['pilar_selected'] == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. DROPDOWN SUB PILAR --}}
                            {{-- Container kita beri ID 'container-subpilar' untuk disembunyikan/dimunculkan --}}
                            <div class="filters-mini__group" id="container-subpilar" style="display: none;">
                                <label class="filters-mini__label">Sub Pilar</label>
                                <select name="subpilar" id="select-subpilar" class="filters-mini__select">
                                    <option value="">Semua Sub Pilar</option>

                                    @foreach ($data['data_subpilar'] as $sub)
                                        {{--
                                            PENTING:
                                            value       = ID unik subpilar (untuk dikirim ke controller)
                                            data-parent = Kode Pilar (kode_3) untuk dicocokkan dengan Pilar diatas
                                            --}}
                                        <option value="{{ $sub->kode_4 }}" data-parent="{{ $sub->kode_3 }}"
                                            {{ $data['subpilar_selected'] == $sub->rencana_kerja ? 'selected' : '' }}>
                                            {{ $sub->rencana_kerja }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- 3. DROPDOWN SATKER --}}
                            <div class="filters-mini__group">
                                <label class="filters-mini__label">Satker</label>
                                <select name="satker" class="filters-mini__select filters-mini__select--satker">
                                    @foreach ($data['satker'] ?? [] as $s)
                                        @if ($data['user_active'][0]->kode_satker === '1100')
                                            <option value="{{ $s->kode_satker }}"
                                                {{ $selectedSatker === (string) $s->kode_satker ? 'selected' : '' }}>
                                                {{ $s->nama_satker }}
                                            </option>
                                        @else
                                            @if ($data['user_active'][0]->kode_satker === $s->kode_satker)
                                                <option value="{{ $s->kode_satker }}"
                                                    {{ $selectedSatker === (string) $s->kode_satker ? 'selected' : '' }}>
                                                    {{ $s->nama_satker }}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- 4. CHECKBOX TUGAS SAYA --}}
                            <div class="filters-mini__group">
                                <label class="filters-mini__label" for="task-me">Tugas Saya</label>
                                <div class="filters-mini__input-wrapper"
                                    style="display: flex; align-items: center; height: 38px;">
                                    {{-- onchange dihapus, jadi filter hanya jalan saat tombol Terapkan diklik --}}
                                    <input type="checkbox" name="task" value="me" id="task-me"
                                        {{ request('task') == 'me' ? 'checked' : '' }}
                                        style="width: 20px; height: 20px; cursor: pointer;">
                                </div>
                            </div>

                            <div class="filters-mini__actions">
                                <button class="filters-mini__btn" type="submit">Terapkan</button>
                                @if ($selectedPilar !== '' || $selectedSatker !== '')
                                    <a class="filters-mini__btn filters-mini__btn--ghost"
                                        href="{{ url()->current() }}">Reset</a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblLembarKerja" class="table table-striped table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th>Rencana Kerja</th>
                                        <th>Rencana Aksi</th>
                                        <th>Output</th>
                                        <th>Penanggung Jawab</th>
                                        <th>Target</th>
                                        <th>Realisasi</th>
                                        <th>Dokumen</th>
                                        {{-- <th>Progress</th> --}}
                                        <th>Status Dokumen</th>
                                        <th>Pemeriksaan</th>
                                        <th>Progress <br> Tr 1</th>
                                        <th>Progress <br> Tr 2</th>
                                        <th>Progress <br> Tr 3</th>
                                        <th>Progress <br> Tr 4</th>
                                        <th>Progress <br> Tahunan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['indikator'] ?? [] as $row)
                                        @php
                                            $lvl = (int) ($row->level ?? 1);
                                            $lvl = max(1, min(5, $lvl));
                                            $text = $row->rencana_kerja ?? ($row->rencana_kerja ?? '-');
                                            $status = $row->status ?? null; // sesuaikan
                                            $array_penanggungjawab = array_map(
                                                'trim',
                                                explode(',', $row->isian->penanggungjawab ?? null),
                                            );
                                        @endphp

                                        <tr id="baris-{{ $row->id }}">
                                            {{-- data-order biar sorting tetap rapi walau ada bullet/indent --}}
                                            <td data-order="{{ strip_tags($text) }}">
                                                <div class="lvl-wrap lvl-{{ $lvl }}"
                                                    style="--lvl: {{ $lvl }};">
                                                    <br>
                                                    @if ($row->pengisian === 1)
                                                        <button type="button" class="btn btn-edit-pill pedoman1"
                                                            title="Pedoman Bukti Dukung" aria-label="Edit"
                                                            style="color: black !important; border:1px solid black !important"
                                                            data-bs-toggle="modal" data-bs-target="#modalPedoman1"
                                                            data-id="{{ $row->isian->id ?? '' }}"
                                                            data-rencana_kerja_ped="{{ $row->rencana_kerja ?? '' }}"
                                                            data-dokumen_ped="{{ $row->pedoman ?? '' }}"
                                                            data-contoh_link_ped="{{ $row->contoh_link ?? '' }}">
                                                            <i class="bi bi-file-earmark-ppt"></i><span>
                                                                Pedoman</span>
                                                        </button>
                                                        <hr>
                                                    @endif
                                                    <div class="lvl-chip">
                                                        <span class="lvl-text">{{ $text }}</span>
                                                    </div>
                                                    @if ($row->pengisian === 1)
                                                        @if (
                                                            $data['user_active'][0]->role === 'admin' ||
                                                                ($data['user_active'][0]->kode_satker === $selectedSatker &&
                                                                    in_array($data['user_active'][0]->username, $array_penanggungjawab) &&
                                                                    $row->isian->status_dokumen !== '5'))
                                                            <hr>
                                                            <button type="button"
                                                                class="btn btn-edit-pill js-edit-isian1"
                                                                title="Edit Rencana Aksi,Output, PJ dan Target"
                                                                aria-label="Edit" data-bs-toggle="modal"
                                                                data-bs-target="#modalEditIsian1"
                                                                data-id="{{ $row->isian->id ?? '' }}"
                                                                data-rencana_kerja="{{ $row->rencana_kerja ?? '' }}"
                                                                data-rencanaaksi="{{ $row->isian->rencanaaksi ?? '' }}"
                                                                data-rencanaaksi_tahun_lalu="{{ $row->isian->rencanaaksi_tahun_lalu ?? '' }}"
                                                                data-output="{{ $row->isian->output ?? '' }}"
                                                                data-output_tahun_lalu="{{ $row->isian->output_tahun_lalu ?? '' }}"
                                                                data-penanggungjawab="{{ $row->isian->penanggungjawab ?? '' }}"
                                                                data-bulan-target="{{ $row->isian->bulan_target ?? '' }}">
                                                                <i class="bi bi-pencil-square"></i><span> Due:
                                                                    Rabu, 1
                                                                    April 2026</span>
                                                            </button>
                                                        @endif
                                                    @endif
                                                    <br>
                                                    <br>
                                                </div>

                                            </td>

                                            <td class="keep-enter">
                                                @if ($row->pengisian === 1)
                                                    <div class="lvl-wrap">
                                                        <div class="lvl-chip">
                                                            {{ $row->isian->rencanaaksi ?? '-' }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="keep-enter">
                                                @if ($row->pengisian === 1)
                                                    <div class="lvl-wrap">
                                                        <div class="lvl-chip">
                                                            {{ $row->isian->output ?? '-' }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($row->pengisian === 1)
                                                    {{ $row->isian->penanggungjawab ?? '-' }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($row->pengisian === 1)
                                                    {{ $row->isian->bulan_target_nama ?? '-' }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($row->pengisian === 1)
                                                    {{ $row->isian->bulan_realisasi_nama ?? '-' }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($row->pengisian === 1)
                                                    {{-- contoh: tampilkan link/filename kalau ada --}}
                                                    @if (!empty($row->isian->link_buktidukung))
                                                        @if (!empty($row->isian->jumlah_dokumen))
                                                            <p>{{ $row->isian->jumlah_dokumen }} Dokumen</p>
                                                        @endif
                                                        <button type="button" class="btn btn-edit-pill"
                                                            onclick="generateData2('{{ $row->isian->link_buktidukung }}')"
                                                            style="color: rgb(0, 195, 255) !important; border:1px solid rgb(7, 61, 0) !important">
                                                            <i class="bi bi-eye-fill"></i><span> Tersedia</span>
                                                            {{-- <a href="{{ $row->isian->link_buktidukung }}"
                                                                target="_blank" style="text-decoration: none">
                                                                <i class="bi bi-eye-fill"></i><span> Tersedia</span>
                                                            </a> --}}
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-edit-pill" disabled>
                                                            <i class="bi bi-eye-slash-fill"></i><span>
                                                                Belum Ada</span>
                                                        </button>
                                                    @endif
                                                    @if (
                                                        $data['user_active'][0]->role === 'admin' ||
                                                            ($data['user_active'][0]->kode_satker === $selectedSatker &&
                                                                in_array($data['user_active'][0]->username, $array_penanggungjawab) &&
                                                                ($row->isian->status_dokumen === '1' ||
                                                                    $row->isian->status_dokumen === '2' ||
                                                                    $row->isian->status_dokumen === '3')))
                                                        <hr>
                                                        <button type="button"
                                                            class="btn btn-edit-pill js-edit-isian2"
                                                            title="Edit Realisasi dan Bukti Dukung" aria-label="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian2"
                                                            data-id2="{{ $row->isian->id ?? '' }}"
                                                            data-rencana_kerja2="{{ $row->rencana_kerja ?? '' }}"
                                                            data-bulan-target2="{{ $row->isian->bulan_target ?? '' }}"
                                                            data-bulan-realisasi="{{ $row->isian->bulan_realisasi ?? '' }}"
                                                            data-link_buktidukung="{{ $row->isian->link_buktidukung ?? '' }}">
                                                            <i class="bi bi-pencil-square"></i><span> Realisasi</span>

                                                        </button>
                                                    @endif
                                                @endif
                                            </td>

                                            <td>
                                                @if ($row->pengisian === 1)
                                                    @php
                                                        $statusDokumen = $row->isian->status_dokumen ?? 'Belum Isi';
                                                        // Konfigurasi tiap tahap (0 - 5)
                                                        // Pastikan jadi number/integer
                                                        $statusNum = (int) $statusDokumen;

                                                        // Konfigurasi tiap tahap (0 - 5)
                                                        $statusMap = [
                                                            0 => [
                                                                'label' => '',
                                                                'color' => '#6c757d',
                                                                'icon' => 'bi-file-earmark',
                                                            ],
                                                            1 => [
                                                                'label' => '',
                                                                'color' => '#dc3545',
                                                                'icon' => 'bi-send',
                                                            ],
                                                            2 => [
                                                                'label' => '',
                                                                'color' => '#fd7e14',
                                                                'icon' => 'bi-gear',
                                                            ],
                                                            3 => [
                                                                'label' => '',
                                                                'color' => '#ffc107',
                                                                'icon' => 'bi-search',
                                                            ],
                                                            4 => [
                                                                'label' => '',
                                                                'color' => '#2b00ff',
                                                                'icon' => 'bi-patch-check',
                                                            ],
                                                            5 => [
                                                                'label' => '',
                                                                'color' => '#198754',
                                                                'icon' => 'bi-check-all',
                                                            ],
                                                        ];

                                                        $currentStatus = $statusMap[$statusNum] ?? $statusMap[0];

                                                        // Rumus: (n+1) / 6 * 100
                                                        $percentage = ($statusNum / 5) * 100;
                                                    @endphp
                                                    @if ($statusDokumen === '1')
                                                        <button type="button" class="btn btn-status"
                                                            title="Status Dokumen" aria-label="Edit">
                                                            <i class="bi bi-graph-up-arrow"></i><span>
                                                                Target Sudah Ditetapkan</span>
                                                        </button>
                                                    @elseif ($statusDokumen === '2')
                                                        @if ($data['user_active'][0]->username === $row->isian->created_by_3)
                                                            <form id="form-finish-{{ $row->isian->id }}"
                                                                action="{{ url('/isian/' . $row->isian->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('PUT')
                                                                {{-- Variabel pengisian dikirim di sini --}}
                                                                <input type="hidden" name="pengisian"
                                                                    value="kelima">
                                                                <input type="hidden" name="updateby" id="updateby"
                                                                    value="{{ $data['user_active'][0]->username }}">
                                                            </form>
                                                            <button type="button"
                                                                class="btn btn-status btn-finish-confirm"
                                                                title="Status Dokumen" aria-label="Edit"
                                                                {{-- Logika cek user tetap dipertahankan untuk keamanan tampilan --}}
                                                                data-id="{{ $row->isian->id ?? '' }}">
                                                                <i class="bi bi-graph-up-arrow"></i>
                                                                <span>Bukti Dukung Sudah Diisi</span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-status"
                                                                title="Status Dokumen" aria-label="Edit">
                                                                <i class="bi bi-graph-up-arrow"></i>
                                                                <span>Bukti Dukung Sudah Diisi</span>
                                                            </button>
                                                        @endif
                                                    @elseif ($statusDokumen === '3')
                                                        <button type="button" class="btn btn-status"
                                                            title="Status Dokumen" aria-label="Edit">
                                                            <i class="bi bi-graph-up-arrow"></i><span>
                                                                Terdapat Evaluasi</span>
                                                        </button>
                                                    @elseif ($statusDokumen === '4')
                                                        @if ($data['user_active'][0]->username === $row->isian->created_by_3)
                                                            <form id="form-finish-{{ $row->isian->id }}"
                                                                action="{{ url('/isian/' . $row->isian->id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('PUT')
                                                                {{-- Variabel pengisian dikirim di sini --}}
                                                                <input type="hidden" name="pengisian"
                                                                    value="kelima">
                                                                <input type="hidden" name="updateby" id="updateby"
                                                                    value="{{ $data['user_active'][0]->username }}">
                                                            </form>
                                                            <button type="button"
                                                                class="btn btn-status btn-finish-confirm"
                                                                title="Status Dokumen" aria-label="Edit"
                                                                {{-- Logika cek user tetap dipertahankan untuk keamanan tampilan --}}
                                                                data-id="{{ $row->isian->id ?? '' }}">
                                                                <i class="bi bi-graph-up-arrow"></i>
                                                                <span>Sudah Ditindaklanjuti</span>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-status"
                                                                title="Status Dokumen" aria-label="Edit">
                                                                <i class="bi bi-graph-up-arrow"></i>
                                                                <span>Sudah Ditindaklanjuti</span>
                                                            </button>
                                                        @endif
                                                    @elseif ($statusDokumen === '5')
                                                        <button type="button" class="btn btn-status"
                                                            title="Status Dokumen" aria-label="Edit">
                                                            <i class="bi bi-graph-up-arrow"></i><span>
                                                                Selesai</span>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-status">
                                                            <i class="bi bi-x-lg"></i>
                                                            <span>{{ $statusDokumen }}</span>
                                                        </button>
                                                    @endif
                                                    <div class="progress-wrapper">
                                                        <div class="progress-track">
                                                            <div class="progress-bar-status"
                                                                style="width: {{ $percentage }}%;
                                                                    border-color: {{ $currentStatus['color'] }} !important;
                                                                    color: {{ $currentStatus['color'] }} !important;">

                                                                <i class="bi {{ $currentStatus['icon'] }}"></i>
                                                                <span>{{ $currentStatus['label'] }}
                                                                    ({{ round($percentage) }}%)</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="keep-enter">
                                                {{-- {{ $row->isian->created_by_3 }} --}}
                                                @if ($row->pengisian === 1)
                                                    @if (
                                                        ($row->isian?->created_by_3 ?? null) !== null &&
                                                            $data['user_active'][0]->username === $row->isian->created_by_3 &&
                                                            in_array($row->isian->status_dokumen, ['2', '3', '4']))
                                                        <button type="button"
                                                            class="btn btn-edit-pill js-edit-isian3"
                                                            title="Beri Evaluasi" aria-label="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian3"
                                                            data-id3="{{ $row->isian->id ?? '' }}"
                                                            data-rencana_kerja3="{{ $row->rencana_kerja ?? '' }}"
                                                            data-komentar_evaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}">
                                                            <i class="bi bi-pencil-square"></i><span> Evaluasi</span>
                                                        </button>
                                                    @endif
                                                    {{-- {{ $row->isian->id ?? '' }} --}}
                                                    @if (!empty($row->isian->komentar_evaluator1))
                                                        <b>{{ $row->isian->created_by_3 ?? 'Evaluator' }} :</b>
                                                        {{ $row->isian->komentar_evaluator1 ?? '-' }}
                                                        <hr>
                                                        @if (!empty($row->isian->komentar_operator1))
                                                            <b>{{ $row->isian->created_by_4 ?? 'Operator' }} :</b>
                                                            {{ $row->isian->komentar_operator1 ?? '-' }}
                                                        @endif

                                                        @if (
                                                            $data['user_active'][0]->role === 'admin' ||
                                                                ($data['user_active'][0]->kode_satker === $selectedSatker &&
                                                                    in_array($data['user_active'][0]->username, $array_penanggungjawab) &&
                                                                    $row->isian->status_dokumen === '3'))
                                                            <button type="button"
                                                                class="btn btn-edit-pill js-edit-isian4"
                                                                title="Beri Tindak Lanjut" aria-label="Edit"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditIsian4"
                                                                data-id4="{{ $row->isian->id ?? '' }}"
                                                                data-rencana_kerja4="{{ $row->rencana_kerja ?? '' }}"
                                                                data-komentar_evaluator12="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                                data-komentar_operator1="{{ $row->isian->komentar_operator1 ?? '' }}">
                                                                <i class="bi bi-pencil-square"></i><span> Tindak
                                                                    Lanjut</span>
                                                            </button>
                                                        @endif
                                                        <br>
                                                    @endif
                                                @endif
                                            </td>
                                            @php
                                                // Kumpulkan field yang akan dilooping secara berurutan
                                                $progressFields = [
                                                    $row->isian->progres_tw1 ?? null,
                                                    $row->isian->progres_tw2 ?? null,
                                                    $row->isian->progres_tw3 ?? null,
                                                    $row->isian->progres_tw4 ?? null,
                                                    $row->isian->progres_th ?? null,
                                                ];
                                            @endphp

                                            @foreach ($progressFields as $nilai)
                                                <td>
                                                    @if ($row->pengisian === 1 && $nilai !== null)
                                                        @php
                                                            // Tentukan gaya berdasarkan nilai
                                                            $isTidakAdaTarget = $nilai === 'Tidak Ada Target';

                                                            if ($isTidakAdaTarget) {
                                                                $lebar = 100;
                                                                $warna = '#6c757d'; // Abu-abu
                                                                $ikon = 'bi-dash-circle';
                                                                $teks = 'Tidak Ada Target';
                                                            } else {
                                                                $angka = (float) $nilai;
                                                                $lebar = $angka;
                                                                $teks = $angka . '%';

                                                                if ($angka >= 100) {
                                                                    $warna = '#198754'; // Hijau
                                                                    $ikon = 'bi-check-circle-fill';
                                                                } elseif ($angka > 0) {
                                                                    $warna = '#2b00ff'; // Biru
                                                                    $ikon = 'bi-activity';
                                                                } else {
                                                                    $warna = '#dc3545'; // Merah
                                                                    $ikon = 'bi-x-circle';
                                                                }
                                                            }
                                                        @endphp

                                                        <div class="progress-track"
                                                            style="background-color: #f1f3f5; border-radius: 12px; height: 32px; border: 1px solid #1A71A7; overflow: hidden; display: flex; align-items: center; width: 100%; min-width: 120px;">
                                                            <div class="btn-status"
                                                                style="width: {{ $lebar }}%;
                                                                    min-width: {{ $isTidakAdaTarget ? '100%' : '60px' }};
                                                                    border-color: {{ $warna }} !important;
                                                                    color: {{ $warna }} !important;
                                                                    transition: width 0.5s ease;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    height: 100%;
                                                                    gap: 10px;
                                                                    padding: 0 10px;
                                                                    border-radius: 12px;
                                                                    background: {{ $isTidakAdaTarget ? '#e9ecef' : '#f1f3f5' }};
                                                                    border: 1px solid #1A71A7;
                                                                    font-size: 9px !important;
                                                                    font-weight: bold;
                                                                    white-space: nowrap;">

                                                                <i class="bi {{ $ikon }}"></i>
                                                                <span>{{ $teks }}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <br>
            <br>
        </div>

        @php
            $bulanList = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'Mei',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Agu',
                9 => 'Sep',
                10 => 'Okt',
                11 => 'Nov',
                12 => 'Des',
            ];
        @endphp

        {{-- Modal Pedoman Bukti Dukung --}}
        <div class="modal fade" id="modalPedoman1" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Pedoman Bukti Dukung ZI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <b>
                                <p id="edit_rencana_kerja_ped"></p>
                            </b>
                        </div>

                        <br>

                        <div class="mb-3">
                            <b>Rincian Bukti Dukung :</b>
                            <p id="edit_dokumen_ped"></p>
                        </div>

                        <br>

                        <div class="mb-3">
                            <p id="edit_contoh_link_ped"></p>
                        </div>

                        <br>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Pengisian Pertama --}}
        <div class="modal fade" id="modalEditIsian1" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="formEditIsian" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Update Rencana Aksi,Output, PJ dan Target</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <input type="hidden" name="pengisian" id="pengisian" value="pertama">
                            <input type="hidden" name="updateby" id="updateby"
                                value="{{ $data['user_active'][0]->username }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja"
                                    id="edit_rencana_kerja" disabled>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Penanggung Jawab
                                </label> &nbsp;
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnPjClear">
                                    Kosongkan
                                </button>

                                <div class="d-flex gap-2 mb-2">
                                    {{-- <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="btnPjSelectAll">
                                        Pilih semua
                                    </button> --}}

                                </div>

                                <select id="edit_penanggungjawab" name="penanggungjawab[]" class="form-select"
                                    multiple>
                                    @foreach ($data['users'] ?? [] as $u)
                                        <option value="{{ $u->username }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>

                                <div class="form-text">
                                    Bisa pilih lebih dari satu.
                                </div>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Aksi</label>&nbsp;
                                {{-- Tombol Copy --}}
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 btn-copy-lalu-1"
                                    id="btnCopyRencana" data-target="edit_rencanaaksi" title="Salin dari tahun lalu">
                                    <i class="bi bi-clipboard-check"></i> Salin Tahun Lalu
                                </button>
                                <textarea class="form-control" name="rencanaaksi" id="edit_rencanaaksi" rows="8"
                                    placeholder="Tulis Rencana Aksi mu..."></textarea>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Output</label>&nbsp;
                                {{-- Tombol Copy --}}
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 btn-copy-lalu-2"
                                    id="btnCopyOutput" data-target="edit_output" title="Salin dari tahun lalu">
                                    <i class="bi bi-clipboard-check"></i> Salin Tahun Lalu
                                </button>
                                <textarea class="form-control" name="output" id="edit_output" rows="8"
                                    placeholder="Tulis Output dari Rencana Aksi mu..."></textarea>
                            </div>

                            <br>


                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">Bulan Target</label>
                                <div class="d-flex flex-wrap gap-2" id="bulanTargetWrap">
                                    @foreach ([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'] as $n => $lbl)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="checkbox" name="bulan_target[]"
                                                value="{{ $n }}" id="bt{{ $n }}">
                                            <label class="form-check-label"
                                                for="bt{{ $n }}">{{ $lbl }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="bulan_target_nama" id="bulan_target_csv">
                            </div>

                            <br>

                        </div>

                        <div class="modal-footer">
                            <p style="font-size: 8px">Diupdate oleh : {{ $data['user_active'][0]->name }} </p>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Pengisian Kedua --}}
        <div class="modal fade" id="modalEditIsian2" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="formEditIsian2" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Update Realisasi dan Bukti Dukung</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id2" id="edit_id2">
                            <input type="hidden" name="pengisian" id="pengisian" value="kedua">
                            <input type="hidden" name="total_files2" id="total_files2" required>
                            <input type="hidden" name="updateby" id="updateby"
                                value="{{ $data['user_active'][0]->username }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja2"
                                    id="edit_rencana_kerja2" disabled>
                            </div>

                            <br>
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">Bulan Target</label>
                                <div class="d-flex flex-wrap gap-2" id="bulanTargetWrap2">
                                    @foreach ([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'] as $n => $lbl)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="checkbox" name="bulan_target2[]"
                                                value="{{ $n }}" id="bt{{ $n }}" disabled>
                                            <label class="form-check-label"
                                                for="bt{{ $n }}">{{ $lbl }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">Bulan Realisasi</label>
                                <div class="d-flex flex-wrap gap-2" id="bulanRealisasiWrap">
                                    @foreach ([1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'] as $n => $lbl)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="checkbox" name="bulan_realisasi[]"
                                                value="{{ $n }}" id="br{{ $n }}">
                                            <label class="form-check-label"
                                                for="br{{ $n }}">{{ $lbl }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="bulan_realisasi_nama" id="bulan_realisasi_csv">
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Bukti Dukung</label>
                                <input type="text" class="form-control" name="link_buktidukung"
                                    id="edit_link_buktidukung">
                            </div>

                            <button type="button" class="btn btn-success mb-3" onclick="generateData()">
                                <span id="btn-text">Generate & Lihat File</span>
                                <span id="btn-loading" class="spinner-border spinner-border-sm d-none"
                                    role="status"></span>
                            </button>

                            <div class="mb-3">
                                <label for="total_files" class="form-label">Total File (PDF/Image)</label>
                                <input type="number" class="form-control bg-light" id="total_files" readonly
                                    required>
                            </div>

                            <br>

                        </div>

                        <div class="modal-footer">
                            <p style="font-size: 8px">Diupdate oleh : {{ $data['user_active'][0]->name }} </p>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Pengisian Ketiga --}}
        <div class="modal fade" id="modalEditIsian3" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="formEditIsian3" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Beri Evaluasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id3" id="edit_id3">
                            <input type="hidden" name="pengisian" id="pengisian" value="ketiga">
                            <input type="hidden" name="updateby" id="updateby"
                                value="{{ $data['user_active'][0]->username }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja3"
                                    id="edit_rencana_kerja3" disabled>
                            </div>

                            <br>

                            <div class="mb-3" id="wrap_komentar_evaluator1">
                                <label class="form-label fw-semibold">Komentar Evaluator</label>
                                <textarea class="form-control" name="komentar_evaluator1" id="edit_komentar_evaluator1" rows="8"
                                    placeholder="Tulis komentar evaluator..."></textarea>
                            </div>

                            <br>

                        </div>

                        <div class="modal-footer">
                            <p style="font-size: 8px">Diupdate oleh : {{ $data['user_active'][0]->name }} </p>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Pengisian Keempat --}}
        <div class="modal fade" id="modalEditIsian4" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="formEditIsian4" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Beri Evaluasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="id4" id="edit_id4">
                            <input type="hidden" name="pengisian" id="pengisian" value="keempat">
                            <input type="hidden" name="updateby" id="updateby"
                                value="{{ $data['user_active'][0]->username }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja4"
                                    id="edit_rencana_kerja4" disabled>
                            </div>

                            <br>

                            <div class="mb-3" id="wrap_komentar_evaluator12">
                                <label class="form-label fw-semibold">Komentar Evaluator</label>
                                <textarea class="form-control" name="komentar_evaluator12" id="edit_komentar_evaluator12" rows="8"
                                    placeholder="Tulis komentar evaluator..." disabled></textarea>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Komentar Operator</label>
                                <textarea class="form-control" name="komentar_operator1" id="edit_komentar_operator1" rows="8"
                                    placeholder="Tulis komentar operator..."></textarea>
                            </div>

                            <br>

                        </div>

                        <div class="modal-footer">
                            <p style="font-size: 8px">Diupdate oleh : {{ $data['user_active'][0]->name }} </p>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal List Data --}}
        <div class="modal fade" id="fileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Daftar File Ditemukan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tableFiles" class="table table-bordered table-striped table-hover"
                                style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama File</th>
                                        <th>Folder</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Upload</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-table-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <?php include 'fitral/php/footer.php'; ?>
        </footer>
    </div>
</body>

<script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>

{{-- jQuery + DataTables JS (CDN) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedheader/4.0.5/js/dataTables.fixedHeader.js"></script>
<script src="https://cdn.datatables.net/fixedheader/4.0.5/js/fixedHeader.dataTables.js"></script>




<!-- JS (pastikan jQuery sudah ada) -->
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Definisikan Elemen
        const pilarSelect = document.getElementById('select-pilar');
        const subPilarSelect = document.getElementById('select-subpilar');
        const subPilarContainer = document.getElementById('container-subpilar');

        // 2. SIMPAN CADANGAN DATA (PENTING!)
        // Kita menyalin (clone) semua opsi subpilar ke dalam variabel memori.
        // Jika tidak diclone, opsi akan hilang selamanya setelah filter pertama.
        const allSubOptions = Array.from(subPilarSelect.querySelectorAll('option')).map(opt => opt.cloneNode(
            true));

        // Fungsi Utama Filter
        function filterSubPilar() {
            const selectedPilar = pilarSelect.value; // Nilai pilar yang dipilih (misal: "I")
            const currentSubVal = "{{ $data['subpilar_selected'] }}"; // Nilai lama dari PHP (jika ada)

            // Reset isi dropdown subpilar jadi kosong dulu
            subPilarSelect.innerHTML = '';

            if (selectedPilar === "") {
                // Jika Pilar Kosong -> Sembunyikan Sub Pilar
                subPilarContainer.style.display = 'none';
            } else {
                // Jika Pilar Ada -> Tampilkan Container
                subPilarContainer.style.display = 'block';

                // Loop data cadangan (allSubOptions)
                allSubOptions.forEach(option => {
                    const parentKode = option.getAttribute('data-parent');

                    // Masukkan opsi ke dropdown JIKA:
                    // 1. Opsi itu adalah placeholder ("Semua Sub Pilar") -> valuenya kosong
                    // 2. ATAU kode parentnya COCOK dengan pilar yang dipilih
                    if (option.value === "" || parentKode == selectedPilar) {
                        subPilarSelect.appendChild(option);
                    }
                });

                // Kembalikan nilai yang terpilih (agar tidak reset saat user klik tombol Terapkan)
                // Cek apakah nilai lama masih valid di daftar yang baru
                const isExist = Array.from(subPilarSelect.options).some(opt => opt.value === currentSubVal);
                if (currentSubVal && isExist) {
                    subPilarSelect.value = currentSubVal;
                } else {
                    subPilarSelect.value = ""; // Default ke "Semua"
                }
            }
        }

        // 3. Jalankan Fungsi
        // Jalankan saat halaman baru dimuat (untuk handle kondisi setelah tombol Terapkan ditekan)
        filterSubPilar();

        // Jalankan setiap kali user mengganti Pilar
        pilarSelect.addEventListener('change', function() {
            // Reset pilihan subpilar saat pilar induk berubah
            subPilarSelect.value = "";
            filterSubPilar();
        });
    });
</script>

<script>
    $('#tblLembarKerja').css({
        'table-layout': 'fixed',
        'width': '100%',
        'word-wrap': 'break-word'
    });

    var table = $('#tblLembarKerja').DataTable({
        // 1. Matikan autoWidth wajib hukumnya
        autoWidth: false,
        fixedHeader: {
            header: true,
            headerOffset: 70
        },
        columnDefs: [{
                targets: '_all',
                className: 'dt-head-center'
            },
            {
                targets: 0,
                orderable: false,
                width: '300px'
            },
            {
                targets: 1,
                orderable: false,
                width: '200px'
            },
            {
                targets: 2,
                orderable: false,
                width: '200px'
            },
            {
                targets: 3,
                orderable: false,
                width: '130px',
                className: 'dt-body-center'
            },
            {
                targets: 4,
                orderable: false,
                width: '100px',
                className: 'dt-body-center'
            },
            {
                targets: 5,
                orderable: false,
                width: '100px',
                className: 'dt-body-center'
            },
            {
                targets: 6,
                orderable: false,
                width: '100px',
                className: 'dt-body-center'
            },
            {
                targets: 7,
                orderable: false,
                width: '200px',
                className: 'dt-body-center'
            },
            {
                targets: 8,
                orderable: false,
                width: '250px'
            },
            {
                targets: 9,
                width: '150px',
                className: 'dt-body-center'
            },
            {
                targets: 10,
                width: '150px',
                className: 'dt-body-center'
            },
            {
                targets: 11,
                width: '150px',
                className: 'dt-body-center'
            },
            {
                targets: 12,
                width: '150px',
                className: 'dt-body-center'
            },
            {
                targets: 13,
                width: '150px',
                className: 'dt-body-center'
            }
        ],
        paging: false,
        // info: false,
        // ordering: false,
        // searching: false,
        order: [],
        scrollX: true, // Mengaktifkan scroll horizontal
        // 4. MENGATUR BORDER ISI TABEL (TD)
        // Fitur ini otomatis memberi border pada data meskipun Anda pindah halaman
        createdRow: function(row, data, dataIndex) {
            $(row).find('td').css({
                'border': '1px solid #54acff' // Border hitam pekat untuk isi tabel
            });
        },

        // 5. MENGATUR HEADER (Warna Background & Border)
        initComplete: function(settings, json) {
            // Border dan warna untuk Header (TH)
            $(this.api().table().header()).find('th').css({
                'background-color': '#54acff',
                'color': '#ffffff',
                'border': '1px solid #000000' // Border hitam pekat untuk header
            });

            // (Opsional) Border untuk bingkai luar tabel secara keseluruhan
            $(this.api().table().node()).css({
                'border': '1px solid #54acff'
            });
        }
        // autoWidth: false, // Mencegah DataTables menghitung lebar otomatis
        // columnDefs: [{
        //         "width": "200px",
        //         "targets": 0
        //     },
        //     {
        //         "width": "150px",
        //         "targets": 1
        //     },
        //     {
        //         "width": "150px",
        //         "targets": 2
        //     },
        //     {
        //         "width": "100px",
        //         "targets": 3
        //     },
        //     {
        //         "width": "80px",
        //         "targets": 4
        //     },
        //     {
        //         "width": "80px",
        //         "targets": 5
        //     },
        //     {
        //         "width": "100px",
        //         "targets": 6
        //     },
        //     {
        //         "width": "900px",
        //         "targets": 7
        //     }
        // ]
    });

    // Tunggu 200ms setelah halaman siap, lalu adjust kolom
    setTimeout(function() {
        table.columns.adjust().draw();
    }, 2000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Pedoman --}}
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.pedoman1');
        if (!btn) return;

        const id = (btn.dataset.id || '').trim();
        const rencana_kerja_ped = btn.dataset.rencana_kerja_ped || '';
        const dokumen_ped = btn.dataset.dokumen_ped || '';
        const contoh_link_ped = btn.dataset.contoh_link_ped || '';

        document.getElementById('edit_rencana_kerja_ped').innerText = rencana_kerja_ped;
        document.getElementById('edit_dokumen_ped').innerText = dokumen_ped;
        document.getElementById('edit_contoh_link_ped').innerText = contoh_link_ped;

        // 2. Pilih elemen target
        const targetElement = document.getElementById("edit_contoh_link_ped");

        // 3. Masukkan HTML berupa tag <a> dan ikon
        // Kita gunakan target="_blank" agar link terbuka di tab baru
        targetElement.innerHTML = `
                <a href="${contoh_link_ped}" target="_blank" class="btn btn-edit-pill" style="color: black !important; border:1px solid green !important">
                    <b><i class="bi bi-file-earmark-ppt"></i>   Lihat Dokumen</b>
                </a>
            `;
    })
</script>

{{-- Pengisian Pertama --}}
<script>
    function setCheckedFromCsv(containerId, csv) {
        const wrap = document.getElementById(containerId);
        if (!wrap) return;

        const set = new Set(
            (csv || '')
            .split(',')
            .map(s => parseInt(String(s).trim(), 10))
            .filter(n => Number.isFinite(n))
        );

        wrap.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            const v = parseInt(cb.value, 10);
            cb.checked = set.has(v);
        });
    }

    function csvFromChecked(containerId) {
        const wrap = document.getElementById(containerId);
        if (!wrap) return '';

        return Array.from(wrap.querySelectorAll('input[type="checkbox"]:checked'))
            .map(cb => parseInt(cb.value, 10))
            .filter(n => Number.isFinite(n))
            .sort((a, b) => a - b)
            .join(',');
    }

    // Modal Pertama Muncul
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-edit-isian1');
        if (!btn) return;

        const id = (btn.dataset.id || '').trim();

        // kalau id kosong, jangan buka modal (opsional tapi aman)
        if (!id) {
            // modal mungkin sudah kebuka karena data-bs-toggle, jadi kita tutup lagi
            const modalEl = document.getElementById('modalEditIsian');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            alert('Data isian belum ada untuk baris ini.');
            return;
        }

        // isi field
        document.getElementById('edit_id').value = id;

        const rencana_kerja = btn.dataset.rencana_kerja || '';
        const penanggungjawab = btn.dataset.penanggungjawab || '';
        const rencanaaksi = btn.dataset.rencanaaksi || '';
        const output = btn.dataset.output || '';
        const raLalu = btn.dataset.rencanaaksi_tahun_lalu || '';
        const outLalu = btn.dataset.output_tahun_lalu || '';

        const rencana_kerjaEl = document.getElementById('edit_rencana_kerja');
        if (rencana_kerjaEl) rencana_kerjaEl.value = rencana_kerja;

        // document.getElementById('edit_penanggungjawab').value = penanggungjawab;
        document.getElementById('edit_rencanaaksi').value = rencanaaksi;
        document.getElementById('edit_output').value = output;

        let stringDariDB = penanggungjawab;
        let arrayPenanggungjawab = stringDariDB.split(',').map(item => item.trim());

        // Masukkan array langsung ke select
        $('#edit_penanggungjawab').val(arrayPenanggungjawab);

        // auto-ceklis bulan
        setCheckedFromCsv('bulanTargetWrap', btn.dataset.bulanTarget || '');

        // set hidden csv
        document.getElementById('bulan_target_csv').value = csvFromChecked('bulanTargetWrap');

        $('.btn-copy-lalu-1').on('click', function() {
            if (raLalu) {
                document.getElementById('edit_rencanaaksi').value = raLalu;
            } else {
                alert('Data tahun lalu tidak ada.');
            };
        });
        $('.btn-copy-lalu-2').on('click', function() {
            if (raLalu) {
                document.getElementById('edit_output').value = outLalu;
            } else {
                alert('Data tahun lalu tidak ada.');
            };
        });

        // set action (sesuaikan path route kamu)
        const form = document.getElementById('formEditIsian');
        form.action = `{{ url('/isian') }}/${id}`; // PUT /isian/{id}
    });

    // update hidden saat checkbox berubah
    document.addEventListener('change', function(e) {
        if (e.target.closest('#bulanTargetWrap')) {
            document.getElementById('bulan_target_csv').value = csvFromChecked('bulanTargetWrap');
        }
    });

    // safety sebelum submit
    document.getElementById('formEditIsian')?.addEventListener('submit', function() {
        document.getElementById('bulan_target_csv').value = csvFromChecked('bulanTargetWrap');
    });

    // Pilihan Penanggung Jawab
    (function() {
        const modalId = '#modalEditIsian1';
        const selectId = '#edit_penanggungjawab';

        function parseCsv(raw) {
            return (raw || '')
                .split(',')
                .map(s => s.trim())
                .filter(Boolean);
        }

        function ensureSelect2() {
            const $select = $(selectId);
            if ($select.data('select2')) return; // sudah init

            $select.select2({
                placeholder: $select.data('placeholder') || 'Pilih...',
                width: '100%',
                closeOnSelect: false,
                dropdownParent: $(modalId)
            });
        }

        // Saat modal dibuka: set default pilihan dari tombol
        $(modalId).on('show.bs.modal', function(e) {
            ensureSelect2();

            const btn = e.relatedTarget;
            const raw = btn?.dataset?.penanggungjawab || '';
            const values = parseCsv(raw);

            const $select = $(selectId);
            $select.val(values).trigger('change');
        });

        // Optional: reset saat modal ditutup
        $(modalId).on('hidden.bs.modal', function() {
            const $select = $(selectId);
            if ($select.data('select2')) $select.val(null).trigger('change');
        });

        // Tombol pilih semua & kosongkan
        document.addEventListener('click', function(e) {
            if (e.target.closest('#btnPjSelectAll')) {
                ensureSelect2();
                const $select = $(selectId);
                const allValues = $select.find('option').map(function() {
                    return this.value;
                }).get();
                $select.val(allValues).trigger('change');
            }

            if (e.target.closest('#btnPjClear')) {
                ensureSelect2();
                $(selectId).val(null).trigger('change');
            }
        });
    })();
</script>

{{-- Pengisian Kedua --}}
<script>
    function setCheckedFromCsv(containerId, csv) {
        const wrap = document.getElementById(containerId);
        if (!wrap) return;

        const set = new Set(
            (csv || '')
            .split(',')
            .map(s => parseInt(String(s).trim(), 10))
            .filter(n => Number.isFinite(n))
        );

        wrap.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            const v = parseInt(cb.value, 10);
            cb.checked = set.has(v);
        });
    }

    function csvFromChecked(containerId) {
        const wrap = document.getElementById(containerId);
        if (!wrap) return '';

        return Array.from(wrap.querySelectorAll('input[type="checkbox"]:checked'))
            .map(cb => parseInt(cb.value, 10))
            .filter(n => Number.isFinite(n))
            .sort((a, b) => a - b)
            .join(',');
    }

    // Modal Kedua Muncul
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-edit-isian2');
        if (!btn) return;

        const id2 = (btn.dataset.id2 || '').trim();

        // kalau id kosong, jangan buka modal (opsional tapi aman)
        if (!id2) {
            // modal mungkin sudah kebuka karena data-bs-toggle, jadi kita tutup lagi
            const modalEl = document.getElementById('modalEditIsian2');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            alert('Data isian belum ada untuk baris ini.');
            return;
        }

        // isi field
        document.getElementById('edit_id2').value = id2;
        total_files.value = '';
        total_files2.value = '';
        // totalInput.value = "";
        const rencana_kerja2 = btn.dataset.rencana_kerja2 || '';
        const link_buktidukung = btn.dataset.link_buktidukung || '';

        const rencana_kerja2El = document.getElementById('edit_rencana_kerja2');
        if (rencana_kerja2El) rencana_kerja2El.value = rencana_kerja2;

        document.getElementById('edit_link_buktidukung').value = link_buktidukung;

        // auto-ceklis bulan// auto-ceklis bulan
        setCheckedFromCsv('bulanTargetWrap2', btn.dataset.bulanTarget2 || '');
        setCheckedFromCsv('bulanRealisasiWrap', btn.dataset.bulanRealisasi || '');

        // set hidden csv
        document.getElementById('bulan_realisasi_csv').value = csvFromChecked('bulanRealisasiWrap');

        // set action (sesuaikan path route kamu)
        const form = document.getElementById('formEditIsian2');
        form.action = `{{ url('/isian') }}/${id2}`; // PUT /isian/{id}
    });

    // update hidden saat checkbox berubah
    document.addEventListener('change', function(e) {
        if (e.target.closest('#bulanRealisasiWrap')) {
            document.getElementById('bulan_realisasi_csv').value = csvFromChecked('bulanRealisasiWrap');
        }
    });

    // safety sebelum submit
    document.getElementById('formEditIsian2')?.addEventListener('submit', function() {
        document.getElementById('bulan_realisasi_csv').value = csvFromChecked('bulanRealisasiWrap');
    });
</script>

{{-- List Data --}}
<script>
    // Variabel global untuk menyimpan instance DataTable
    let dataTableInstance = null;
    const fileModal = new bootstrap.Modal(document.getElementById('fileModal'));

    function generateData() {
        const linkInput = document.getElementById('edit_link_buktidukung').value;
        const totalInput = document.getElementById('total_files');
        const totalInput2 = document.getElementById('total_files2');
        const btnText = document.getElementById('btn-text');
        const btnLoading = document.getElementById('btn-loading');
        const tbody = document.getElementById('modal-table-body');
        const overlay = document.getElementById('loading-overlay');

        // 1. Ekstrak ID Folder dari Link
        // Regex ini mengambil string setelah 'folders/' atau 'id='
        const regex = /[-\w]{25,}/;
        const match = linkInput.match(regex);
        if (!match) {
            alert("Link Google Drive tidak valid! Pastikan formatnya benar.");
            return;
        }
        const folderId = match[0];
        // 1. TAMPILKAN OVERLAY (Blokir Layar)
        overlay.style.display = 'flex'; // Ubah dari none ke flex agar muncul di tengah
        // UI Loading State
        btnText.textContent = "Sedang Memproses...";
        btnLoading.classList.remove('d-none');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Sedang mengambil data dari Google API...</td></tr>';

        // Kosongkan total dulu
        totalInput.value = "";

        // 2. Panggil API Laravel
        fetch('/google-drive/files', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    folder_id: folderId
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Gagal mengambil data (Cek Folder ID / Izin Akses)");
                return response.json();
            })
            .then(data => {
                // Isi Input Total
                totalInput.value = data.total_count;
                totalInput2.value = data.total_count;

                // ======================================================
                // BAGIAN DATATABLES
                // ======================================================

                // A. Hancurkan DataTable lama jika ada (untuk menghindari duplikat/error)
                if (dataTableInstance) {
                    dataTableInstance.destroy();
                    dataTableInstance = null;
                }

                // B. Kosongkan Body Tabel
                const tbody = document.getElementById('modal-table-body');
                tbody.innerHTML = "";

                // C. Loop Data Baru
                data.files.forEach((file, index) => {
                    // Format Tanggal (YYYY-MM-DD agar sorting tanggal benar)
                    const dateObj = new Date(file.created_at);
                    const displayDate = dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    // Trik Sorting: Gunakan data-order untuk kolom tanggal agar urutannya benar (bukan alfabet)
                    const sortDate = file.created_at; // Format ISO string bagus untuk sorting

                    const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${file.name}</td>
                        <td>${file.folder_name}</td>
                        <td>
                            <span class="badge bg-${file.type.includes('pdf') ? 'danger' : 'info'}">
                                ${file.type.includes('pdf') ? 'PDF' : 'IMG'}
                            </span>
                        </td>
                        <td data-order="${sortDate}">${displayDate}</td>
                        <td>
                            <a href="${file.link}" target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                `;
                    tbody.innerHTML += row;
                });

                // D. Inisialisasi Ulang DataTable
                // Kita bungkus dalam setTimeout agar DOM selesai dirender dulu
                setTimeout(() => {
                    dataTableInstance = $('#tableFiles').DataTable({
                        "language": {
                            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ baris",
                            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 baris",
                            "infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                            "infoPostFix": "",
                            "thousands": ".",
                            "lengthMenu": "Tampilkan _MENU_ baris",
                            "loadingRecords": "Sedang memuat...",
                            "processing": "Sedang memproses...",
                            "search": "Cari:",
                            "zeroRecords": "Tidak ditemukan data yang sesuai",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            },
                            "aria": {
                                "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
                                "sortDescending": ": aktifkan untuk mengurutkan kolom menurun"
                            }
                        },
                        "pageLength": 10, // Default 10 baris per halaman
                        "order": [
                            [2, 'asc']
                        ] // Default urut berdasarkan kolom No (index 0)
                    });
                }, 100);

                // Tampilkan Modal
                fileModal.show();
            })
            .catch(error => {
                alert(error.message);
                console.error(error);
            })
            .finally(() => {
                // 2. SEMBUNYIKAN OVERLAY (Buka Blokir)
                // Dijalankan baik sukses maupun error
                overlay.style.display = 'none';
                // Reset Tombol
                btnText.textContent = "Generate & Lihat File";
                btnLoading.classList.add('d-none');
            });
    }
</script>

{{-- List Data 2 --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi modal atau lainnya di sini
        const fileModal = new bootstrap.Modal(document.getElementById('fileModal2'));
    });

    function generateData2(id) {
        const linkInput = id;
        const tbody = document.getElementById('modal-table-body');
        const overlay = document.getElementById('loading-overlay');

        // 1. Ekstrak ID Folder dari Link
        // Regex ini mengambil string setelah 'folders/' atau 'id='
        const regex = /[-\w]{25,}/;
        const match = linkInput.match(regex);
        if (!match) {
            alert("Link Google Drive tidak valid! Pastikan formatnya benar.");
            return;
        }
        const folderId = match[0];
        // 1. TAMPILKAN OVERLAY (Blokir Layar)
        overlay.style.display = 'flex'; // Ubah dari none ke flex agar muncul di tengah

        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Sedang mengambil data dari Google API...</td></tr>';

        // 2. Panggil API Laravel
        fetch('/google-drive/files', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    folder_id: folderId
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("Gagal mengambil data (Cek Folder ID / Izin Akses)");
                return response.json();
            })
            .then(data => {

                // ======================================================
                // BAGIAN DATATABLES
                // ======================================================

                // A. Hancurkan DataTable lama jika ada (untuk menghindari duplikat/error)
                if (dataTableInstance) {
                    dataTableInstance.destroy();
                    dataTableInstance = null;
                }

                // B. Kosongkan Body Tabel
                const tbody = document.getElementById('modal-table-body');
                tbody.innerHTML = "";

                // C. Loop Data Baru
                data.files.forEach((file, index) => {
                    // Format Tanggal (YYYY-MM-DD agar sorting tanggal benar)
                    const dateObj = new Date(file.created_at);
                    const displayDate = dateObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });

                    // Trik Sorting: Gunakan data-order untuk kolom tanggal agar urutannya benar (bukan alfabet)
                    const sortDate = file.created_at; // Format ISO string bagus untuk sorting

                    const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${file.name}</td>
                        <td>${file.folder_name}</td>
                        <td>
                            <span class="badge bg-${file.type.includes('pdf') ? 'danger' : 'info'}">
                                ${file.type.includes('pdf') ? 'PDF' : 'IMG'}
                            </span>
                        </td>
                        <td data-order="${sortDate}">${displayDate}</td>
                        <td>
                            <a href="${file.link}" target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                `;
                    tbody.innerHTML += row;
                });

                // D. Inisialisasi Ulang DataTable
                // Kita bungkus dalam setTimeout agar DOM selesai dirender dulu
                setTimeout(() => {
                    dataTableInstance = $('#tableFiles').DataTable({
                        "language": {
                            "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ baris",
                            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 baris",
                            "infoFiltered": "(disaring dari _MAX_ baris keseluruhan)",
                            "infoPostFix": "",
                            "thousands": ".",
                            "lengthMenu": "Tampilkan _MENU_ baris",
                            "loadingRecords": "Sedang memuat...",
                            "processing": "Sedang memproses...",
                            "search": "Cari:",
                            "zeroRecords": "Tidak ditemukan data yang sesuai",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            },
                            "aria": {
                                "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
                                "sortDescending": ": aktifkan untuk mengurutkan kolom menurun"
                            }
                        },
                        "pageLength": 10, // Default 10 baris per halaman
                        "order": [
                            [2, 'asc']
                        ] // Default urut berdasarkan kolom No (index 0)
                    });
                }, 100);

                // Tampilkan Modal
                fileModal.show();
            })
            .catch(error => {
                alert(error.message);
                console.error(error);
            })
            .finally(() => {
                // 2. SEMBUNYIKAN OVERLAY (Buka Blokir)
                // Dijalankan baik sukses maupun error
                overlay.style.display = 'none';
            });
    }
</script>

{{-- Pengisian Ketiga --}}
<script>
    // Modal Ketiga Muncul
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-edit-isian3');
        if (!btn) return;

        const id3 = (btn.dataset.id3 || '').trim();
        // alert(id3);

        // kalau id kosong, jangan buka modal (opsional tapi aman)
        if (!id3) {
            // modal mungkin sudah kebuka karena data-bs-toggle, jadi kita tutup lagi
            const modalEl = document.getElementById('modalEditIsian3');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            alert('Data isian belum ada untuk baris ini.');
            return;
        }

        // isi field
        document.getElementById('edit_id3').value = id3;

        const rencana_kerja3 = btn.dataset.rencana_kerja3 || '';
        const komentar_evaluator1 = btn.dataset.komentar_evaluator1 || '';

        const rencana_kerja3El = document.getElementById('edit_rencana_kerja3');
        if (rencana_kerja3El) rencana_kerja3El.value = rencana_kerja3;

        document.getElementById('edit_komentar_evaluator1').value = komentar_evaluator1;

        // set action (sesuaikan path route kamu)
        const form = document.getElementById('formEditIsian3');
        form.action = `{{ url('/isian') }}/${id3}`; // PUT /isian/{id}
    });
</script>

{{-- Pengisian Keempat --}}
<script>
    // Modal Ketiga Muncul
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.js-edit-isian4');
        if (!btn) return;

        const id4 = (btn.dataset.id4 || '').trim();
        // alert(id4);

        // kalau id kosong, jangan buka modal (opsional tapi aman)
        if (!id4) {
            // modal mungkin sudah kebuka karena data-bs-toggle, jadi kita tutup lagi
            const modalEl = document.getElementById('modalEditIsian4');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            alert('Data isian belum ada untuk baris ini.');
            return;
        }

        // isi field
        document.getElementById('edit_id4').value = id4;

        const rencana_kerja4 = btn.dataset.rencana_kerja4 || '';
        const komentar_evaluator12 = btn.dataset.komentar_evaluator12 || '';
        const komentar_operator1 = btn.dataset.komentar_operator1 || '';

        const rencana_kerja4El = document.getElementById('edit_rencana_kerja4');
        if (rencana_kerja4El) rencana_kerja4El.value = rencana_kerja4;

        document.getElementById('edit_komentar_evaluator12').value = komentar_evaluator12;
        document.getElementById('edit_komentar_operator1').value = komentar_operator1;

        // set action (sesuaikan path route kamu)
        const form = document.getElementById('formEditIsian4');
        form.action = `{{ url('/isian') }}/${id4}`; // PUT /isian/{id}
    });
</script>

{{-- Konfirmasi Finishing Dokumen --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-finish-confirm').forEach(button => {
        button.addEventListener('click', function() {
            const dataId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Finalisasi Dokumen',
                text: "Apakah kamu yakin dokumen yang ada sudah benar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Finalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-finish-${dataId}`).submit();
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const element = document.getElementById("typewriter");
        const username = @json($data['user_active'][0]->name ?? 'Pengguna');
        const fullText = `👋 Selamat Datang, ${username}..`;
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

</html>
