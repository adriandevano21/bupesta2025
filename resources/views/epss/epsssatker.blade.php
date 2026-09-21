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
    <link rel="stylesheet" href="{{ asset('assets-bupesta/epss.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">

    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>

    <style>
        /* Styling tambahan untuk memperhalus UI elemen form */
        .modern-select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #fed7aa;
            background-color: #fff;
            color: #334155;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .modern-select:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
        }

        .badge-selisih {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-up {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-down {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-neutral {
            background: #f1f5f9;
            color: #64748b;
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern th {
            background: #ffedd5;
            color: #9a3412;
            padding: 15px;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #fdba74;
        }

        .table-modern td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }

        .row-domain td {
            background: #fff7ed;
            color: #ea580c;
            font-weight: 700;
            border-bottom: 1px solid #fed7aa;
        }

        .row-aspek td {
            background: #fafaf9;
            font-weight: 600;
            color: #334155;
        }

        .row-indikator:hover td {
            background: #f8fafc;
        }
    </style>
</head>

<body>

    @php
        $opsiPegawaiHtml = '<option value="">-- Pilih Pegawai / PJK --</option>';
        if (!empty($data['pegawai_prov'])) {
            foreach ($data['pegawai_prov'] as $pegawai) {
                $opsiPegawaiHtml .= '<option value="' . $pegawai->nip_pegawai . '">' . $pegawai->name . '</option>';
            }
        }

        $userActive = $data['user_active'] ?? null;
        $userRole = strtolower(trim($userActive->epss ?? ''));
        $userNip = trim($userActive->nip_pegawai ?? '');
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
        <header>
            @include('layout2.navbar-se2026')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>
            @if (session('error'))
                <div
                    style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-family: 'Poppins', sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.3rem;"></i>
                    <span style="font-weight: 500; font-size: 0.95rem;">{{ session('error') }}</span>
                </div>
            @endif

            <div class="posisitengah">
                <!-- Main Container -->
                <div
                    style="background: #ffffff; padding: 30px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); width: 100%; max-width: 1200px; margin: 0 auto; font-family: 'Poppins', sans-serif;">

                    <h2
                        style="margin-top: 0; margin-bottom: 25px; color: #1e293b; text-align: center; font-weight: 700;">
                        Rincian EPSS <span style="color: #f97316;">Satuan Kerja</span>
                    </h2>

                    <!-- Area Filter Modern -->
                    <div
                        style="margin-bottom: 30px; background: #fffaf0; padding: 20px 25px; border-radius: 12px; border: 1px solid #ffedd5; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        <form action="{{ url()->current() }}" method="GET"
                            style="margin: 0; display: flex; flex-direction: column; gap: 20px;">

                            <!-- Filter Utama -->
                            <div style="display: flex; gap: 25px; flex-wrap: wrap; align-items: center;">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label
                                        style="font-size: 0.85rem; font-weight: 600; color: #9a3412; text-transform: uppercase; letter-spacing: 0.5px;">Tahun</label>
                                    <select name="tahun" onchange="this.form.submit()" class="modern-select">
                                        @foreach ($data['list_tahun'] as $t)
                                            <option value="{{ $t }}"
                                                {{ $data['tahun_dipilih'] == $t ? 'selected' : '' }}>
                                                {{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label
                                        style="font-size: 0.85rem; font-weight: 600; color: #9a3412; text-transform: uppercase; letter-spacing: 0.5px;">Tahapan</label>
                                    <select name="tahapan" onchange="this.form.submit()" class="modern-select"
                                        style="min-width: 220px;">
                                        @foreach ($data['list_tahapan'] as $thp)
                                            <option value="{{ $thp->id }}"
                                                {{ $data['tahapan_dipilih'] == $thp->id ? 'selected' : '' }}>
                                                {{ $thp->tahapan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 5px; flex-grow: 1;">
                                    <label
                                        style="font-size: 0.85rem; font-weight: 600; color: #9a3412; text-transform: uppercase; letter-spacing: 0.5px;">Satuan
                                        Kerja</label>
                                    <select name="kode_satker" onchange="this.form.submit()" class="modern-select"
                                        style="width: 100%;">
                                        @foreach ($data['list_satker'] as $satker)
                                            <option value="{{ $satker->kode_satker }}"
                                                {{ $data['kode_satker_dipilih'] == $satker->kode_satker ? 'selected' : '' }}>
                                                [{{ $satker->kode_satker }}] {{ $satker->nama_satker }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="height: 1px; background: #fed7aa; width: 100%;"></div>

                            <!-- Filter Banding -->
                            <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
                                <label
                                    style="font-weight: 600; color: #ea580c; display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px 15px; background: #fff; border-radius: 8px; border: 1px solid #fed7aa; transition: 0.3s;">
                                    <input type="checkbox" name="compare_aktif" value="1"
                                        onchange="this.form.submit()" {{ $data['is_compare'] ? 'checked' : '' }}
                                        style="accent-color: #ea580c; width: 16px; height: 16px;">
                                    Bandingkan Nilai
                                </label>

                                @if ($data['is_compare'])
                                    <div
                                        style="display: flex; flex-direction: column; gap: 5px; padding-left: 20px; border-left: 2px dashed #fed7aa;">
                                        <label style="font-size: 0.85rem; font-weight: 600; color: #b45309;">Tahun
                                            Banding</label>
                                        <select name="compare_tahun" onchange="this.form.submit()"
                                            class="modern-select">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($data['list_tahun'] as $t)
                                                <option value="{{ $t }}"
                                                    {{ $data['compare_tahun'] == $t ? 'selected' : '' }}>
                                                    {{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($data['compare_tahun'])
                                        <div style="display: flex; flex-direction: column; gap: 5px;">
                                            <label style="font-size: 0.85rem; font-weight: 600; color: #b45309;">Tahapan
                                                Banding</label>
                                            <select name="compare_tahapan" onchange="this.form.submit()"
                                                class="modern-select">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($data['list_compare_tahapan'] as $ct)
                                                    <option value="{{ $ct->id }}"
                                                        {{ $data['compare_tahapan'] == $ct->id ? 'selected' : '' }}>
                                                        {{ $ct->tahapan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </form>
                    </div>

                    @if ($data['data_utama'])

                        <!-- HIGHLIGHT KARTU ORANGE PREMIUM -->
                        <div
                            style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 30px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 25px rgba(234, 88, 12, 0.25); flex-wrap: wrap; gap: 20px; position: relative; overflow: hidden;">

                            <!-- Dekorasi Background -->
                            <i class="fa-solid fa-chart-line"
                                style="position: absolute; right: -20px; bottom: -30px; font-size: 15rem; color: white; opacity: 0.05; transform: rotate(-10deg);"></i>

                            <div style="position: relative; z-index: 2;">
                                <p
                                    style="margin: 0 0 8px 0; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; font-weight: 600;">
                                    Satuan Kerja</p>
                                <h3
                                    style="margin: 0; font-size: 2rem; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    {{ $data['data_utama']->nama_satker }}</h3>
                                <div
                                    style="margin-top: 10px; display: inline-block; background: rgba(0,0,0,0.15); padding: 5px 12px; border-radius: 6px; font-weight: 600; letter-spacing: 1px; backdrop-filter: blur(5px);">
                                    KODE: {{ $data['data_utama']->kode_satker }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 15px; text-align: right; position: relative; z-index: 2;">
                                <!-- Kotak Nilai Utama -->
                                <div
                                    style="background: rgba(255,255,255,0.1); padding: 20px 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); text-align: center;">
                                    <p
                                        style="margin: 0 0 5px 0; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: white;">
                                        Total Nilai</p>

                                    <!-- BADGE TAHUN & TAHAPAN -->
                                    <span
                                        style="font-size: 0.75rem; font-weight: 600; color: #ea580c; background: #ffedd5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                        <i class="fa-regular fa-calendar" style="margin-right: 3px;"></i>
                                        {{ $data['data_utama']->tahun }} |
                                        {{ $data['data_utama']->tahapan ?: 'Belum Dipilih' }}
                                    </span>

                                    <h2 style="margin: 0; font-size: 2.8rem; font-weight: 800; line-height: 1;">
                                        {{ number_format($data['data_utama']->nilai_epss, 2) }}</h2>
                                </div>

                                <!-- Kotak Nilai Banding -->
                                @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                    @php
                                        $nilai_banding = $data['data_compare']
                                            ? $data['data_compare']->nilai_epss
                                            : null;
                                        $selisih =
                                            $nilai_banding !== null
                                                ? $data['data_utama']->nilai_epss - $nilai_banding
                                                : null;
                                    @endphp
                                    <div
                                        style="background: rgba(255,255,255,0.1); padding: 20px 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); text-align: center;">
                                        <p
                                            style="margin: 0 0 5px 0; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: white;">
                                            Pembanding</p>

                                        <!-- BADGE TAHUN & TAHAPAN BANDING -->
                                        <span
                                            style="font-size: 0.75rem; font-weight: 600; color: #ea580c; background: #ffedd5; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <i class="fa-regular fa-calendar" style="margin-right: 3px;"></i>
                                            {{ $data['compare_tahun'] }} |
                                            {{ $data['compare_tahapan_nama'] ?: 'Belum Dipilih' }}
                                        </span>

                                        <div
                                            style="display: flex; align-items: center; justify-content: center; gap: 12px;">
                                            <h2 style="margin: 0; font-size: 2rem; font-weight: 800; line-height: 1;">
                                                {{ $nilai_banding !== null ? number_format($nilai_banding, 2) : 'N/A' }}
                                            </h2>
                                            @if ($selisih !== null)
                                                <span
                                                    style="font-size: 1rem; padding: 4px 10px; border-radius: 20px; font-weight: 700; background: {{ $selisih > 0 ? '#dcfce7' : ($selisih < 0 ? '#fee2e2' : '#f1f5f9') }}; color: {{ $selisih > 0 ? '#15803d' : ($selisih < 0 ? '#b91c1c' : '#475569') }}; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                    @if ($selisih > 0)
                                                        +{{ number_format($selisih, 2) }} <i
                                                            class="fa-solid fa-arrow-trend-up"></i>
                                                    @elseif($selisih < 0)
                                                        {{ number_format($selisih, 2) }} <i
                                                            class="fa-solid fa-arrow-trend-down"></i>
                                                    @else
                                                        0.00
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tabel Rincian -->
                        <div
                            style="overflow-x: auto; background: white; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th style="width: 10%; border-top-left-radius: 12px;">Kode</th>
                                        <th>Uraian (Referensi)</th>

                                        <th
                                            style="text-align: center; width: 17%; background: #fdba74; color: #7c2d12;">
                                            Nilai Utama <br>
                                            <!-- BADGE KETERANGAN TAHUN TAHAPAN UTAMA -->
                                            <span
                                                style="font-size: 0.70rem; font-weight: 600; color: #ea580c; background: #ffedd5; padding: 3px 8px; border-radius: 4px; display: inline-block; margin-top: 6px; border: 1px solid rgba(234, 88, 12, 0.15);">
                                                <i class="fa-regular fa-calendar" style="margin-right: 3px;"></i>
                                                {{ $data['data_utama']->tahun }} |
                                                {{ $data['data_utama']->tahapan ?: 'Belum Dipilih' }}
                                            </span>
                                        </th>

                                        @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                            <th style="text-align: center; width: 17%;">
                                                Pembanding <br>
                                                <!-- BADGE KETERANGAN TAHUN TAHAPAN BANDING -->
                                                <span
                                                    style="font-size: 0.70rem; font-weight: 600; color: #ea580c; background: #ffedd5; padding: 3px 8px; border-radius: 4px; display: inline-block; margin-top: 6px; border: 1px solid rgba(234, 88, 12, 0.15);">
                                                    <i class="fa-regular fa-calendar" style="margin-right: 3px;"></i>
                                                    {{ $data['compare_tahun'] }} |
                                                    {{ $data['compare_tahapan_nama'] ?: 'Belum Dipilih' }}
                                                </span>
                                            </th>
                                            <th style="text-align: center; width: 12%; border-top-right-radius: 12px;">
                                                Selisih</th>
                                        @else
                                            <th style="display: none; border-top-right-radius: 12px;"></th>
                                            <!-- Spacer for radius -->
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['struktur'] as $kodeDomain => $aspeks)
                                        @php
                                            $val_domain = $data['data_utama']->$kodeDomain ?? 0;
                                            $val_domain_banding = $data['data_compare']
                                                ? $data['data_compare']->$kodeDomain ?? 0
                                                : null;
                                            $selisih_domain =
                                                $val_domain_banding !== null ? $val_domain - $val_domain_banding : null;
                                        @endphp

                                        <!-- Row Domain -->
                                        <tr class="row-domain">
                                            <td style="border-left: 4px solid #ea580c;">{{ $kodeDomain }}</td>
                                            <td>{{ $data['referensi'][$kodeDomain]->penjelasan ?? 'Tidak ada referensi' }}
                                            </td>
                                            <td style="text-align: center; font-size: 1.05rem;">
                                                {{ number_format($val_domain, 2) }}
                                            </td>
                                            @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                                <td style="text-align: center; color: #9a3412;">
                                                    {{ $val_domain_banding !== null ? number_format($val_domain_banding, 2) : '-' }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($selisih_domain !== null)
                                                        <span
                                                            class="badge-selisih {{ $selisih_domain > 0 ? 'badge-up' : ($selisih_domain < 0 ? 'badge-down' : 'badge-neutral') }}">
                                                            {{ $selisih_domain > 0 ? '+' : '' }}{{ number_format($selisih_domain, 2) }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>

                                        @foreach ($aspeks as $kodeAspek => $indikators)
                                            @php
                                                $val_aspek = $data['data_utama']->$kodeAspek ?? 0;
                                                $val_aspek_banding = $data['data_compare']
                                                    ? $data['data_compare']->$kodeAspek ?? 0
                                                    : null;
                                                $selisih_aspek =
                                                    $val_aspek_banding !== null
                                                        ? $val_aspek - $val_aspek_banding
                                                        : null;
                                            @endphp

                                            <!-- Row Aspek -->
                                            <tr class="row-aspek">
                                                <td style="padding-left: 25px;">{{ $kodeAspek }}</td>
                                                <td>{{ $data['referensi'][$kodeAspek]->penjelasan ?? 'Tidak ada referensi' }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ number_format($val_aspek, 2) }}
                                                </td>
                                                @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                                    <td style="text-align: center;">
                                                        {{ $val_aspek_banding !== null ? number_format($val_aspek_banding, 2) : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if ($selisih_aspek !== null)
                                                            <span
                                                                class="badge-selisih {{ $selisih_aspek > 0 ? 'badge-up' : ($selisih_aspek < 0 ? 'badge-down' : 'badge-neutral') }}">
                                                                {{ $selisih_aspek > 0 ? '+' : '' }}{{ number_format($selisih_aspek, 2) }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>

                                            @foreach ($indikators as $kodeIndikator)
                                                @php
                                                    $val_ind = $data['data_utama']->$kodeIndikator ?? 0;
                                                    $val_ind_banding = $data['data_compare']
                                                        ? $data['data_compare']->$kodeIndikator ?? null
                                                        : null;
                                                    $selisih_ind =
                                                        $val_ind_banding !== null ? $val_ind - $val_ind_banding : null;
                                                @endphp

                                                <!-- Row Indikator -->
                                                <tr class="row-indikator">
                                                    <td style="padding-left: 45px;">{{ $kodeIndikator }}</td>
                                                    <td style="font-size: 0.9rem;">
                                                        {{ $data['referensi'][$kodeIndikator]->penjelasan ?? 'Tidak ada referensi' }}
                                                    </td>
                                                    <td style="text-align: center; font-weight: 600; color: #1e293b;">
                                                        {{ number_format($val_ind, 2) }}
                                                    </td>
                                                    @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                                        <td style="text-align: center;">
                                                            {{ $val_ind_banding !== null ? number_format($val_ind_banding, 2) : '-' }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if ($selisih_ind !== null)
                                                                <span
                                                                    class="badge-selisih {{ $selisih_ind > 0 ? 'badge-up' : ($selisih_ind < 0 ? 'badge-down' : 'badge-neutral') }}">
                                                                    {{ $selisih_ind > 0 ? '+' : '' }}{{ number_format($selisih_ind, 2) }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div
                            style="padding: 50px; text-align: center; background: #fff7ed; border: 2px dashed #fdba74; border-radius: 12px;">
                            <i class="fa-solid fa-folder-open"
                                style="font-size: 3rem; color: #fb923c; margin-bottom: 15px;"></i>
                            <h4 style="margin: 0; color: #9a3412; font-weight: 600;">Data Tidak Ditemukan</h4>
                            <p style="margin: 5px 0 0 0; color: #ea580c;">Belum ada rincian data nilai untuk satker dan
                                periode tahapan ini.</p>
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

        </div>

        {{-- Script Tambahan --}}
        <script>
            window.BupestaConfig = {
                userName: "{{ auth()->check() ? auth()->user()->name : 'Guest' }}",
                successMessage: "{{ session('success') }}",
                opsiPegawaiHtml: `{!! $opsiPegawaiHtml !!}`
            };
            window.userActiveNip = "{{ $userNip }}";
        </script>

        <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
        <script src="{{ asset('assets-bupesta/epss.js') }}"></script>

</body>

</html>
