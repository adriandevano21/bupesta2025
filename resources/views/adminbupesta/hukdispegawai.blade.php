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
    <link rel="stylesheet" href="{{ asset('assets-bupesta/hukdis.css') }}">
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

            <div class="posisitengah">

                {{-- Notifikasi Sukses/Gagal --}}
                @if (session('success'))
                    <div
                        style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- KOTAK AREA INPUT COPY PASTE --}}
                <div
                    style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3 style="margin-bottom: 15px; color: var(--warna-utama, #f79039);"><i
                            class="fas fa-file-excel"></i> Tambah Data Hukdis (Dari Excel)</h3>
                    <p style="margin-bottom: 15px; font-size: 0.9rem; color: #6b7280;">
                        1. Buka file Excel Anda.<br>
                        2. Blok (Pilih) baris data yang ingin dimasukkan (Pastikan berurutan: <b>NIP BPS, Nama, Satker,
                            Jenis, Hukuman, TMT Mulai</b>). Jangan copy baris judul/header-nya.<br>
                        3. Paste ke dalam kotak di bawah ini, lalu klik Simpan.
                    </p>

                    <form action="{{ route('hukdispegawai.store') }}" method="POST">
                        @csrf
                        <textarea id="excel_data" name="excel_data" rows="6" required
                            style="width: 100%; padding: 15px; border: 2px dashed #cbd5e1; border-radius: 8px; font-family: monospace; margin-bottom: 15px; background: #f8fafc;"
                            placeholder="Klik kanan dan Paste (Tempel) data dari Excel di sini..."></textarea>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="previewExcel()"
                                style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-eye"></i> Preview Dulu
                            </button>
                            <button type="submit" class="btn-simpan" style="padding: 10px 20px; cursor: pointer;">
                                <i class="fas fa-save"></i> Simpan ke Database
                            </button>
                        </div>
                    </form>

                    {{-- Tabel Preview Cerdas (Disembunyikan secara default) --}}
                    <div id="preview-area" style="display: none; margin-top: 20px; overflow-x: auto;">
                        <h4 style="margin-bottom: 10px;">Pratinjau Data (Sebelum Disimpan):</h4>
                        <table style="width: 100%; border-collapse: collapse; min-width: 800px; font-size: 0.85rem;"
                            border="1">
                            <thead style="background: var(--warna-utama, #f79039); color: white;">
                                <tr>
                                    <th style="padding: 8px;">NIP BPS</th>
                                    <th style="padding: 8px;">Nama</th>
                                    <th style="padding: 8px;">Satker</th>
                                    <th style="padding: 8px;">Jenis</th>
                                    <th style="padding: 8px;">Hukuman</th>
                                    <th style="padding: 8px;">TMT Mulai</th>
                                </tr>
                            </thead>
                            <tbody id="preview-tbody" style="background: #fff; text-align: center;"></tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL DATA YANG SUDAH MASUK DATABASE --}}
                <div
                    style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow-x: auto;">

                    {{-- Header Tabel & Tombol Hapus --}}
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        <h3 style="margin: 0;"><i class="fas fa-list"></i> Daftar Riwayat Hukuman Disiplin</h3>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="hapusTerpilih()"
                                style="padding: 8px 15px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-trash-alt"></i> Hapus Terpilih
                            </button>
                            <button type="button" onclick="hapusSemua()"
                                style="padding: 8px 15px; background: #7f1d1d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-dumpster"></i> Kosongkan Tabel
                            </button>
                        </div>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; min-width: 900px; font-size: 0.9rem;">
                        <thead style="background: #f1f5f9;">
                            <tr>
                                <th style="padding: 12px; border: 1px solid #e2e8f0; text-align: center; width: 40px;">
                                    <input type="checkbox" id="checkAll" style="cursor: pointer;">
                                </th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">NIP BPS</th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">Nama Pegawai</th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">Satker</th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">Jenis Hukuman</th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">Hukuman</th>
                                <th style="padding: 12px; border: 1px solid #e2e8f0;">TMT Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['hukdis'] ?? [] as $row)
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 10px; text-align: center;">
                                        <input type="checkbox" class="checkItem" value="{{ $row->id }}"
                                            style="cursor: pointer;">
                                    </td>
                                    <td style="padding: 10px;">{{ $row->nip_bps }}</td>
                                    <td style="padding: 10px;">{{ $row->nama }}</td>
                                    <td style="padding: 10px; text-align: center;">{{ $row->satker }}</td>
                                    <td style="padding: 10px; text-align: center;">
                                        <span
                                            style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">
                                            {{ $row->jenis }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px;">{{ $row->hukuman }}</td>
                                    <td style="padding: 10px; text-align: center;">{{ $row->tmt_mulai }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">
                                        Belum ada data hukuman disiplin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
    <script src="{{ asset('assets-bupesta/hukdis.js') }}"></script>

</body>

</html>
