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
        /* CSS Admin Layout */
        .admin-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }

        .tab-btn {
            padding: 10px 20px;
            border: none;
            background: #e2e8f0;
            cursor: pointer;
            border-radius: 5px;
            font-weight: 600;
        }

        .tab-btn.active {
            background: #3182ce;
            color: white;
        }

        .tab-content {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tab-content.active {
            display: block;
        }

        /* CSS Tabel & Horizontal Scroll */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            margin-top: 15px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .admin-table th,
        .admin-table td {
            border: 1px solid #cbd5e0;
            padding: 10px 12px;
            text-align: left;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Freeze Header (Atas) */
        .admin-table th {
            background: #edf2f7;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        /* Freeze Kolom Aksi (Kiri) */
        .admin-table th:first-child,
        .admin-table td:first-child {
            position: sticky;
            left: 0;
            background: #f8fafc;
            text-align: center;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 2;
            min-width: 90px;
        }

        .admin-table th:first-child {
            z-index: 3;
        }

        /* TOMBOL AKSI SEDERHANA & RAPI */
        .btn-sederhana {
            display: inline-block !important;
            width: 32px !important;
            height: 32px !important;
            line-height: 32px !important;
            text-align: center !important;
            padding: 0 !important;
            margin: 0 2px !important;
            border: none !important;
            border-radius: 4px !important;
            color: white !important;
            font-size: 13px !important;
            cursor: pointer !important;
            position: static !important;
            box-shadow: none !important;
        }

        .btn-biru {
            background-color: #3182ce !important;
        }

        .btn-merah {
            background-color: #e53e3e !important;
        }

        .btn-add {
            background: #48bb78;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* CSS Filter Dropdown */
        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            background: #f7fafc;
            padding: 10px;
            border-radius: 6px;
            align-items: center;
        }

        .filter-dropdown {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cbd5e0;
            min-width: 200px;
        }

        /* Field Terkunci / Disabled */
        select:disabled,
        input:disabled {
            background-color: #edf2f7;
            cursor: not-allowed;
            opacity: 0.8;
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

        // 38 Indikator EPSS
        $indikators = [
            '10101',
            '10201',
            '10301',
            '10401',
            '20101',
            '20102',
            '20201',
            '20301',
            '20302',
            '20401',
            '20402',
            '20403',
            '20501',
            '20502',
            '30101',
            '30102',
            '30103',
            '30201',
            '30301',
            '30302',
            '30401',
            '40101',
            '40102',
            '40103',
            '40104',
            '40201',
            '40202',
            '40301',
            '40302',
            '40303',
            '40304',
            '50101',
            '50102',
            '50103',
            '50201',
            '50301',
            '50302',
            '50303',
        ];
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

            <div class="posisitengah">
                @if (session('success'))
                    <div
                        style="background: #c6f6d5; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #22543d; text-align: center; font-weight: bold;">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($userRole === 'admin')
                    <!-- ============================================== -->
                    <!-- HALAMAN CRUD ADMIN -->
                    <!-- ============================================== -->
                    <div class="admin-nav">
                        <button class="tab-btn active" onclick="bukaTab(event, 'tabKegiatan')">EPSS Kegiatan</button>
                        <button class="tab-btn" onclick="bukaTab(event, 'tabTahapan')">EPSS Tahapan</button>
                        <button class="tab-btn" onclick="bukaTab(event, 'tabUsulan')">EPSS Usulan</button>
                        <button class="tab-btn" onclick="bukaTab(event, 'tabNilai')">EPSS Nilai</button>
                    </div>

                    <!-- TAB: KEGIATAN -->
                    <div id="tabKegiatan" class="tab-content active">
                        <h2>Tabel EPSS Kegiatan</h2>
                        <div class="filter-container">
                            <label><strong>Filter Tahun:</strong></label>
                            <select class="filter-dropdown" onchange="filterTableKegiatan(this.value)">
                                <option value="">-- Semua Tahun --</option>
                                @foreach ($data['kegiatan']->pluck('tahun')->unique() as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn-add" onclick="formKegiatan('add')"><i class="fa-solid fa-plus"></i> Tambah
                            Kegiatan</button>

                        <div class="table-responsive">
                            <table class="admin-table" id="tableKegiatan">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['kegiatan'] as $item)
                                        <tr data-tahun="{{ $item->tahun }}">
                                            <td>
                                                <!-- Tombol Sederhana -->
                                                <button type="button" class="btn-sederhana btn-biru"
                                                    data-item="{{ json_encode($item) }}"
                                                    onclick="formKegiatan('edit', this.dataset.item)"
                                                    title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                                                <form action="{{ route('epss.kegiatan.destroy', $item->id) }}"
                                                    method="POST" style="display:inline-block; margin:0; padding:0;"
                                                    onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-sederhana btn-merah" type="submit"
                                                        title="Hapus Data"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>{{ $item->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB: TAHAPAN -->
                    <div id="tabTahapan" class="tab-content">
                        <h2>Tabel EPSS Tahapan Kegiatan</h2>
                        <div class="filter-container">
                            <label><strong>Filter Kegiatan:</strong></label>
                            <select class="filter-dropdown" onchange="filterTableTahapan(this.value)">
                                <option value="">-- Semua Kegiatan --</option>
                                @foreach ($data['kegiatan'] as $keg)
                                    <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }} (ID:
                                        {{ $keg->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn-add" onclick="formTahapan('add')"><i class="fa-solid fa-plus"></i> Tambah
                            Tahapan</button>

                        <div class="table-responsive">
                            <table class="admin-table" id="tableTahapan">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Tahun Kegiatan</th>
                                        <th>Tahapan</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Selesai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['tahapan'] as $item)
                                        <tr data-keg="{{ $item->id_kegiatan }}">
                                            <td>
                                                <!-- Tombol Sederhana -->
                                                <button type="button" class="btn-sederhana btn-biru"
                                                    data-item="{{ json_encode($item) }}"
                                                    onclick="formTahapan('edit', this.dataset.item)"
                                                    title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                                                <form action="{{ route('epss.tahapan.destroy', $item->id) }}"
                                                    method="POST" style="display:inline-block; margin:0; padding:0;"
                                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-sederhana btn-merah" type="submit"
                                                        title="Hapus Data"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                            <td>{{ $data['kegiatan']->firstWhere('id', $item->id_kegiatan)->tahun ?? 'N/A' }}
                                            </td>
                                            <td>{{ $item->tahapan }}</td>
                                            <td>{{ $item->tanggal_mulai }}</td>
                                            <td>{{ $item->tanggal_selesai }}</td>
                                            <td>{{ $item->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB: USULAN KEGIATAN -->
                    <div id="tabUsulan" class="tab-content">
                        <h2>Tabel EPSS Usulan Kegiatan</h2>
                        <div class="filter-container">
                            <label><strong>Filter Kegiatan:</strong></label>
                            <select class="filter-dropdown" onchange="filterTableUsulan(this.value)">
                                <option value="">-- Semua Kegiatan --</option>
                                @foreach ($data['kegiatan'] as $keg)
                                    <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }} (ID:
                                        {{ $keg->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn-add" onclick="formUsulan('add')"><i class="fa-solid fa-plus"></i> Tambah
                            Usulan</button>

                        <div class="table-responsive">
                            <table class="admin-table" id="tableUsulan">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Tahun Kegiatan</th>
                                        <th>Kode Satker</th>
                                        <th>Kegiatan 1</th>
                                        <th>Nama Dinas 1</th>
                                        <th>Tahun 1</th>
                                        <th>Kegiatan 2</th>
                                        <th>Nama Dinas 2</th>
                                        <th>Tahun 2</th>
                                        <th>Satker Penilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['usulan'] as $item)
                                        <tr data-keg="{{ $item->id_kegiatan }}">
                                            <td>
                                                <!-- Tombol Sederhana -->
                                                <button type="button" class="btn-sederhana btn-biru"
                                                    data-item="{{ json_encode($item) }}"
                                                    onclick="formUsulan('edit', this.dataset.item)"
                                                    title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                                                <form action="{{ route('epss.usulan.destroy', $item->id) }}"
                                                    method="POST" style="display:inline-block; margin:0; padding:0;"
                                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-sederhana btn-merah" type="submit"
                                                        title="Hapus Data"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>

                                            <td>{{ $data['kegiatan']->firstWhere('id', $item->id_kegiatan)->tahun ?? 'N/A' }}
                                            </td>

                                            {{-- Tampilkan Relasi Nama Satker (kode_satker) --}}
                                            <td>
                                                @php $s1 = collect($data['satkers'] ?? [])->firstWhere('kode_satker', $item->kode_satker); @endphp
                                                ({{ $item->kode_satker }})
                                                {{ $s1->nama_satker ?? '' }}
                                            </td>

                                            <td>{{ Str::limit($item->kegiatan_1, 50) }}</td>
                                            <td>{{ $item->nama_dinas_1 }}</td>
                                            <td>{{ $item->tahun_1 }}</td>
                                            <td>{{ Str::limit($item->kegiatan_2, 50) }}</td>
                                            <td>{{ $item->nama_dinas_2 }}</td>
                                            <td>{{ $item->tahun_2 }}</td>

                                            {{-- Tampilkan Relasi Nama Satker Penilai (kode_satker) --}}
                                            <td>
                                                @php $s2 = collect($data['satkers'] ?? [])->firstWhere('kode_satker', $item->kode_satker_penilai); @endphp
                                                ({{ $item->kode_satker_penilai }}) {{ $s2->nama_satker ?? '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB: NILAI -->
                    <div id="tabNilai" class="tab-content">
                        <h2>Tabel EPSS Nilai</h2>
                        <div class="filter-container">
                            <label><strong>Filter Kegiatan:</strong></label>
                            <select id="filterNilaiKeg" class="filter-dropdown"
                                onchange="updateFilterNilaiThp(this.value)">
                                <option value="">-- Semua Kegiatan --</option>
                                @foreach ($data['kegiatan'] as $keg)
                                    <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }} (ID:
                                        {{ $keg->id }})</option>
                                @endforeach
                            </select>

                            <label><strong>Filter Tahapan:</strong></label>
                            <select id="filterNilaiThp" class="filter-dropdown" onchange="filterTableNilai()">
                                <option value="">-- Semua Tahapan --</option>
                            </select>
                        </div>

                        <!-- TOMBOL TAMBAH & TOMBOL EDIT MASSAL BARU -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button class="btn-add" onclick="formNilai('add')"><i class="fa-solid fa-plus"></i>
                                Tambah Nilai</button>
                            <button class="btn-add" style="background:#ecc94b; color:black;"
                                onclick="bukaModalBulkNilai()"><i class="fa-solid fa-table-list"></i> Update Massal
                                dari Excel</button>
                        </div>

                        <div class="table-responsive">
                            <table class="admin-table" id="tableNilai">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Tahun Keg</th>
                                        <th>Tahapan</th>
                                        <th>Satker Usulan</th>
                                        @foreach ($indikators as $ind)
                                            <th>{{ $ind }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['nilai'] as $item)
                                        <tr data-keg="{{ $item->id_kegiatan }}" data-thp="{{ $item->id_tahapan }}">
                                            <td>
                                                <!-- Tombol Sederhana -->
                                                <button type="button" class="btn-sederhana btn-biru"
                                                    data-item="{{ json_encode($item) }}"
                                                    onclick="formNilai('edit', this.dataset.item)"
                                                    title="Edit Data"><i class="fa-solid fa-pen"></i></button>
                                                <form action="{{ route('epss.nilai.destroy', $item->id) }}"
                                                    method="POST" style="display:inline-block; margin:0; padding:0;"
                                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-sederhana btn-merah" type="submit"
                                                        title="Hapus Data"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                            <td>{{ $data['kegiatan']->firstWhere('id', $item->id_kegiatan)->tahun ?? 'N/A' }}
                                            </td>
                                            <td>{{ $data['tahapan']->firstWhere('id', $item->id_tahapan)->tahapan ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @php
                                                    $usl = $data['usulan']->firstWhere('id', $item->id_usulan_kegiatan);
                                                    $s_usl = collect($data['satkers'] ?? [])->firstWhere(
                                                        'kode_satker',
                                                        $usl->kode_satker ?? '',
                                                    );
                                                @endphp
                                                ({{ $usl->kode_satker ?? 'N/A' }})
                                                {{ $s_usl->nama_satker ?? '' }}
                                            </td>
                                            @foreach ($indikators as $ind)
                                                <td>{{ $item->{$ind} }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- MODAL FORM CRUD (LAMA) -->
                    <!-- ============================================== -->

                    <!-- Modal Kegiatan -->
                    <div id="modalKegiatan" class="modal-overlay" style="display: none; align-items:center;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="modalKegiatanTitle">Tambah Kegiatan</h3>
                                <button type="button"
                                    onclick="document.getElementById('modalKegiatan').style.display='none'"
                                    style="float:right; cursor:pointer; background:none; border:none; font-size:1.2rem;">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form id="formKegiatanEl" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" id="methodKegiatan" value="POST">
                                    <div class="grup-input"><label>Tahun</label><input type="number" id="keg_tahun"
                                            name="tahun" class="input-form"></div>
                                    <div class="grup-input"><label>Status</label><input type="text"
                                            id="keg_status" name="status" class="input-form"
                                            placeholder="Contoh: Ada/Tidak"></div>
                                    <div class="modal-footer" style="text-align: right; margin-top:20px;"><button
                                            type="submit" class="btn-simpan">Simpan Data</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Tahapan -->
                    <div id="modalTahapan" class="modal-overlay" style="display: none; align-items:center;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="modalTahapanTitle">Tambah Tahapan</h3>
                                <button type="button"
                                    onclick="document.getElementById('modalTahapan').style.display='none'"
                                    style="float:right; cursor:pointer; background:none; border:none; font-size:1.2rem;">&times;</button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <form id="formTahapanEl" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" id="methodTahapan" value="POST">
                                    <div class="grup-input">
                                        <label>Tahun Kegiatan</label>
                                        <select id="thp_id_kegiatan" name="id_kegiatan" class="input-form" required>
                                            <option value="">-- Pilih Kegiatan --</option>
                                            @foreach ($data['kegiatan'] as $keg)
                                                <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }} (ID:
                                                    {{ $keg->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grup-input"><label>Tahapan</label><input type="text"
                                            id="thp_tahapan" name="tahapan" class="input-form"></div>
                                    <div class="grup-input"><label>Tgl Mulai</label><input type="date"
                                            id="thp_tanggal_mulai" name="tanggal_mulai" class="input-form"></div>
                                    <div class="grup-input"><label>Tgl Selesai</label><input type="date"
                                            id="thp_tanggal_selesai" name="tanggal_selesai" class="input-form"></div>
                                    <div class="grup-input"><label>Status</label><input type="text"
                                            id="thp_status" name="status" class="input-form"></div>
                                    <div class="modal-footer" style="text-align: right; margin-top:20px;"><button
                                            type="submit" class="btn-simpan">Simpan Data</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Usulan -->
                    <div id="modalUsulan" class="modal-overlay" style="display: none; align-items:center;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="modalUsulanTitle">Tambah Usulan</h3>
                                <button type="button"
                                    onclick="document.getElementById('modalUsulan').style.display='none'"
                                    style="float:right; cursor:pointer; background:none; border:none; font-size:1.2rem;">&times;</button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <form id="formUsulanEl" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" id="methodUsulan" value="POST">
                                    <div class="grup-input">
                                        <label>Tahun Kegiatan</label>
                                        <select id="usl_id_kegiatan" name="id_kegiatan" class="input-form" required>
                                            <option value="">-- Pilih Kegiatan --</option>
                                            @foreach ($data['kegiatan'] as $keg)
                                                <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }} (ID:
                                                    {{ $keg->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grup-input">
                                        <label>Kode Satker</label>
                                        <select id="usl_kode_satker" name="kode_satker" class="input-form">
                                            <option value="">-- Pilih Satker --</option>
                                            @foreach ($data['satkers'] ?? [] as $satker)
                                                <option value="{{ $satker->kode_satker }}">
                                                    ({{ $satker->kode_satker }})
                                                    {{ $satker->nama_satker }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grup-input"><label>Kegiatan 1</label>
                                        <textarea id="usl_kegiatan_1" name="kegiatan_1" class="input-form" rows="2"></textarea>
                                    </div>
                                    <div class="grup-input"><label>Nama Dinas 1</label><input type="text"
                                            id="usl_nama_dinas_1" name="nama_dinas_1" class="input-form"></div>
                                    <div class="grup-input"><label>Tahun 1</label><input type="text"
                                            id="usl_tahun_1" name="tahun_1" class="input-form"></div>

                                    <div class="grup-input"><label>Kegiatan 2</label>
                                        <textarea id="usl_kegiatan_2" name="kegiatan_2" class="input-form" rows="2"></textarea>
                                    </div>
                                    <div class="grup-input"><label>Nama Dinas 2</label><input type="text"
                                            id="usl_nama_dinas_2" name="nama_dinas_2" class="input-form"></div>
                                    <div class="grup-input"><label>Tahun 2</label><input type="text"
                                            id="usl_tahun_2" name="tahun_2" class="input-form"></div>

                                    <div class="grup-input">
                                        <label>Kode Satker Penilai</label>
                                        <select id="usl_kode_satker_penilai" name="kode_satker_penilai"
                                            class="input-form">
                                            <option value="">-- Pilih Satker Penilai --</option>
                                            @foreach ($data['satkers'] ?? [] as $satker)
                                                <option value="{{ $satker->kode_satker }}">
                                                    ({{ $satker->kode_satker }})
                                                    {{ $satker->nama_satker }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="modal-footer" style="text-align: right; margin-top:20px;"><button
                                            type="submit" class="btn-simpan">Simpan Data</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Nilai (Satu per satu) -->
                    <div id="modalNilai" class="modal-overlay" style="display: none; align-items:center;">
                        <div class="modal-content" style="max-width: 800px; width: 90%;">
                            <div class="modal-header">
                                <h3 id="modalNilaiTitle">Tambah Nilai</h3>
                                <button type="button"
                                    onclick="document.getElementById('modalNilai').style.display='none'"
                                    style="float:right; cursor:pointer; background:none; border:none; font-size:1.2rem;">&times;</button>
                            </div>
                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <form id="formNilaiEl" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" id="methodNilai" value="POST">

                                    <div style="display:flex; gap:10px;">
                                        <div class="grup-input" style="flex: 1;">
                                            <label>Tahun Kegiatan</label>
                                            <select id="nil_id_kegiatan" name="id_kegiatan" class="input-form"
                                                onchange="updateModalNilaiDropdowns(this.value)" required>
                                                <option value="">-- Pilih Kegiatan --</option>
                                                @foreach ($data['kegiatan'] as $keg)
                                                    <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grup-input" style="flex: 1;">
                                            <label>Tahapan (Otomatis)</label>
                                            <select id="nil_id_tahapan" name="id_tahapan" class="input-form"
                                                required>
                                                <option value="">-- Pilih Tahapan --</option>
                                            </select>
                                        </div>
                                        <div class="grup-input" style="flex: 1;">
                                            <label>Usulan (Otomatis)</label>
                                            <select id="nil_id_usulan_kegiatan" name="id_usulan_kegiatan"
                                                class="input-form" required>
                                                <option value="">-- Pilih Usulan --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr style="margin: 15px 0;">

                                    <div class="grup-input"
                                        style="background: #ebf8ff; padding: 15px; border-radius: 6px; border: 1px dashed #3182ce;">
                                        <label style="color: #2b6cb0; font-weight: bold; margin-bottom: 5px;"><i
                                                class="fa-solid fa-paste"></i> Paste 38 Cell Excel ke Sini</label>
                                        <textarea id="pasteExcelNilai" class="input-form" rows="3"
                                            placeholder="Block 1 baris (38 cell/indikator) dari Excel lalu paste di sini... otomatis mengisi ke bawah."></textarea>
                                    </div>

                                    <!-- Looping 38 Indikator -->
                                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                                        @foreach ($indikators as $ind)
                                            <div class="grup-input" style="margin-bottom: 0;">
                                                <label style="font-size: 0.8rem;">Ind {{ $ind }}</label>
                                                <input type="number" step="any" id="nil_{{ $ind }}"
                                                    name="{{ $ind }}" class="input-form" min="0"
                                                    max="5">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="modal-footer" style="text-align: right; margin-top:20px;">
                                        <button type="submit" class="btn-simpan">Simpan Data</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- MODAL UPDATE MASSAL (DIKEMBANGKAN LEBIH AMAN) -->
                    <!-- ============================================== -->
                    <div id="modalBulkNilai" class="modal-overlay" style="display: none; align-items:center;">
                        <div class="modal-content" style="max-width: 900px; width: 95%;">
                            <div class="modal-header">
                                <h3>Update Nilai Massal dari Excel</h3>
                                <button type="button"
                                    onclick="document.getElementById('modalBulkNilai').style.display='none'"
                                    style="float:right; cursor:pointer; background:none; border:none; font-size:1.2rem;">&times;</button>
                            </div>
                            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                                <div style="display:flex; gap:10px;">
                                    <div class="grup-input" style="flex: 1;">
                                        <label>Tahun Kegiatan</label>
                                        <select id="bulk_id_kegiatan" class="input-form"
                                            onchange="updateBulkNilaiDropdowns(this.value)">
                                            <option value="">-- Pilih Kegiatan --</option>
                                            @foreach ($data['kegiatan'] as $keg)
                                                <option value="{{ $keg->id }}">Tahun {{ $keg->tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grup-input" style="flex: 1;">
                                        <label>Tahapan</label>
                                        <select id="bulk_id_tahapan" class="input-form"
                                            onchange="loadBulkSatkerList()">
                                            <option value="">-- Pilih Tahapan --</option>
                                        </select>
                                    </div>
                                </div>

                                <div
                                    style="background: #edf2f7; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                                    <label style="font-weight:bold; font-size: 0.9rem; color:#2d3748;">
                                        <i class="fa-solid fa-list-ol"></i> Urutan Baris Excel Anda Wajib Mengikuti
                                        Satker Berikut (Di-sort by Kode Satker):
                                    </label>
                                    <div id="bulkSatkerList"
                                        style="margin-top: 8px; color: #4a5568; font-size:0.85rem;">
                                        <span style="color:#e53e3e;">Silakan pilih Tahun Kegiatan dan Tahapan terlebih
                                            dahulu.</span>
                                    </div>
                                </div>

                                <div class="grup-input"
                                    style="background: #ebf8ff; padding: 15px; border-radius: 6px; border: 1px dashed #3182ce;">
                                    <label style="color: #2b6cb0; font-weight: bold; margin-bottom: 5px;"><i
                                            class="fa-solid fa-paste"></i> Paste Block Seluruh Baris Excel ke
                                        Sini</label>
                                    <p style="font-size:0.8rem; color:#2b6cb0; margin:0 0 10px 0;">Blok keseluruhan
                                        data di Excel (Baris = Jumlah Satker di atas, Kolom = 38 Indikator) dan
                                        Tempel/Paste.</p>
                                    <textarea id="bulkPasteExcel" class="input-form" rows="8"
                                        placeholder="Contoh: Jika ada 12 Satker, blok 12 baris dan 38 kolom angka di Excel Anda..."></textarea>
                                </div>

                                <div class="modal-footer" style="text-align: right; margin-top:20px;">
                                    <button type="button" id="btnSubmitBulk" class="btn-simpan"
                                        style="background:#ecc94b; color:black;" onclick="submitBulkNilai()">Simpan
                                        Seluruh Data Massal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        style="background: #fed7d7; padding: 20px; text-align:center; border-radius: 5px; color: #c53030; margin-top: 30px;">
                        <h2>Akses Ditolak!</h2>
                        <p>Halaman ini hanya dapat diakses oleh Administrator.</p>
                    </div>
                @endif
            </div>

            {{-- Bagian Modal Profil dan Footer --}}
        </div>
    </div>

    {{-- Script Tambahan --}}
    <script>
        const dataTahapan = @json($data['tahapan'] ?? []);
        const dataUsulan = @json($data['usulan'] ?? []);
        const dataSatkers = @json($data['satkers'] ?? []);
        const dataNilai = @json($data['nilai'] ?? []);

        const indikators = ['10101', '10201', '10301', '10401', '20101', '20102', '20201', '20301', '20302', '20401',
            '20402', '20403', '20501', '20502', '30101', '30102', '30103', '30201', '30301', '30302', '30401', '40101',
            '40102', '40103', '40104', '40201', '40202', '40301', '40302', '40303', '40304', '50101', '50102', '50103',
            '50201', '50301', '50302', '50303'
        ];

        function getNamaSatker(kode) {
            let s = dataSatkers.find(x => x.kode_satker == kode);
            return s ? s.nama_satker : 'N/A';
        }

        // ============================================
        // LOGIC UPDATE MASSAL / BULK (NEW SECURE RAW JSON)
        // ============================================
        let bulkNilaiTargets = [];

        function bukaModalBulkNilai() {
            document.getElementById('bulkPasteExcel').value = '';
            document.getElementById('bulkSatkerList').innerHTML =
                '<span style="color:#e53e3e;">Silakan pilih Tahun Kegiatan dan Tahapan terlebih dahulu.</span>';
            document.getElementById('modalBulkNilai').style.display = 'flex';
        }

        function updateBulkNilaiDropdowns(idKeg) {
            const thpSelect = document.getElementById('bulk_id_tahapan');
            thpSelect.innerHTML = '<option value="">-- Pilih Tahapan --</option>';
            if (idKeg) {
                dataTahapan.filter(t => t.id_kegiatan == idKeg).forEach(t => {
                    thpSelect.innerHTML += `<option value="${t.id}">${t.tahapan}</option>`;
                });
            }
            loadBulkSatkerList();
        }

        function loadBulkSatkerList() {
            const idKeg = document.getElementById('bulk_id_kegiatan').value;
            const idThp = document.getElementById('bulk_id_tahapan').value;
            const container = document.getElementById('bulkSatkerList');

            if (!idKeg || !idThp) {
                container.innerHTML =
                    '<span style="color:#e53e3e;">Silakan pilih Tahun Kegiatan dan Tahapan terlebih dahulu.</span>';
                return;
            }

            let targetNilai = dataNilai.filter(n => n.id_kegiatan == idKeg && n.id_tahapan == idThp);

            targetNilai = targetNilai.map(n => {
                let usl = dataUsulan.find(u => u.id == n.id_usulan_kegiatan);
                n.kode_satker = usl ? usl.kode_satker : 'ZZZ';
                n.nama_satker = getNamaSatker(n.kode_satker);
                return n;
            });

            targetNilai.sort((a, b) => a.kode_satker.localeCompare(b.kode_satker));
            bulkNilaiTargets = targetNilai;

            if (bulkNilaiTargets.length === 0) {
                container.innerHTML =
                    '<span style="color:#e53e3e;">Belum ada form nilai yang terdaftar untuk Kegiatan dan Tahapan ini. Buat terlebih dahulu pada fitur Tambah Nilai.</span>';
            } else {
                let html = `<ol style="margin-left: 20px; columns: 2; margin-top:5px; padding-left:15px;">`;
                bulkNilaiTargets.forEach(n => {
                    html += `<li>(${n.kode_satker}) ${n.nama_satker}</li>`;
                });
                html += `</ol>`;
                container.innerHTML = html;
            }
        }

        async function submitBulkNilai() {
            const pasteData = document.getElementById('bulkPasteExcel').value;
            if (bulkNilaiTargets.length === 0) {
                alert("Target update kosong. Pastikan daftar Satker sudah muncul.");
                return;
            }
            if (!pasteData) {
                alert("Silakan Paste data excel terlebih dahulu pada kolom yang disediakan.");
                return;
            }

            let rows = pasteData.split(/\r\n|\n/).map(v => v.trim()).filter(v => v !== '');

            if (rows.length !== bulkNilaiTargets.length) {
                let proceed = confirm(
                    `Perhatian: Jumlah baris data yang Anda paste (${rows.length} baris) TIDAK SAMA dengan jumlah Satker terdaftar (${bulkNilaiTargets.length} Satker). Lanjutkan proses ke baris yang ada saja?`
                );
                if (!proceed) return;
            }

            let btn = document.getElementById('btnSubmitBulk');
            btn.innerText = "Memproses Update... Mohon Tunggu";
            btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            let successCount = 0;
            let errorCount = 0;

            for (let i = 0; i < bulkNilaiTargets.length; i++) {
                if (i >= rows.length) break;

                let cols = rows[i].split('\t').map(v => v.trim());
                let targetId = bulkNilaiTargets[i].id;

                if (!targetId) {
                    errorCount++;
                    continue;
                }

                // PAYLOAD RAW JSON YANG AMAN 100% UNTUK NAMA KOLOM ANGKA
                let payload = {
                    _token: csrfToken,
                    id_kegiatan: bulkNilaiTargets[i].id_kegiatan,
                    id_tahapan: bulkNilaiTargets[i].id_tahapan,
                    id_usulan_kegiatan: bulkNilaiTargets[i].id_usulan_kegiatan
                };

                for (let c = 0; c < indikators.length; c++) {
                    if (cols[c] !== undefined && cols[c] !== "") {
                        let cleanStr = cols[c].replace(/"/g, '').replace(',', '.');
                        let val = parseFloat(cleanStr);
                        payload[indikators[c]] = isNaN(val) ? 0 : val;
                    } else {
                        // Pertahankan nilai asli jika tercut
                        let existingVal = bulkNilaiTargets[i][indikators[c]];
                        payload[indikators[c]] = existingVal !== null && existingVal !== undefined ? existingVal : 0;
                    }
                }

                try {
                    // Gunakan Parameter spoofing URL
                    let urlTarget = "{{ url('/adminepss/nilai') }}/" + targetId + "?_method=PUT";

                    let response = await fetch(urlTarget, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json' // Meminta respons aman dari Laravel
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.ok || response.status === 302 || response.type === 'opaqueredirect') {
                        successCount++;
                    } else {
                        errorCount++;
                        let errText = await response.text();
                        console.error(`Gagal update ID ${targetId}. Status: ${response.status}`, errText);
                        alert(
                            `Terjadi Error pada saat update Satker ke-${i+1}!\n\nSilakan pastikan Model EpssNilai.php Anda sudah memiliki baris:\nprotected $guarded = [];`
                            );
                        break;
                    }
                } catch (e) {
                    errorCount++;
                    console.error("Fetch Error pada ID: " + targetId, e);
                    alert("Koneksi terputus atau Server Down!");
                    break;
                }
            }

            btn.innerText = "Simpan Seluruh Data Massal";
            btn.disabled = false;

            if (successCount > 0) {
                alert(
                    `Proses Selesai! Berhasil mengupdate ${successCount} dari ${bulkNilaiTargets.length} data Satker.`
                    );
                window.location.reload();
            }
        }

        // ============================================
        // LOGIC COPY PASTE EXCEL (MODAL SINGLE NILAI)
        // ============================================
        document.getElementById('pasteExcelNilai').addEventListener('input', function(e) {
            let pasteData = this.value;
            if (!pasteData) return;
            let values = pasteData.split(/\r\n|\n|\t/).map(v => v.trim()).filter(v => v !== '');

            for (let i = 0; i < indikators.length; i++) {
                let elementInput = document.getElementById('nil_' + indikators[i]);
                if (elementInput && values[i] !== undefined) {
                    let cleanStr = values[i].replace(/"/g, '').replace(',', '.');
                    let parsedValue = parseFloat(cleanStr);
                    if (!isNaN(parsedValue)) elementInput.value = parsedValue;
                }
            }
        });

        document.getElementById('formNilaiEl').addEventListener('submit', function() {
            document.getElementById('nil_id_kegiatan').disabled = false;
            document.getElementById('nil_id_tahapan').disabled = false;
            document.getElementById('nil_id_usulan_kegiatan').disabled = false;
        });

        // ============================================
        // LOGIC FILTER TABEL DIATAS 
        // ============================================
        function filterTableKegiatan(tahun) {
            document.querySelectorAll('#tableKegiatan tbody tr').forEach(row => {
                row.style.display = (tahun === "" || row.dataset.tahun == tahun) ? '' : 'none';
            });
        }

        function filterTableTahapan(idKeg) {
            document.querySelectorAll('#tableTahapan tbody tr').forEach(row => {
                row.style.display = (idKeg === "" || row.dataset.keg == idKeg) ? '' : 'none';
            });
        }

        function filterTableUsulan(idKeg) {
            document.querySelectorAll('#tableUsulan tbody tr').forEach(row => {
                row.style.display = (idKeg === "" || row.dataset.keg == idKeg) ? '' : 'none';
            });
        }

        function updateFilterNilaiThp(idKeg) {
            const thpSelect = document.getElementById('filterNilaiThp');
            thpSelect.innerHTML = '<option value="">-- Semua Tahapan --</option>';
            if (idKeg) {
                dataTahapan.filter(t => t.id_kegiatan == idKeg).forEach(t => {
                    thpSelect.innerHTML += `<option value="${t.id}">${t.tahapan}</option>`;
                });
            }
            filterTableNilai();
        }

        function filterTableNilai() {
            const idKeg = document.getElementById('filterNilaiKeg').value;
            const idThp = document.getElementById('filterNilaiThp').value;
            document.querySelectorAll('#tableNilai tbody tr').forEach(row => {
                const matchKeg = (idKeg === "" || row.dataset.keg == idKeg);
                const matchThp = (idThp === "" || row.dataset.thp == idThp);
                row.style.display = (matchKeg && matchThp) ? '' : 'none';
            });
        }

        // ============================================
        // LOGIC BUKA FORM BIASA
        // ============================================
        function bukaTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function updateModalNilaiDropdowns(idKeg) {
            const thpSelect = document.getElementById('nil_id_tahapan');
            const uslSelect = document.getElementById('nil_id_usulan_kegiatan');
            thpSelect.innerHTML = '<option value="">-- Pilih Tahapan --</option>';
            uslSelect.innerHTML = '<option value="">-- Pilih Usulan --</option>';
            if (idKeg) {
                dataTahapan.filter(t => t.id_kegiatan == idKeg).forEach(t => {
                    thpSelect.innerHTML += `<option value="${t.id}">${t.tahapan}</option>`;
                });
                dataUsulan.filter(u => u.id_kegiatan == idKeg).forEach(u => {
                    uslSelect.innerHTML +=
                        `<option value="${u.id}">(${u.kode_satker}) ${getNamaSatker(u.kode_satker)}</option>`;
                });
            }
        }

        function formKegiatan(tipe, dataStr = null) {
            let form = document.getElementById('formKegiatanEl');
            let method = document.getElementById('methodKegiatan');
            let title = document.getElementById('modalKegiatanTitle');
            if (tipe === 'add') {
                title.innerText = 'Tambah Kegiatan';
                method.value = 'POST';
                form.action = "{{ route('epss.kegiatan.store') }}";
                form.reset();
            } else {
                let data = JSON.parse(dataStr);
                title.innerText = 'Edit Kegiatan';
                method.value = 'PUT';
                form.action = "{{ url('/adminepss/kegiatan') }}/" + data.id;
                document.getElementById('keg_tahun').value = data.tahun;
                document.getElementById('keg_status').value = data.status;
            }
            document.getElementById('modalKegiatan').style.display = 'flex';
        }

        function formTahapan(tipe, dataStr = null) {
            let form = document.getElementById('formTahapanEl');
            let method = document.getElementById('methodTahapan');
            let title = document.getElementById('modalTahapanTitle');
            if (tipe === 'add') {
                title.innerText = 'Tambah Tahapan';
                method.value = 'POST';
                form.action = "{{ route('epss.tahapan.store') }}";
                form.reset();
            } else {
                let data = JSON.parse(dataStr);
                title.innerText = 'Edit Tahapan';
                method.value = 'PUT';
                form.action = "{{ url('/adminepss/tahapan') }}/" + data.id;
                document.getElementById('thp_id_kegiatan').value = data.id_kegiatan;
                document.getElementById('thp_tahapan').value = data.tahapan;
                document.getElementById('thp_tanggal_mulai').value = data.tanggal_mulai;
                document.getElementById('thp_tanggal_selesai').value = data.tanggal_selesai;
                document.getElementById('thp_status').value = data.status;
            }
            document.getElementById('modalTahapan').style.display = 'flex';
        }

        function formUsulan(tipe, dataStr = null) {
            let form = document.getElementById('formUsulanEl');
            let method = document.getElementById('methodUsulan');
            let title = document.getElementById('modalUsulanTitle');
            if (tipe === 'add') {
                title.innerText = 'Tambah Usulan';
                method.value = 'POST';
                form.action = "{{ route('epss.usulan.store') }}";
                form.reset();
            } else {
                let data = JSON.parse(dataStr);
                title.innerText = 'Edit Usulan';
                method.value = 'PUT';
                form.action = "{{ url('/adminepss/usulan') }}/" + data.id;
                document.getElementById('usl_id_kegiatan').value = data.id_kegiatan;
                document.getElementById('usl_kode_satker').value = data.kode_satker;
                document.getElementById('usl_kegiatan_1').value = data.kegiatan_1;
                document.getElementById('usl_nama_dinas_1').value = data.nama_dinas_1;
                document.getElementById('usl_tahun_1').value = data.tahun_1;
                document.getElementById('usl_kegiatan_2').value = data.kegiatan_2;
                document.getElementById('usl_nama_dinas_2').value = data.nama_dinas_2;
                document.getElementById('usl_tahun_2').value = data.tahun_2;
                document.getElementById('usl_kode_satker_penilai').value = data.kode_satker_penilai;
            }
            document.getElementById('modalUsulan').style.display = 'flex';
        }

        function formNilai(tipe, dataStr = null) {
            let form = document.getElementById('formNilaiEl');
            let method = document.getElementById('methodNilai');
            let title = document.getElementById('modalNilaiTitle');

            document.getElementById('pasteExcelNilai').value = '';
            const dropdownKeg = document.getElementById('nil_id_kegiatan');
            const dropdownThp = document.getElementById('nil_id_tahapan');
            const dropdownUsl = document.getElementById('nil_id_usulan_kegiatan');

            if (tipe === 'add') {
                title.innerText = 'Tambah Nilai';
                method.value = 'POST';
                form.action = "{{ route('epss.nilai.store') }}";
                form.reset();
                updateModalNilaiDropdowns('');
                dropdownKeg.disabled = false;
                dropdownThp.disabled = false;
                dropdownUsl.disabled = false;
            } else {
                let data = JSON.parse(dataStr);
                title.innerText = 'Edit Nilai';
                method.value = 'PUT';
                form.action = "{{ url('/adminepss/nilai') }}/" + data.id;

                dropdownKeg.value = data.id_kegiatan;
                updateModalNilaiDropdowns(data.id_kegiatan);
                dropdownThp.value = data.id_tahapan;
                dropdownUsl.value = data.id_usulan_kegiatan;

                dropdownKeg.disabled = true;
                dropdownThp.disabled = true;
                dropdownUsl.disabled = true;

                indikators.forEach(ind => {
                    document.getElementById('nil_' + ind).value = data[ind] || '';
                });
            }
            document.getElementById('modalNilai').style.display = 'flex';
        }
    </script>
</body>

</html>
