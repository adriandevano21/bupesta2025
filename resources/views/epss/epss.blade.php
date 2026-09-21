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

            <div class="posisitengah">

                @php
                    // Mencari nama tahapan utama yang sedang dipilih untuk ditampilkan di header tabel
                    $nama_tahapan_utama = '';
                    if (!empty($data['list_tahapan'])) {
                        foreach ($data['list_tahapan'] as $thp) {
                            if ($thp->id == $data['tahapan_dipilih']) {
                                $nama_tahapan_utama = $thp->tahapan;
                                break;
                            }
                        }
                    }
                @endphp

                <div
                    style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 1150px; margin: 0 auto; border-top: 4px solid #ea580c;">

                    <div style="text-align: center; margin-bottom: 25px;">
                        <h2
                            style="margin: 0; color: #9a3412; font-size: 1.7rem; font-weight: 700; letter-spacing: 0.5px;">
                            Daftar Nilai EPSS</h2>
                        <p style="color: #64748b; margin-top: 5px; font-size: 0.95rem;">Evaluasi Penyelenggaraan
                            Statistik Sektoral</p>
                    </div>

                    <!-- Area Filter Utama -->
                    <div
                        style="margin-bottom: 25px; background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <form action="{{ url()->current() }}" method="GET"
                            style="margin: 0; display: flex; flex-direction: column; gap: 15px;">
                            <input type="hidden" name="sort_by" value="{{ $data['sort_by'] }}">
                            <input type="hidden" name="sort_order" value="{{ $data['sort_order'] }}">

                            <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label for="filter_tahun"
                                        style="font-weight: 600; color: #334155; font-size: 0.95rem;">Tahun
                                        Utama:</label>
                                    <select name="tahun" id="filter_tahun" onchange="this.form.submit()"
                                        style="padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: white; color: #1e293b; outline: none; font-weight: 500; cursor: pointer;">
                                        @foreach ($data['list_tahun'] as $t)
                                            <option value="{{ $t }}"
                                                {{ $data['tahun_dipilih'] == $t ? 'selected' : '' }}>
                                                {{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label for="filter_tahapan"
                                        style="font-weight: 600; color: #334155; font-size: 0.95rem;">Tahapan
                                        Utama:</label>
                                    <select name="tahapan" id="filter_tahapan" onchange="this.form.submit()"
                                        style="padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: white; color: #1e293b; outline: none; font-weight: 500; min-width: 220px; cursor: pointer;">
                                        @foreach ($data['list_tahapan'] as $thp)
                                            <option value="{{ $thp->id }}"
                                                {{ $data['tahapan_dipilih'] == $thp->id ? 'selected' : '' }}>
                                                {{ $thp->tahapan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 5px 0;">

                            <!-- Area Filter Pembanding -->
                            <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
                                <label
                                    style="font-weight: 600; color: #ea580c; display: flex; align-items: center; gap: 8px; cursor: pointer; background: #fff7ed; padding: 8px 14px; border-radius: 6px; border: 1px solid #ffedd5; transition: all 0.2s;">
                                    <input type="checkbox" name="compare_aktif" value="1"
                                        onchange="this.form.submit()" {{ $data['is_compare'] ? 'checked' : '' }}
                                        style="accent-color: #ea580c; width: 16px; height: 16px; cursor: pointer;">
                                    Bandingkan Data
                                </label>

                                @if ($data['is_compare'])
                                    <div
                                        style="display: flex; align-items: center; gap: 10px; border-left: 3px solid #e2e8f0; padding-left: 15px;">
                                        <label style="font-weight: 600; color: #475569; font-size: 0.95rem;">Tahun
                                            Pembanding:</label>
                                        <select name="compare_tahun" onchange="this.form.submit()"
                                            style="padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; outline: none; cursor: pointer;">
                                            <option value="">-- Pilih Tahun --</option>
                                            @foreach ($data['list_tahun'] as $t)
                                                <option value="{{ $t }}"
                                                    {{ $data['compare_tahun'] == $t ? 'selected' : '' }}>
                                                    {{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($data['compare_tahun'])
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <label style="font-weight: 600; color: #475569; font-size: 0.95rem;">Tahapan
                                                Pembanding:</label>
                                            <select name="compare_tahapan" onchange="this.form.submit()"
                                                style="padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; outline: none; cursor: pointer;">
                                                <option value="">-- Pilih Tahapan --</option>
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

                    <!-- Tabel Data -->
                    <div
                        style="overflow-x: auto; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th
                                        style="background: #f8fafc; color: #334155; padding: 14px; text-align: center; width: 5%; border-bottom: 2px solid #cbd5e1; border-right: 1px solid #e2e8f0;">
                                        No</th>

                                    <th
                                        style="background: #f8fafc; border-bottom: 2px solid #cbd5e1; padding: 14px; border-right: 1px solid #e2e8f0;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'kode_satker', 'sort_order' => $data['sort_by'] == 'kode_satker' && $data['sort_order'] == 'desc' ? 'asc' : 'desc']) }}"
                                            style="color: #334155; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-weight: 700;">
                                            Kode Satker
                                            <i class="fa-solid fa-sort{{ $data['sort_by'] == 'kode_satker' ? ($data['sort_order'] == 'desc' ? '-down' : '-up') : '' }}"
                                                style="color: {{ $data['sort_by'] == 'kode_satker' ? '#ea580c' : '#cbd5e1' }};"></i>
                                        </a>
                                    </th>

                                    <th
                                        style="background: #f8fafc; border-bottom: 2px solid #cbd5e1; padding: 14px; border-right: 1px solid #e2e8f0;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_satker', 'sort_order' => $data['sort_by'] == 'nama_satker' && $data['sort_order'] == 'desc' ? 'asc' : 'desc']) }}"
                                            style="color: #334155; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-weight: 700;">
                                            Nama Satuan Kerja
                                            <i class="fa-solid fa-sort{{ $data['sort_by'] == 'nama_satker' ? ($data['sort_order'] == 'desc' ? '-down' : '-up') : '' }}"
                                                style="color: {{ $data['sort_by'] == 'nama_satker' ? '#ea580c' : '#cbd5e1' }};"></i>
                                        </a>
                                    </th>

                                    <!-- Kolom Nilai Utama dengan Keterangan Filter -->
                                    <th
                                        style="background: #fff7ed; border-bottom: 2px solid #fed7aa; padding: 10px 14px; vertical-align: middle;">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nilai_epss', 'sort_order' => $data['sort_by'] == 'nilai_epss' && $data['sort_order'] == 'desc' ? 'asc' : 'desc']) }}"
                                            style="color: #9a3412; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                            <div style="text-align: left; line-height: 1.3;">
                                                <span style="font-weight: 800;">Nilai Utama</span><br>
                                                <span
                                                    style="font-size: 0.75rem; font-weight: 600; color: #ea580c; background: #ffedd5; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                                    <i class="fa-regular fa-calendar" style="margin-right: 3px;"></i>
                                                    {{ $data['tahun_dipilih'] }} |
                                                    {{ $nama_tahapan_utama ?: 'Belum Dipilih' }}
                                                </span>
                                            </div>
                                            <i class="fa-solid fa-sort{{ $data['sort_by'] == 'nilai_epss' ? ($data['sort_order'] == 'desc' ? '-down' : '-up') : '' }}"
                                                style="color: {{ $data['sort_by'] == 'nilai_epss' ? '#ea580c' : '#fdba74' }}; font-size: 1.1rem;"></i>
                                        </a>
                                    </th>

                                    @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                        <!-- Kolom Nilai Banding dengan Keterangan Filter -->
                                        <th
                                            style="background: #fffbeb; border-bottom: 2px solid #fde68a; padding: 10px 14px; border-left: 2px solid #e2e8f0; vertical-align: middle;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nilai_compare', 'sort_order' => $data['sort_by'] == 'nilai_compare' && $data['sort_order'] == 'desc' ? 'asc' : 'desc']) }}"
                                                style="color: #b45309; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                                <div style="text-align: left; line-height: 1.3;">
                                                    <span style="font-weight: 800;">Nilai Banding</span><br>
                                                    <span
                                                        style="font-size: 0.75rem; font-weight: 600; color: #d97706; background: #fef3c7; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                                        <i class="fa-regular fa-calendar"
                                                            style="margin-right: 3px;"></i>
                                                        {{ $data['compare_tahun'] }} |
                                                        {{ $data['compare_tahapan_nama'] }}
                                                    </span>
                                                </div>
                                                <i class="fa-solid fa-sort{{ $data['sort_by'] == 'nilai_compare' ? ($data['sort_order'] == 'desc' ? '-down' : '-up') : '' }}"
                                                    style="color: {{ $data['sort_by'] == 'nilai_compare' ? '#d97706' : '#fde68a' }}; font-size: 1.1rem;"></i>
                                            </a>
                                        </th>

                                        <!-- Kolom Selisih -->
                                        <th
                                            style="background: #fffbeb; border-bottom: 2px solid #fde68a; padding: 14px; border-left: 1px solid #e2e8f0;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'selisih', 'sort_order' => $data['sort_by'] == 'selisih' && $data['sort_order'] == 'desc' ? 'asc' : 'desc']) }}"
                                                style="color: #b45309; text-decoration: none; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-weight: 800;">
                                                Selisih
                                                <i class="fa-solid fa-sort{{ $data['sort_by'] == 'selisih' ? ($data['sort_order'] == 'desc' ? '-down' : '-up') : '' }}"
                                                    style="color: {{ $data['sort_by'] == 'selisih' ? '#d97706' : '#fde68a' }};"></i>
                                            </a>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['nilai_epss'] as $index => $item)
                                    <!-- Pewarnaan baris otomatis (Zebra striping) -->
                                    <tr
                                        style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: background 0.1s;">
                                        <td
                                            style="padding: 12px; text-align: center; color: #64748b; border-right: 1px solid #e2e8f0;">
                                            {{ $index + 1 }}</td>
                                        <td
                                            style="padding: 12px; text-align: center; font-weight: 600; color: #334155; border-right: 1px solid #e2e8f0;">
                                            {{ $item->kode_satker }}</td>
                                        <td
                                            style="padding: 12px; font-weight: 500; color: #1e293b; border-right: 1px solid #e2e8f0;">
                                            {{ $item->nama_satker }}</td>
                                        <td
                                            style="padding: 12px; text-align: center; font-weight: 700; color: #ea580c; font-size: 1.05rem; background: {{ $loop->even ? '#fff7ed' : '#ffffff' }};">
                                            {{ number_format($item->nilai_epss, 2) }}
                                        </td>

                                        @if ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan'])
                                            <td
                                                style="padding: 12px; text-align: center; font-weight: 600; color: #475569; border-left: 2px solid #e2e8f0;">
                                                {{ $item->nilai_compare !== null ? number_format($item->nilai_compare, 2) : '-' }}
                                            </td>
                                            <td
                                                style="padding: 12px; text-align: center; font-weight: bold; font-size: 1.05rem; border-left: 1px solid #e2e8f0;">
                                                @if ($item->selisih !== null)
                                                    @if ($item->selisih > 0)
                                                        <span
                                                            style="color: #15803d; background: #dcfce7; padding: 4px 8px; border-radius: 6px;">+{{ number_format($item->selisih, 2) }}
                                                            <i class="fa-solid fa-arrow-trend-up"
                                                                style="font-size: 0.85rem;"></i></span>
                                                    @elseif($item->selisih < 0)
                                                        <span
                                                            style="color: #b91c1c; background: #fee2e2; padding: 4px 8px; border-radius: 6px;">{{ number_format($item->selisih, 2) }}
                                                            <i class="fa-solid fa-arrow-trend-down"
                                                                style="font-size: 0.85rem;"></i></span>
                                                    @else
                                                        <span
                                                            style="color: #64748b; background: #f1f5f9; padding: 4px 8px; border-radius: 6px;">0.00
                                                            <i class="fa-solid fa-minus"
                                                                style="font-size: 0.85rem;"></i></span>
                                                    @endif
                                                @else
                                                    <span style="color: #94a3b8;">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <!-- Update rentang kolom berdasarkan filter aktif -->
                                        @php $colspan = ($data['is_compare'] && $data['compare_tahun'] && $data['compare_tahapan']) ? 6 : 4; @endphp
                                        <td colspan="{{ $colspan }}"
                                            style="padding: 40px; text-align: center; color: #94a3b8;">
                                            <i class="fa-solid fa-folder-open"
                                                style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                                            <br>
                                            <span style="font-weight: 600; color: #64748b; font-size: 1.05rem;">Tidak
                                                ada data penilaian yang ditemukan.</span>
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
