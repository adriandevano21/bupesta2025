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
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/jazirah.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .embed-responsive {
            position: relative;
            padding-top: 56.25%;
        }

        .embed-responsive iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>

    <!-- DataTables + FixedColumns CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    <style>
        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap
        }

        .toolbar label {
            font-weight: 600;
            color: #374151
        }

        #info {
            margin: 4px 0 10px;
            color: #4b5563
        }

        .wrap {
            max-width: 100%;
            overflow: auto
        }

        /* Select2: sederhana & rapi */
        .tri-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .tri-wrap:focus-within {
            outline: 2px solid #93c5fd;
            outline-offset: 2px
        }

        .tri-wrap .select2-container {
            min-width: 240px;
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827;
            font-weight: 700;
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }

        /* Tint wrapper sesuai triwulan (diubah via JS) */
        .tri-wrap.tri-1 {
            background: #dcfce7;
            border-color: #86efac
        }

        .tri-wrap.tri-2 {
            background: #e0f2fe;
            border-color: #93c5fd
        }

        .tri-wrap.tri-3 {
            background: #ffedd5;
            border-color: #fdba74
        }

        a .tri-wrap.tri-4 {
            background: #efe9ff;
            border-color: #c4b5fd
        }

        /* Tabel simple + warna lembut */
        table.dataTable thead th {
            background: linear-gradient(90deg, #e8f8e8, #e8f0ff);
            color: #1f2937;
            font-weight: 700;
            text-align: center;
        }

        table.dataTable td,
        table.dataTable th {
            font-size: 11px;
            white-space: nowrap;
            text-align: center;
            padding: 8px 10px !important;
        }

        .col-indikator {
            background: #fff8e6;
            font-weight: 700;
            border-left: 3px solid #60a5fa !important
        }

        .col-pilar {
            background: #f3faf3;
            font-weight: 700
        }

        .cell-num {
            font-weight: 700
        }

        .cell-empty {
            background: #ffe5e5 !important;
            color: #7f1d1d !important;
            font-weight: 700
        }

        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0
        }

        table.dataTable tbody td {
            border-right: 1px solid #f0f0f0
        }

        table.dataTable tbody tr:nth-child(odd) {
            background: #fafafa
        }

        /* Samakan lebar semua kolom kode_satker (th & td) */
        #tbl th.satker-col,
        #tbl td.satker-col {
            width: 50px;
            min-width: 50px;
            max-width: 50px;
        }
    </style>

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
            @include('layout2.navbar-jazirah')
        </header>

        <div class="konten">
            <!-- <img style="width:100%;" src="fitral/img/gedung1.jpg"> -->
            <?php include 'fitral/php/animasitextbps.php'; ?>

            <br>

            <div class="posisitengah">

                @php
                    $tahun = (int) request('tahun', 2025); // default 2025
                @endphp

                <div class="mt-2">
                    @if ($tahun === 2025)
                        <main class="grid h-scroll">

                            <a class="link-card" target="_blank"
                                href="https://us02web.zoom.us/j/89332701548?pwd=bROlzcdevFoiaRb4UX1Tw1GQ3Z1hPy.1
