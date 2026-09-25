<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Konfigurasi Font & Ikon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS Internal/Lokal -->
    <link rel="stylesheet" href="{{ asset('assets-se2026/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/jazirah-lembarkerja.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">

    <!-- Library JS (Export & SweetAlert) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>
    {{-- AWAL BLOK OPTIMASI LOGIKA & DATA REUSABLE --}}
    @php
        $userActive = $data['user_active'] ?? null;
        $userRole = $userActive->jazirah ?? '';
        $userNip = $userActive->nip_pegawai ?? '';
        $username = $userActive->username ?? '';
        $userSatker = $userActive->kode_satker ?? '';

        // Definisi Hak Akses Secara Global
        $rolesEvaluator = [
            'admin',
            'sekretariat',
            'kepala',
            'kepala-kako',
            'kabag-umum',
            'kasubbag',
            'sekretariat-kako',
        ];
        $rolesValidator = ['admin', 'sekretariat'];

        $isEvaluator = in_array($userRole, $rolesEvaluator);
        $canUnvalidate = in_array($userRole, $rolesValidator);

        $opsiPegawaiHtml = '<option value="">-- Pilih Pegawai / PJK --</option>';
        if (!empty($data['pegawai_prov'])) {
            foreach ($data['pegawai_prov'] as $pegawai) {
                $opsiPegawaiHtml .= '<option value="' . $pegawai->nip_pegawai . '">' . $pegawai->name . '</option>';
            }
        }

        // Mapping seluruh pegawai berdasarkan username untuk dipakai di JavaScript
        $usersMap = [];
        if (!empty($data['all_users'])) {
            foreach ($data['all_users'] as $satker_users) {
                foreach ($satker_users as $u) {
                    $usersMap[$u->username] = $u;
                }
            }
        }
    @endphp
    {{-- AKHIR BLOK OPTIMASI --}}

    <!-- Orientasi Perangkat Peringatan -->
    <div id="orientation-warning" style="display: none;">
        <h1>Putar Perangkat Anda</h1>
        <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
        <div class="phone-wrapper">
            <div class="screen"></div>
            <div class="button"></div>
        </div>
    </div>

    <!-- Animasi Loading -->
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

            <div class="posisitengah custom-jazirah-wrapper">
                <!-- 1. MODERN FILTER SECTION -->
                <div class="j-card j-filter-card j-spacing-bottom">
                    @php
                        $selectedPilar = (string) ($data['pilar_selected'] ?? '');
                        $selectedSatker = (string) ($data['satker_selected'] ?? '');
                    @endphp

                    <form method="GET" action="{{ url()->current() }}" class="j-filter-form">
                        <div class="j-filter-left">
                            <div class="j-filter-header">
                                <div class="j-icon-box">
                                    <i class="bi bi-funnel-fill"></i>
                                </div>
                                <span class="j-filter-title">Filter Data</span>
                            </div>

                            <div class="j-filter-controls">
                                <div class="j-select-wrapper">
                                    <select name="pilar" id="select-pilar" class="form-select j-select">
                                        <option value="">Semua Pilar</option>
                                        @foreach ($data['pilars'] as $p)
                                            <option value="{{ $p }}"
                                                {{ $selectedPilar === $p ? 'selected' : '' }}>
                                                Pilar {{ $p }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="j-select-wrapper">
                                    <select name="satker" class="form-select j-select">
                                        @foreach ($data['satker'] as $s)
                                            @if ($userSatker === '1100' || $userSatker === $s->kode_satker)
                                                <option value="{{ $s->kode_satker }}"
                                                    {{ $selectedSatker === (string) $s->kode_satker ? 'selected' : '' }}>
                                                    {{ $s->nama_satker }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <!-- FITUR BARU: TOGGLE FILTER TUGAS SAYA -->
                                <div class="j-toggle-wrapper">
                                    <label class="j-modern-toggle">
                                        <input type="checkbox" name="tugas_saya" value="1"
                                            {{ ($data['tugas_saya_selected'] ?? '') == '1' ? 'checked' : '' }}>
                                        <span class="j-toggle-slider"></span>
                                        <span class="j-toggle-label"><i class="fa-solid fa-user-check me-1"></i> Tugas
                                            Saya</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="j-filter-actions">
                            <button type="button" class="btn j-btn-excel" onclick="exportToExcel()"
                                title="Download Excel">
                                <i class="fa-solid fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn j-btn-pdf" onclick="exportToPDF()" title="Download PDF">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </button>
                            <div class="d-none d-md-block"
                                style="width: 2px; height: 30px; background: #e2e8f0; margin: 0 5px;"></div>

                            <button class="btn j-btn-primary" type="submit">
                                <i class="fa-solid fa-circle-check"></i>&nbsp; Terapkan
                            </button>
                            @if ($selectedPilar !== '' || $selectedSatker !== '')
                                <a class="btn j-btn-light" href="{{ url()->current() }}">
                                    <i class="fa-solid fa-rotate-left"></i>&nbsp; Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- 2. MODERN TABLE SECTION -->
                <div class="j-card j-table-card j-premium-shadow">
                    <div class="table-responsive j-scrollable">
                        <table class="table align-middle w-100 m-0 j-modern-table" id="jazirahTable">
                            <thead>
                                <tr>
                                    <th width="8%" class="text-center">Aksi</th>
                                    <th width="18%">Rencana Kerja</th>
                                    <th width="18%">Rencana Aksi</th>
                                    <th width="18%">Output</th>
                                    <th width="17%" class="text-center">P. Jawab</th>
                                    <th width="9%" class="text-center">Target</th>
                                    <th width="12%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['indikator'] as $row)
                                    @php
                                        $lvl = max(1, min(5, (int) ($row->level ?? 1)));
                                        $text = $row->rencana_kerja ?? '-';
                                        $statusDoc = $row->isian->status_dokumen ?? '0';

                                        // ... (Biarkan semua deklarasi variabel PHP bawaan Anda tetap ada di sini) ...
                                        $canComment = false;
                                        $canValidate = false;
                                        $canEdit = false;
                                        $chatData = '[]';
                                        $chatBase64 = base64_encode($chatData);
                                        $array_pj = [];

                                        if ($row->pengisian === 1 && !empty($row->isian)) {
                                            if ($row->isian->komentars) {
                                                $chatData = $row->isian->komentars->toJson();
                                                $chatBase64 = base64_encode($chatData);
                                            }

                                            $rawPj = $row->isian->penanggungjawab ?? '';
                                            $array_pj = $rawPj !== '' ? array_map('trim', explode(',', $rawPj)) : [];

                                            $rawCreator = $row->isian->created_by_3 ?? '';
                                            $array_creator =
                                                $rawCreator !== '' ? array_map('trim', explode(',', $rawCreator)) : [];

                                            $isPj = in_array($username, $array_pj);
                                            $isCreator = in_array($username, $array_creator);

                                            $canComment = $isEvaluator || $isPj || $isCreator;
                                            $canValidate = $userRole === 'admin' || $isCreator;
                                            $canEdit = $isEvaluator || $isPj;
                                        }
                                    @endphp

                                    <tr class="j-row-lvl-{{ $lvl }}" id="row-{{ $row->isian->id ?? '' }}">
                                        <!-- KOLOM AKSI -->
                                        <td class="text-center text-nowrap">
                                            @if ($row->pengisian === 1)
                                                {{-- Tombol Detail --}}
                                                <button type="button" class="btn-mata-modern"
                                                    title="Lihat Detail Isian" onclick="bukaModalKustom(this)"
                                                    data-can_comment="{{ $canComment ? '1' : '0' }}"
                                                    data-can_validate="{{ $canValidate ? '1' : '0' }}"
                                                    data-id_isian="{{ $row->isian->id ?? '' }}"
                                                    data-kode_1="{{ $row->kode_1 ?? '' }}"
                                                    data-kode_2="{{ $row->kode_2 ?? '' }}"
                                                    data-kode_3="{{ $row->kode_3 ?? '' }}"
                                                    data-kode_4="{{ $row->kode_4 ?? '' }}"
                                                    data-kode_5="{{ $row->kode_5 ?? '' }}"
                                                    data-rencana_kerja="{{ $row->rencana_kerja ?? '' }}"
                                                    data-dokumen_ped="{{ $row->pedoman ?? '' }}"
                                                    data-contoh_link_ped="{{ $row->contoh_link ?? '' }}"
                                                    data-link_lainnya="{{ $row->isian->link_lainnya ?? '' }}"
                                                    data-rencanaaksi="{{ $row->isian->rencanaaksi ?? '' }}"
                                                    data-output="{{ $row->isian->output ?? '' }}"
                                                    data-penanggungjawab="{{ $row->isian->penanggungjawab ?? '' }}"
                                                    data-created_by_3="{{ $row->isian->created_by_3 ?? '' }}"
                                                    data-bulan-target="{{ $row->isian->bulan_target ?? '' }}"
                                                    data-bulan_realisasi="{{ $row->isian->bulan_realisasi ?? '' }}"
                                                    data-link_buktidukung="{{ $row->isian->link_buktidukung ?? '' }}"
                                                    data-status_dokumen="{{ $statusDoc }}"
                                                    data-komentars="{{ $chatBase64 }}"
                                                    data-cb1="{{ $row->isian->created_by_1 ?? '' }}"
                                                    data-ca1="{{ $row->isian->created_at_1 ?? '' }}"
                                                    data-cb2="{{ $row->isian->created_by_2 ?? '' }}"
                                                    data-ca2="{{ $row->isian->created_at_2 ?? '' }}"
                                                    data-cb3="{{ $row->isian->created_by_3 ?? '' }}"
                                                    data-ca3="{{ $row->isian->created_at_3 ?? '' }}"
                                                    data-cb4="{{ $row->isian->created_by_4 ?? '' }}"
                                                    data-ca4="{{ $row->isian->created_at_4 ?? '' }}"
                                                    data-cb5="{{ $row->isian->created_by_5 ?? '' }}"
                                                    data-ca5="{{ $row->isian->created_at_5 ?? '' }}">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                {{-- Tombol Edit --}}
                                                @if ($canEdit && $statusDoc !== '5')
                                                    <button type="button" class="btn-edit-modern"
                                                        title="Edit Data Isian" onclick="bukaModalEdit(this)"
                                                        data-id_isian="{{ $row->isian->id ?? '' }}"
                                                        data-rencana_kerja="{{ $row->rencana_kerja ?? '' }}"
                                                        data-rencanaaksi="{{ $row->isian->rencanaaksi ?? '' }}"
                                                        data-output="{{ $row->isian->output ?? '' }}"
                                                        data-rencanaaksi_lalu="{{ $row->isian->rencanaaksi_tahun_lalu ?? '' }}"
                                                        data-output_lalu="{{ $row->isian->output_tahun_lalu ?? '' }}"
                                                        data-penanggungjawab="{{ $row->isian->penanggungjawab ?? '' }}"
                                                        data-bulan-target="{{ $row->isian->bulan_target ?? '' }}"
                                                        data-bulan_realisasi="{{ $row->isian->bulan_realisasi ?? '' }}"
                                                        data-link_buktidukung="{{ $row->isian->link_buktidukung ?? '' }}"
                                                        data-status_dokumen="{{ $statusDoc }}"
                                                        data-kode_1="{{ $row->kode_1 ?? '' }}"
                                                        data-kode_2="{{ $row->kode_2 ?? '' }}"
                                                        data-kode_3="{{ $row->kode_3 ?? '' }}"
                                                        data-kode_4="{{ $row->kode_4 ?? '' }}"
                                                        data-kode_5="{{ $row->kode_5 ?? '' }}">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </button>
                                                @endif

                                                {{-- Tombol Batal Validasi --}}
                                                @if ($canUnvalidate && $statusDoc === '5')
                                                    <button type="button" class="btn-edit-modern"
                                                        style="background-color: #fee2e2; color: #dc2626;"
                                                        title="Batalkan Validasi Dokumen"
                                                        onclick="batalValidasi(this)"
                                                        data-id_isian="{{ $row->isian->id ?? '' }}">
                                                        <i class="fa-solid fa-rotate-left"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>

                                        <!-- KOLOM RENCANA KERJA -->
                                        <td>
                                            <div class="j-indent j-indent-lvl-{{ $lvl }}">
                                                <span class="j-text-hierarchy">{{ $text }}</span>
                                            </div>
                                        </td>

                                        <!-- KOLOM RENCANA AKSI & OUTPUT -->
                                        <td>
                                            <div class="j-text-truncate">
                                                {{ $row->pengisian === 1 ? $row->isian->rencanaaksi ?? '' : '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="j-text-truncate">
                                                {{ $row->pengisian === 1 ? $row->isian->output ?? '' : '' }}
                                            </div>
                                        </td>

                                        <!-- KOLOM P. JAWAB -->
                                        <td class="text-center">
                                            @if ($row->pengisian === 1 && !empty($array_pj))
                                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                                    @foreach ($array_pj as $pj)
                                                        @if (!empty($pj))
                                                            @php
                                                                $displayName = $usersMap[$pj]->name ?? $pj;
                                                                $isMeClass = $username === $pj ? 'ada-data' : '';
                                                            @endphp
                                                            <button type="button"
                                                                class="btn-profil-pjk {{ $isMeClass }}"
                                                                onclick="showProfilPegawai('{{ $pj }}')">
                                                                <i class="bi bi-person-circle"></i>
                                                                {{ $displayName }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        <!-- KOLOM TARGET -->
                                        <td class="text-center">
                                            @if ($row->pengisian === 1)
                                                <span
                                                    class="j-target-text">{{ $row->isian->bulan_target_nama ?? '-' }}</span>
                                            @endif
                                        </td>

                                        <!-- KOLOM STATUS -->
                                        <td class="text-center" id="status-container-{{ $row->isian->id ?? '' }}">
                                            @if ($row->pengisian === 1)
                                                @php
                                                    // Ambil bulan berjalan saat ini (1 = Januari, 12 = Desember)
                                                    $currentMonth = (int) date('n');
                                                    $targetBulan = $row->isian->bulan_target ?? '';
                                                    $arrTarget = $targetBulan !== '' ? explode(',', $targetBulan) : [];

                                                    // Cek apakah ada target dari Januari s.d Bulan Berjalan
                                                    $adaTargetBerjalan = false;
                                                    foreach ($arrTarget as $tb) {
                                                        if ((int) trim($tb) <= $currentMonth) {
                                                            $adaTargetBerjalan = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                @if ($statusDoc === '0' || $statusDoc === '')
                                                    <span class="badge j-badge j-bg-gray"><i
                                                            class="bi bi-dash-circle-fill me-1"></i>Perlu Menetapkan
                                                        Target (Penanggung Jawab)</span>
                                                @elseif (!$adaTargetBerjalan)
                                                    <span class="badge j-badge j-bg-gray"><i
                                                            class="bi bi-calendar-x me-1"></i>Belum Ada Target Jatuh
                                                        Tempo</span>
                                                @else
                                                    @if ($statusDoc === '1')
                                                        <span class="badge j-badge j-bg-orange"><i
                                                                class="bi bi-bullseye me-1"></i>Perlu Realisasi
                                                            (Penanggung Jawab)</span>
                                                    @elseif ($statusDoc === '2')
                                                        <span class="badge j-badge j-bg-blue"><i
                                                                class="bi bi-search me-1"></i>Perlu Evaluasi (Tim
                                                            Evaluator)</span>
                                                    @elseif ($statusDoc === '3')
                                                        <span class="badge j-badge j-bg-red"><i
                                                                class="bi bi-exclamation-circle-fill me-1"></i>Perlu
                                                            Ditindaklanjuti (Penanggung Jawab)</span>
                                                    @elseif ($statusDoc === '4')
                                                        <span class="badge j-badge j-bg-yellow"><i
                                                                class="bi bi-search me-1"></i>Perlu Evaluasi (Tim
                                                            Evaluator)</span>
                                                    @elseif ($statusDoc === '5')
                                                        <span class="badge j-badge j-bg-green"><i
                                                                class="bi bi-check-circle-fill me-1"></i>Dokumen Sudah
                                                            Validasi</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <!-- TAMPILAN KETIKA DATA KOSONG (EMPTY STATE) -->
                                    <tr>
                                        <td colspan="7" class="text-center j-empty-state-cell">
                                            <div class="j-empty-state-wrapper">
                                                <div class="j-empty-icon">
                                                    <i class="fa-solid fa-clipboard-list"></i>
                                                </div>
                                                <h5 class="j-empty-title">Belum Ada Data Tugas</h5>
                                                <p class="j-empty-desc">
                                                    Tidak ada dokumen atau rencana kerja yang sesuai dengan filter
                                                    pencarian Anda saat ini.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            <footer>
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp; Tim Pengolahan dan TI - BPS Provinsi Aceh</p>
            </footer>
        </div>
    </div>

    <!-- STRUKTUR MODAL KUSTOM BARU -->
    <div id="modalDetailKustom" class="kustom-modal-overlay">
        <div class="kustom-modal-container">
            <div class="kustom-modal-header">
                <h5><i class="fa-solid fa-circle-info me-2"></i>&nbsp; Detail Informasi LKE</h5>
                <button type="button" class="kustom-btn-tutup-x" onclick="tutupModalKustom()">&times;</button>
            </div>
            <div class="kustom-modal-body">

                <!-- ROW 1: TIMELINE CHART -->
                <div class="kustom-card">
                    <h6 class="kustom-card-title title-ungu">
                        <i class="fa-solid fa-timeline"></i> Status Rekam Jejak Dokumen
                    </h6>
                    <div class="timeline-wrapper">
                        <div class="timeline-bg-line"></div>
                        <div class="timeline-progress-line" id="timeline-progress" style="width: 0%;"></div>

                        <div class="timeline-step" id="step-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-html="true" title="Belum ada data">
                            <div class="timeline-circle"><i class="fa-solid fa-pen-to-square"></i></div>
                            <div class="timeline-label">Target<br>Ditetapkan</div>
                        </div>
                        <div class="timeline-step" id="step-2" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-html="true" title="Belum ada data">
                            <div class="timeline-circle"><i class="fa-solid fa-upload"></i></div>
                            <div class="timeline-label">Pengisian<br>Bukti</div>
                        </div>
                        <div class="timeline-step" id="step-3" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-html="true" title="Belum ada data">
                            <div class="timeline-circle"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <div class="timeline-label">Evaluasi<br>& Revisi</div>
                        </div>
                        <div class="timeline-step" id="step-4" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-html="true" title="Belum ada data">
                            <div class="timeline-circle"><i class="fa-solid fa-wrench"></i></div>
                            <div class="timeline-label">Tindak Lanjut</div>
                        </div>
                        <div class="timeline-step" id="step-5" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-html="true" title="Belum ada data">
                            <div class="timeline-circle"><i class="fa-solid fa-check-double"></i></div>
                            <div class="timeline-label">Selesai</div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: INFORMASI UMUM & TARGET/REALISASI -->
                <div class="kustom-grid-2">
                    <div class="kustom-card">
                        <h6 class="kustom-card-title title-biru">
                            <i class="fa-solid fa-circle-info"></i> Informasi Umum
                        </h6>
                        <table class="kustom-table-info">
                            <tbody>
                                <tr>
                                    <td class="td-label">Kode Indikator</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-kode_gabungan"
                                        style="font-family: monospace; font-weight: 600;">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Rencana Kerja</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-rencana_kerja">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Dokumen Pedoman</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-dokumen_ped">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Contoh Link</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value"><a href="#" id="k-contoh_link_ped" target="_blank"
                                            style="text-decoration:none; color:#0284c7; font-weight:bold;">-</a></td>
                                </tr>
                                <tr>
                                    <td class="td-label">Link Lainnya</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value"><a href="#" id="k-link_lainnya" target="_blank"
                                            style="text-decoration:none; color:#0284c7; font-weight:bold;">-</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="kustom-card">
                        <h6 class="kustom-card-title title-orange">
                            <i class="fa-solid fa-bullseye"></i> Detail Aksi & Target
                        </h6>
                        <table class="kustom-table-info">
                            <tbody>
                                <tr>
                                    <td class="td-label">Penanggung Jawab</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value">
                                        <div id="k-penanggungjawab-container" class="d-flex flex-wrap gap-1"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-label">Rencana Aksi</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-rencanaaksi">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Output</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-output">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Bulan Target</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-bulan_target" style="font-weight:600;">-</td>
                                </tr>
                                <tr>
                                    <td class="td-label">Bulan Realisasi</td>
                                    <td class="td-titik">:</td>
                                    <td class="td-value" id="k-bulan_realisasi"
                                        style="font-weight:600; color:#059669;">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ROW 3: BUKTI DUKUNG & CHAT -->
                <div class="kustom-grid-2">
                    <div class="kustom-card mb-0">
                        <h6 class="kustom-card-title title-hijau" style="justify-content: space-between;">
                            <div style="display: flex; align-items: center;">
                                <i class="fa-solid fa-file-pdf"></i> Pratinjau Bukti Dukung
                            </div>
                            <a href="#" id="k-btn-direct-bukti" target="_blank"
                                class="btn btn-sm btn-outline-success"
                                style="font-size:0.75rem; display:none; border-radius: 6px;">
                                <i class="fa-solid fa-arrow-up-right-from-square"
                                    style="width:auto; height:auto; background:none; box-shadow:none; color:inherit; margin:0; font-size:inherit;"></i>
                            </a>
                        </h6>
                        <div class="iframe-container" id="k-iframe-wrapper">
                            <div id="k-iframe-fallback" style="color:#64748b; font-size:0.9rem;">
                                <i class="fa-regular fa-folder-open mb-2 text-muted"
                                    style="font-size:2rem; display:block; text-align:center;"></i>
                                Belum ada lampiran bukti dukung.
                            </div>
                            <iframe id="k-iframe-bukti" src="" style="display:none;"></iframe>
                        </div>
                    </div>

                    <div class="kustom-card mb-0 d-flex flex-column"
                        style="padding: 0; overflow: hidden; border: 1px solid #e2e8f0;">
                        <div class="wa-header">
                            <i class="fa-brands fa-whatsapp"></i> Ruang Diskusi
                        </div>
                        <div class="wa-chat-container" id="k-chat-messages">
                            <!-- Chat dirender via JS -->
                        </div>
                        <div class="wa-input-area">
                            <input type="text" id="k-chat-input" class="wa-input-field"
                                placeholder="Ketik pesan..." autocomplete="off">
                            <button class="wa-btn-send" type="button" id="btn-kirim-pesan" title="Kirim Pesan">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kustom-modal-footer d-flex justify-content-end gap-2"
                style="background-color: #f8fafc; padding: 12px 24px; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn-simpan-kustom" id="btn-validasi-dokumen"
                    style="display: none; background-color: #059669; border-color: #059669;">
                    <i class="fa-solid fa-check-double"></i> Sahkan & Validasi
                </button>
                <button type="button" class="btn-batal-kustom" onclick="tutupModalKustom()">Tutup Jendela</button>
            </div>
        </div>
    </div>
    <!-- AKHIR MODAL KUSTOM -->

    {{-- Modal Profil Pegawai --}}
    <div id="modalProfilPegawai" class="modal-overlay" style="display: none; z-index: 999999;">
        <div class="modal-content modal-profil">
            <div class="modal-header header-orange">
                <h3 class="modal-title">Profil Pegawai</h3>
                <span class="tutup-modal" onclick="tutupModalProfilPegawai()">&times;</span>
            </div>
            <div class="modal-body text-center">
                <h4 id="profil_nama" class="nama-pegawai">-</h4>
                <p class="nip-pegawai">NIP. <span id="profil_nip">-</span></p>

                <div class="detail-profil">
                    <div class="info-item">
                        <div class="ikon-box"><i class="fa-solid fa-briefcase"></i></div>
                        <div class="info-teks">
                            <small>JABATAN</small>
                            <span id="profil_jabatan">-</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ikon-box"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="info-teks">
                            <small>GOLONGAN</small>
                            <span id="profil_golongan">-</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="ikon-box"><i class="fa-solid fa-building"></i></div>
                        <div class="info-teks">
                            <small>KODE SATKER</small>
                            <span id="profil_satker">-</span>
                        </div>
                    </div>
                    <div class="info-item border-0 pb-0">
                        <div class="ikon-box"><i class="fab fa-whatsapp"
                                style="color: #25D366; font-size: 1.4rem;"></i></div>
                        <div class="info-teks">
                            <small>WHATSAPP</small>
                            <a id="profil_wa_link" href="#" target="_blank" class="wa-kapsul">
                                <span id="profil_no_hp">-</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AWAL MODAL EDIT KUSTOM -->
    <div id="modalEditKustom" class="kustom-modal-overlay">
        <div class="kustom-modal-container">
            <div class="kustom-modal-header">
                <h5><i class="fa-solid fa-pen-to-square me-2"></i>&nbsp; Edit Informasi LKE</h5>
                <button type="button" class="kustom-btn-tutup-x" onclick="tutupModalEdit()">&times;</button>
            </div>
            <form action="{{ route('lke.update_isian') }}" method="POST" id="formEditKustom">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_isian" id="e-id_isian">
                <input type="hidden" name="bulan_target" id="e-bulan_target-hidden">
                <input type="hidden" name="bulan_realisasi" id="e-bulan_realisasi-hidden">
                <input type="hidden" name="penanggungjawab" id="e-penanggungjawab-hidden">
                <input type="hidden" name="status_dokumen" id="e-status_dokumen">

                <div class="kustom-modal-body">
                    <div class="kustom-card mb-4" style="background-color: #f8fafc; padding: 15px;">
                        <div class="d-flex flex-column gap-2" style="font-size: 0.9rem;">
                            <div>
                                <strong class="text-secondary">Kode Indikator:</strong>
                                <span id="e-kode_gabungan" class="badge bg-white text-dark border ms-1"
                                    style="font-family: monospace; font-size: 0.85rem;">-</span>
                            </div>
                            <div>
                                <strong class="text-secondary">Rencana Kerja:</strong>
                                <span id="e-rencana_kerja" class="ms-1"
                                    style="color: #1e293b; font-weight: 600;">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="kustom-grid-2">
                        <!-- KOLOM KIRI -->
                        <div class="d-flex flex-column gap-3">
                            <div class="kustom-form-group mb-0 pj-wrapper">
                                <label>Penanggung Jawab</label>
                                <div class="pj-tag-container" id="pj-tag-container"
                                    onclick="document.getElementById('pj-search-input').focus()">
                                    <input type="text" id="pj-search-input" class="pj-search-input"
                                        placeholder="Cari nama pegawai..." autocomplete="off">
                                </div>
                                <div class="pj-dropdown" id="pj-dropdown"></div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">*Ketik nama
                                    pegawai, lalu klik untuk menambahkan.</small>
                            </div>

                            <div class="kustom-form-group mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="e-rencanaaksi" class="mb-0">Rencana Aksi &nbsp;
                                        <button type="button" class="btn-copy-lalu" id="btn-copy-rencanaaksi"
                                            title="Salin data tahun lalu">
                                            <i class="fa-regular fa-copy"></i> Salin Tahun Lalu
                                        </button>
                                    </label>
                                </div>
                                <textarea name="rencanaaksi" id="e-rencanaaksi" class="kustom-form-control" rows="6"
                                    placeholder="Masukkan Rencana Aksi..."></textarea>
                            </div>

                            <div class="kustom-form-group mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="e-output" class="mb-0">Output &nbsp;
                                        <button type="button" class="btn-copy-lalu" id="btn-copy-output"
                                            title="Salin data tahun lalu">
                                            <i class="fa-regular fa-copy"></i> Salin Tahun Lalu
                                        </button>
                                    </label>
                                </div>
                                <textarea name="output" id="e-output" class="kustom-form-control" rows="6"
                                    placeholder="Masukkan Output..."></textarea>
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="d-flex flex-column gap-3">
                            <div class="kustom-form-group mb-0">
                                <label>Bulan Target</label>
                                <div class="modern-month-grid">
                                    @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $namaBulan)
                                        <label class="modern-month-check">
                                            <input type="checkbox" class="e-bt-checkbox"
                                                value="{{ $index + 1 }}">
                                            <span class="modern-month-label">{{ $namaBulan }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="kustom-form-group mb-0">
                                <label>Bulan Realisasi</label>
                                <div class="modern-month-grid">
                                    @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'] as $index => $namaBulan)
                                        <label class="modern-month-check">
                                            <input type="checkbox" class="e-br-checkbox"
                                                value="{{ $index + 1 }}">
                                            <span class="modern-month-label">{{ $namaBulan }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="kustom-form-group mb-0">
                                <label for="e-link_buktidukung">Link Bukti Dukung (Google Drive dll)</label>
                                <input type="url" name="link_buktidukung" id="e-link_buktidukung"
                                    class="kustom-form-control" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kustom-modal-footer d-flex justify-content-end gap-2"
                    style="background-color: #f8fafc; padding: 16px 24px;">
                    <button type="button" class="btn-batal-kustom" onclick="tutupModalEdit()">Batal</button>
                    <button type="submit" class="btn-simpan-kustom"><i class="fa-solid fa-save"></i> Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- AKHIR MODAL EDIT KUSTOM -->

    {{-- Script Komunikasi Bridge JS-PHP --}}
    <script>
        window.BupestaConfig = {
            userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}",
            successMessage: "{{ session('success') }}",
            opsiPegawaiHtml: `{!! $opsiPegawaiHtml !!}`
        };
        window.userActiveNip = "{{ $userNip }}";
        window.BupestaUsersMap = @json($usersMap);
        window.SatkerUsersAktif = @json($data['all_users'][$data['satker_selected']] ?? []);
    </script>

    <!-- Library JS Bootstrap (Dibersihkan dari pemanggilan duplikat) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS Lokal -->
    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-jazirah/style/jazirah-lembarkerja.js') }}"></script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true
                });

                @if (session('updated_id'))
                    let updatedRowId = "row-{{ session('updated_id') }}";
                    let rowElement = document.getElementById(updatedRowId);

                    if (rowElement) {
                        rowElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        let originalBg = rowElement.style.backgroundColor;
                        rowElement.style.transition = "background-color 1.5s ease";
                        rowElement.style.backgroundColor = "#d1fae5";

                        setTimeout(() => {
                            rowElement.style.backgroundColor = originalBg;
                            setTimeout(() => {
                                rowElement.style.transition = "";
                            }, 1500);
                        }, 3000);
                    }
                @endif
            });
        </script>
    @endif

    <!-- Script Inisialisasi Tooltip & Update Hover -->
    <script>
        // Inisialisasi Bootstrap Tooltip secara global
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>
