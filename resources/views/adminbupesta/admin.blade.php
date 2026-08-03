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
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-bupesta/admin.css') }}">

    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>

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

                {{-- 1. NOTIFIKASI HASIL AKSI --}}
                @if (session('success'))
                    <div
                        style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; border-left: 5px solid #28a745;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; border-left: 5px solid #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- 2. KOTAK UPLOAD FILE --}}
                <div
                    style="background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; text-align: left; border-top: 4px solid #f79039;">
                    <h3 style="margin-top: 0; color: #333; font-size: 1.2rem;">
                        <i class="fas fa-file-excel" style="color: #28a745;"></i> Upload & Import Data SIMPEG
                    </h3>
                    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

                    <form action="{{ route('simpeg.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 220px;">
                                <label
                                    style="display: block; font-weight: 600; margin-bottom: 8px; color: #555;">Tanggal
                                    Versi Data:</label>
                                <input type="date" name="tanggal_versidata" required
                                    style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif;">
                            </div>

                            <div style="flex: 2; min-width: 250px;">
                                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #555;">Upload
                                    File Excel (.xls / .xlsx):</label>
                                <input type="file" name="file_excel" accept=".xls,.xlsx" required
                                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif; background: #fafafa;">
                            </div>
                        </div>

                        <button type="submit"
                            style="margin-top: 18px; background-color: #f79039; color: white; border: none; padding: 10px 22px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.3s; box-shadow: 0 2px 4px rgba(247, 144, 57, 0.3);">
                            <i class="fas fa-cloud-upload-alt"></i> Upload & Simpan
                        </button>
                    </form>
                </div>

                {{-- 3. KOTAK TABEL & FILTER & HAPUS MASAL --}}
                <div
                    style="background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: left;">

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 20px;">
                        <div>
                            <h3 style="margin: 0; color: #333; font-size: 1.2rem;">
                                <i class="fas fa-users" style="color: #f79039;"></i> Data SIMPEG Pegawai
                            </h3>
                        </div>

                        <form action="{{ route('simpeg.index') }}" method="GET"
                            style="display: flex; align-items: center; gap: 10px;">
                            <label style="font-weight: 600; color: #555; white-space: nowrap;"><i
                                    class="fas fa-filter"></i> Filter Versi:</label>
                            <select name="filter_versi" onchange="this.form.submit()"
                                style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-family: 'Poppins', sans-serif;">
                                <option value="">-- Semua Versi Data --</option>
                                @if (isset($data['list_versi']))
                                    @foreach ($data['list_versi'] as $tgl)
                                        <option value="{{ $tgl }}"
                                            {{ $data['filter_versi'] == $tgl ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($tgl)->format('d-m-Y') }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            @if (!empty($data['filter_versi']))
                                <a href="{{ route('simpeg.index') }}"
                                    style="color: #6c757d; text-decoration: none; font-size: 0.85rem; padding: 8px 12px; background: #e9ecef; border-radius: 6px;"
                                    title="Reset Filter">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            @endif
                        </form>
                    </div>

                    @if (!empty($data['filter_versi']))
                        <div
                            style="background-color: #fff3cd; border: 1px solid #ffe8a1; color: #856404; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <i class="fas fa-info-circle"></i> Menampilkan data versi:
                                <strong>{{ \Carbon\Carbon::parse($data['filter_versi'])->format('d-m-Y') }}</strong>
                                (Total: {{ count($data['simpeg']) }} data)
                            </div>

                            <form action="{{ route('simpeg.delete_by_versi') }}" method="POST"
                                onsubmit="return confirm('⚠️ PERINGATAN BPS!\n\nApakah Anda yakin ingin menghapus SELURUH data SIMPEG untuk versi tanggal {{ \Carbon\Carbon::parse($data['filter_versi'])->format('d-m-Y') }}?\n\nTindakan ini tidak dapat dibatalkan!');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="tanggal_versidata" value="{{ $data['filter_versi'] }}">
                                <button type="submit"
                                    style="background-color: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.85rem; transition: 0.2s;">
                                    <i class="fas fa-trash-alt"></i> Hapus Semua Data Versi Ini
                                </button>
                            </form>
                        </div>
                    @endif

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 1100px; font-size: 0.88rem;">
                            <thead>
                                <tr style="background-color: #f4f6f9; color: #333; border-bottom: 2px solid #ddd;">
                                    <th style="padding: 10px; border: 1px solid #eee; text-align: center;">No</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">NIP BPS</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">NIP</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Nama</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Kode Org</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Jabatan</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Wilayah</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Gol. Akhir</th>
                                    <th style="padding: 10px; border: 1px solid #eee;">Status</th>
                                    <th style="padding: 10px; border: 1px solid #eee; text-align: center;">Versi Data
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($data['simpeg']) && count($data['simpeg']) > 0)
                                    @foreach ($data['simpeg'] as $index => $pegawai)
                                        <tr style="border-bottom: 1px solid #eee; transition: 0.2s;"
                                            onmouseover="this.style.backgroundColor='#fcfcfc'"
                                            onmouseout="this.style.backgroundColor='transparent'">
                                            <td style="padding: 10px; border: 1px solid #eee; text-align: center;">
                                                {{ $index + 1 }}</td>
                                            <td style="padding: 10px; border: 1px solid #eee;">{{ $pegawai->nip_bps }}
                                            </td>
                                            <td style="padding: 10px; border: 1px solid #eee;">{{ $pegawai->nip }}
                                            </td>
                                            <td style="padding: 10px; border: 1px solid #eee; font-weight: 500;">
                                                {{ $pegawai->nama }}</td>
                                            <td style="padding: 10px; border: 1px solid #eee;">
                                                {{ $pegawai->kode_org }}</td>
                                            <td style="padding: 10px; border: 1px solid #eee;">{{ $pegawai->jabatan }}
                                            </td>
                                            <td style="padding: 10px; border: 1px solid #eee;">{{ $pegawai->wilayah }}
                                            </td>
                                            <td style="padding: 10px; border: 1px solid #eee;">
                                                {{ $pegawai->gol_akhir }}</td>
                                            <td style="padding: 10px; border: 1px solid #eee;">{{ $pegawai->status }}
                                            </td>
                                            <td style="padding: 10px; border: 1px solid #eee; text-align: center;">
                                                <span
                                                    style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; color: #555; white-space: nowrap;">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ \Carbon\Carbon::parse($pegawai->tanggal_versidata)->format('d-m-Y') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10"
                                            style="padding: 30px; text-align: center; border: 1px solid #eee; color: #888;">
                                            <i class="fas fa-folder-open"
                                                style="font-size: 2.2rem; color: #ccc; margin-bottom: 10px; display: block;"></i>
                                            Tidak ada data SIMPEG ditemukan untuk versi ini.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            <br>
            <footer>
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-bupesta/admin.js') }}"></script>

</body>

</html>