"
                                aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="3" y="6" width="14" height="12" rx="3" />
                                        <path d="M15 9l6-3v12l-6-3z" />
                                    </svg>
                                </div>
                                <div class="label">Seulanga</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank" href="/form-jazirah" aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="3" y="3" width="12" height="18" rx="2" />
                                        <rect x="5.2" y="6" width="3.2" height="3.2" />
                                        <rect x="9.6" y="6" width="3.2" height="3.2" />
                                        <rect x="5.2" y="10.4" width="3.2" height="3.2" />
                                        <rect x="9.6" y="10.4" width="3.2" height="3.2" />
                                        <path d="M14.5 13.8l4-4 2.7 2.7-4 4-2.8.6z" />
                                    </svg>
                                </div>
                                <div class="label">Pengisian Matriks Aksi</div>
                                <div class="hint"></div>
                            </a>

                            <!-- Ganti href sesuai kebutuhan Anda -->
                            <a class="link-card" target="_blank"
                                href="https://sites.google.com/view/pedomanevaluazi2025/pemenuhan"
                                aria-label="Pengguna">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="5" y="3" width="13" height="18" rx="2" />
                                        <rect x="4" y="3" width="2" height="18" />
                                        <path d="M12 3v7l2-1 2 1V3z" />
                                    </svg>
                                </div>
                                <div class="label">Pedoman ZI</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank" href="https://s.bps.go.id/11sop_aceh"
                                aria-label="Pengguna">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <title>SOP</title>
                                        <!-- Dokumen -->
                                        <rect x="4" y="3" width="12" height="16" rx="2" />
                                        <rect x="6.5" y="6" width="7.5" height="1.8" />
                                        <rect x="6.5" y="9.5" width="7.5" height="1.8" />
                                        <rect x="6.5" y="13" width="5.5" height="1.8" />
                                        <!-- “Gear/Nut” sederhana -->
                                        <path d="M18 12.5l2.4 1.4v2.8L18 18.1l-2.4-1.4v-2.8z" />
                                        <circle cx="18" cy="15.3" r="1.1" fill="#fff" />
                                    </svg>
                                </div>
                                <div class="label">SOP</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank"
                                href="https://drive.google.com/drive/folders/1842s9o3Y22mhLvFEOUHM1-ErO55d_LSx"
                                aria-label="Booking">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="4" y="3" width="12" height="16" rx="2" />
                                        <rect x="6" y="6" width="8" height="2" />
                                        <rect x="6" y="10" width="6" height="2" />
                                        <circle cx="17.5" cy="17.5" r="3.5" />
                                        <path d="M16.1 17.5l1.1 1.1 2-2-1-1z" fill="#fff" />
                                    </svg>
                                </div>
                                <div class="label">LHE TPP ZI 2024</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank"
                                href="https://drive.google.com/drive/folders/158hgrrwQuDhxpDE4MWMV6MckdX0mYAoQ?usp=sharing"
                                aria-label="Booking">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <path d="M8 3h8v3H8z" />
                                        <rect x="5" y="5" width="14" height="16" rx="2" />
                                        <rect x="7" y="9" width="2" height="2" />
                                        <rect x="10.5" y="9" width="6.5" height="2" />
                                        <rect x="7" y="13" width="2" height="2" />
                                        <rect x="10.5" y="13" width="5" height="2" />
                                        <rect x="7" y="17" width="2" height="2" />
                                        <rect x="10.5" y="17" width="4" height="2" />
                                    </svg>
                                </div>
                                <div class="label">LKE Satker 2024</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank"
                                href="https://drive.google.com/drive/folders/1CgaY-FdPXj3hZe38YvtOxqnyrZd0lvnE?usp=sharing"
                                aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <rect x="3" y="8" width="18" height="2" />
                                        <rect x="7" y="3" width="2" height="4" />
                                        <rect x="15" y="3" width="2" height="4" />
                                        <path d="M15 16l-1.2.7.3-1.3L13 14.5l1.4-.1L15 13l.6 1.4 1.4.1-1.1.9.3 1.3z" />
                                    </svg>
                                </div>
                                <div class="label">Event Jazirah</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank"
                                href="https://drive.google.com/drive/folders/1IyTMgAG8jUhOKPRzgchftq6RnPPhLkbm?usp=sharing"
                                aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <path d="M12 2l7 4v6c0 5-4.2 8-7 10-2.8-2-7-5-7-10V6z" />
                                        <path d="M9 12l2 2 4-4 1.6 1.6-5.6 5.6L7.4 13.6z" fill="#fff" />
                                    </svg>
                                </div>
                                <div class="label">Satker Lolos TPI</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" target="_blank" href="/qna-jazirah" aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <path d="M4 4h10a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H9l-3 3V6a2 2 0 0 1 2-2z" />
                                        <path d="M10 13h6a2 2 0 0 1 2 2v5l-3-3h-5a2 2 0 0 1-2-2v-1" />
                                        <path
                                            d="M9.8 7.8a2.2 2.2 0 1 1 3.7 1.6c-.5.4-1 .7-1 .9v.7h-1.6v-.9c0-.9.7-1.4 1.2-1.8.3-.2.5-.5.5-.8a.6.6 0 0 0-.6-.6c-.4 0-.6.2-.9.4z"
                                            fill="#fff" />
                                        <rect x="11" y="12.7" width="1.6" height="1.6" rx=".3"
                                            fill="#fff" />
                                    </svg>
                                </div>
                                <div class="label">QNA</div>
                                <div class="hint"></div>
                            </a>

                            <a class="link-card" href="/narahubung-jazirah" aria-label="Kamar">
                                <span class="go" aria-hidden="true"><svg viewBox="0 0 24 24">
                                        <path d="M12 4l1.41 1.41L8.83 10H20v2H8.83l4.58 4.59L12 18l-8-8 8-8z" />
                                    </svg></span>
                                <div class="icon-xl">
                                    <svg viewBox="0 0 24 24" role="img">
                                        <rect x="5" y="3" width="14" height="18" rx="2" />
                                        <rect x="3" y="5" width="2" height="3" />
                                        <rect x="3" y="10.5" width="2" height="3" />
                                        <rect x="3" y="16" width="2" height="3" />
                                        <circle cx="12" cy="10" r="3" fill="#fff" />
                                        <path d="M8.5 16.5a4.5 4.5 0 0 1 7 0V19H8.5z" fill="#fff" />
                                    </svg>
                                </div>
                                <div class="label">Narahubung</div>
                                <div class="hint"></div>
                            </a>

                        </main>

                        <br>

                        <div class="card shadow-sm">
                            <div class="card-body p-0">
                                <div class="toolbar">
                                    <label for="triSelect">Data:</label>
                                    <div id="triWrap" class="tri-wrap tri-1">
                                        <select id="triSelect">
                                            <option value="1" selected>Pengisian Link</option>
                                            {{-- <option value="2">Triwulan 2</option> --}}
                                            {{-- <option value="3">Triwulan 3</option> --}}
                                            <option value="4">Target - Realisasi</option>
                                            <option value="5">Progress Evaluasi</option>
                                            {{-- <option value="6">Evaluasi Tr 2</option> --}}
                                            {{-- <option value="7">Evaluasi Tr 3</option> --}}
                                            {{-- <option value="8">Progress Evaluasi</option> --}}
                                            <option value="9">Validasi Dokumen</option>
                                            {{-- <option value="10">Tindak Lanjut Tr 2</option> --}}
                                            {{-- <option value="11">Tindak Lanjut Tr 3</option> --}}
                                            {{-- <option value="12">Progress Tindak Lanjut</option> --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="wrap">
                                    <div id="info">Memuat data…</div>
                                    <table id="tbl" class="display nowrap compact" style="width:100%">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- jQuery -->
                                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

                                <!-- DataTables + FixedColumns JS -->
                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

                                <!-- Select2 JS -->
                                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                                <script>
                                    const ENDPOINT =
                                        "https://script.google.com/macros/s/AKfycbxXE6T-yd5VWK1mXE69bCzw0FCqyuri2X7doO2NHQWu6_kgu4yqnMx-nok0mhxXxvmAfg/exec";

                                    /* 24 kolom kode_satker */
                                    const ALL_SATKERS = [
                                        "1100", "1101", "1102", "1103", "1104", "1105", "1106", "1107", "1108", "1109",
                                        "1110", "1111", "1112", "1113", "1114", "1115", "1116", "1117", "1118",
                                        "1171", "1172", "1173", "1174", "1175"
                                    ];
                                    const SATKER_TARGETS = ALL_SATKERS.map((_, i) => i + 2); // kolom 2..akhir adalah satker
                                    const TRI_FIELDS = {
                                        1: 'nilai_1',
                                        2: 'nilai_2',
                                        3: 'nilai_3',
                                        4: 'nilai_4',
                                        5: 'eval_1',
                                        6: 'eval_2',
                                        7: 'eval_3',
                                        8: 'eval_4',
                                        9: 'tl_1',
                                        10: 'tl_2',
                                        11: 'tl_3',
                                        12: 'tl_4'
                                    };

                                    const clean = s => String(s ?? "").trim();

                                    function romanToInt(r) {
                                        const m = {
                                            I: 1,
                                            V: 5,
                                            X: 10,
                                            L: 50,
                                            C: 100,
                                            D: 500,
                                            M: 1000
                                        };
                                        let n = 0;
                                        for (let i = 0; i < r.length; i++) {
                                            const v = m[r[i]] || 0,
                                                vn = m[r[i + 1]] || 0;
                                            n += v < vn ? -v : v;
                                        }
                                        return n || 999;
                                    }

                                    function asNumber(x) {
                                        const n = typeof x === "number" ? x : parseFloat(String(x).replace(",", "."));
                                        if (!Number.isFinite(n)) {
                                            return null;
                                        }
                                        return Math.min(n, 100); // batasi maksimum 100
                                    }

                                    /* gradasi: 0=merah, 50=kuning, 100=hijau */
                                    function heatColor(v) {
                                        const val = Math.max(0, Math.min(100, v));
                                        if (val <= 50) {
                                            const t = val / 50;
                                            const r = 255;
                                            const g = Math.round(229 + (255 - 229) * t);
                                            const b = Math.round(229 * (1 - t));
                                            return `rgb(${r},${g},${b})`;
                                        } else {
                                            const t = (val - 50) / 50;
                                            const r = Math.round(255 * (1 - t));
                                            const g = 255;
                                            const b = Math.round(0 + (230 - 0) * t);
                                            return `rgb(${r},${g},${b})`;
                                        }
                                    }

                                    let dt = null;
                                    let inds = [],
                                        pilars = [];
                                    let idx = new Map(); // key -> {nilai_1..nilai_4}

                                    async function loadData() {
                                        const res = await fetch(ENDPOINT);
                                        if (!res.ok) throw new Error("HTTP " + res.status);
                                        const raw = await res.json();
                                        let rows = Array.isArray(raw) ? raw : (Array.isArray(raw.data) ? raw.data : [raw]);

                                        // mapping (ubah jika nama field berbeda)
                                        rows = rows.map(r => ({
                                            kode_satker: clean(r.kode_satker ?? r.kode ?? r.satker ?? r.KODE_SATKER),
                                            indikator: clean(r.indikator ?? r.ind ?? r.group ?? r.kelompok ?? "A"),
                                            pilar: clean(r.pilar ?? r.PILAR ?? r.level ?? r.tahap),
                                            nilai_1: r.nilai_1 ?? r.nilai ?? r.value ?? r.NILAI_1,
                                            nilai_2: r.nilai_2 ?? r.NILAI_2 ?? null,
                                            nilai_3: r.nilai_3 ?? r.NILAI_3 ?? null,
                                            nilai_4: r.nilai_4 ?? r.NILAI_4 ?? null,
                                            eval_1: r.eval_1 ?? r.EVAL_1 ?? null,
                                            eval_2: r.eval_2 ?? r.EVAL_2 ?? null,
                                            eval_3: r.eval_3 ?? r.EVAL_3 ?? null,
                                            eval_4: r.eval_4 ?? r.EVAL_4 ?? null,
                                            tl_1: r.tl_1 ?? r.TL_1 ?? null,
                                            tl_2: r.tl_2 ?? r.TL_2 ?? null,
                                            tl_3: r.tl_3 ?? r.TL_3 ?? null,
                                            tl_4: r.tl_4 ?? r.TL_4 ?? null
                                        }));

                                        inds = Array.from(new Set(rows.map(r => r.indikator))).sort(); // A, B
                                        pilars = Array.from(new Set(rows.map(r => r.pilar))).sort((a, b) => romanToInt(a) - romanToInt(b));

                                        idx = new Map();
                                        for (const r of rows) {
                                            const key = `${r.kode_satker}|${r.indikator}|${r.pilar}`;
                                            idx.set(key, {
                                                nilai_1: r.nilai_1,
                                                nilai_2: r.nilai_2,
                                                nilai_3: r.nilai_3,
                                                nilai_4: r.nilai_4,
                                                eval_1: r.eval_1 * 100,
                                                eval_2: r.eval_2 * 100,
                                                eval_3: r.eval_3 * 100,
                                                eval_4: r.eval_4 * 100,
                                                tl_1: r.tl_1 * 100,
                                                tl_2: r.tl_2 * 100,
                                                tl_3: r.tl_3 * 100,
                                                tl_4: r.tl_4 * 100
                                            });
                                        }

                                        // Header statis
                                        const thead = document.querySelector("#tbl thead");
                                        thead.innerHTML = `<tr>
                                                <th>Indikator</th>
                                                <th>Pilar</th>
                                                ${ALL_SATKERS.map(s=>`<th>${s}</th>`).join("")}
                                            </tr>`;
                                    }

                                    function makeDataForTri(tri) {
                                        const field = TRI_FIELDS[tri] || 'nilai_1';
                                        const data = [];
                                        for (const ind of inds) {
                                            for (const p of pilars) {
                                                const row = [ind, p];
                                                for (const s of ALL_SATKERS) {
                                                    const rec = idx.get(`${s}|${ind}|${p}`);
                                                    const val = rec ? rec[field] : undefined;
                                                    if (val === undefined || val === null || val === "" || String(val).toLowerCase().includes(
                                                            "belum")) {
                                                        row.push("Belum Isi");
                                                    } else {
                                                        const n = asNumber(val);
                                                        row.push(n === null ? "Belum Isi" : Number(n.toFixed(2)));
                                                    }
                                                }
                                                data.push(row);
                                            }
                                        }
                                        return data;
                                    }

                                    function initOrUpdateTable(data) {
                                        if (!dt) {
                                            dt = new DataTable('#tbl', {
                                                data,
                                                ordering: false,
                                                paging: false,
                                                searching: false,
                                                info: false,
                                                scrollX: true,
                                                autoWidth: false,
                                                fixedColumns: {
                                                    leftColumns: 2
                                                },
                                                columnDefs: [{
                                                        targets: 0,
                                                        className: 'col-indikator'
                                                    },
                                                    {
                                                        targets: 1,
                                                        className: 'col-pilar'
                                                    },
                                                    {
                                                        targets: SATKER_TARGETS,
                                                        className: 'satker-col',
                                                        width: '84px'
                                                    }
                                                ],
                                                createdRow: function(row, rowData) {
                                                    for (let i = 2; i < rowData.length; i++) {
                                                        const td = row.children[i];
                                                        const v = rowData[i];
                                                        if (typeof v === "number") {
                                                            td.classList.add('cell-num');
                                                            td.style.background = heatColor(v);
                                                        } else {
                                                            td.classList.add('cell-empty');
                                                            td.textContent = "Belum Isi";
                                                        }
                                                    }
                                                }
                                            });
                                        } else {
                                            dt.clear();
                                            dt.rows.add(data);
                                            dt.draw(false);
                                        }
                                    }

                                    function setTriTint(tri) {
                                        const wrap = document.getElementById('triWrap');
                                        wrap.classList.remove('tri-1', 'tri-2', 'tri-3', 'tri-4');
                                        wrap.classList.add(`tri-${tri}`);
                                    }

                                    $(async function() {
                                        // Inisialisasi Select2 (simple)
                                        $('#triSelect').select2({
                                            width: 'resolve',
                                            minimumResultsForSearch: Infinity, // sembunyikan kotak pencarian (hanya 4 opsi)
                                            dropdownAutoWidth: true
                                        });

                                        try {
                                            await loadData();
                                            const tri = parseInt($('#triSelect').val(), 10) || 1;
                                            setTriTint(tri);
                                            const data = makeDataForTri(tri);
                                            initOrUpdateTable(data);
                                            $('#info').text(`Tabel Monitoring ZI`);
                                        } catch (e) {
                                            $('#info').text('Gagal memuat: ' + e.message);
                                            console.error(e);
                                        }

                                        // Ganti triwulan -> rebuild data
                                        $('#triSelect').on('change', function() {
                                            const tri = parseInt(this.value, 10) || 1;
                                            setTriTint(tri);
                                            const data = makeDataForTri(tri);
                                            initOrUpdateTable(data);
                                            $('#info').text(`Tabel Monitoring ZI`);
                                        });
                                    });
                                </script>

                            </div>
                        </div>
                    @elseif ($tahun === 2024)
                        <main class="grid">
                            <!-- Ganti href sesuai kebutuhan Anda -->

                        </main>
                    @else
                        <p>Silakan pilih tahun.</p>
                    @endif
                </div>

            </div>
        </div>
        <br>
        <br>

    </div>
</body>

<script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('click', function(e) {
        const el = e.target.closest('a.coming-soon');
        if (!el) return; // klik bukan pada link coming-soon
        e.preventDefault(); // cegah pindah halaman
        Swal.fire({
            title: 'Mohon maaf',
            text: el.dataset.msg || 'Link belum tersedia.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    });
</script>

</html>
