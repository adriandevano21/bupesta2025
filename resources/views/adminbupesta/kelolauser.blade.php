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
    <link rel="stylesheet" href="{{ asset('assets-bupesta/kelolauser.css') }}">
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

                @if (session('success'))
                    <div class="alert alert-success alert-modern">
                        <i class="fa-solid fa-circle-check"></i>
                        <div>
                            <strong>Berhasil!</strong><br>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="header-modern">
                    <div class="teks-header">
                        <h2><i class="fa-solid fa-users-gear text-primary"></i> Manajemen Pengguna</h2>
                        <p>Kelola akses dan sinkronisasi data pegawai dengan sistem SIMPEG. <br>
                            <b>Tips:</b> Klik pada teks bergaris bawah untuk mengedit secara langsung.
                        </p>
                    </div>

                    <div class="filter-wrapper">
                        <label for="filter_satker"><i class="fa-solid fa-filter"></i> Satker:</label>
                        <div class="select-modern">
                            <select id="filter_satker" class="input-form">
                                <option value="">Semua Wilayah</option>
                                @foreach ($data['list_satker'] as $satker)
                                    <option value="{{ $satker }}">{{ $satker }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down select-icon"></i>
                        </div>
                    </div>
                </div>

                {{-- KARTU TABEL MODERN --}}
                <div class="kartu-tabel">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="tabel-pengguna" class="table-bupesta modern-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="sticky-col sticky-col-1">Profil Pegawai</th>
                                    <th colspan="2" class="text-center group-header text-blue">Satuan Kerja</th>
                                    <th colspan="2" class="text-center group-header text-orange">Jabatan</th>
                                    <th colspan="2" class="text-center group-header text-green">Golongan</th>
                                    <th colspan="5" class="text-center group-header text-purple border-left">Akses
                                        Aplikasi</th>
                                    <th rowspan="2" class="align-middle text-center border-left">Status</th>
                                </tr>
                                <tr class="sub-header">
                                    <th class="col-user">Data App</th>
                                    <th class="col-simpeg border-right">Data SIMPEG</th>
                                    <th class="col-user">Data App</th>
                                    <th class="col-simpeg border-right">Data SIMPEG</th>
                                    <th class="col-user">Data App</th>
                                    <th class="col-simpeg">Data SIMPEG</th>
                                    {{-- Kolom Aplikasi Dipecah Jelas --}}
                                    <th class="col-role border-left">BuPeSta</th>
                                    <th class="col-role">Jazirah</th>
                                    <th class="col-role">Cinema</th>
                                    <th class="col-role">Kinerja</th>
                                    <th class="col-role">IST</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['users'] as $u)
                                    @php
                                        $isSatkerSama = $u->kode_satker == $u->kode_wilayah;
                                        $isJabatanSama =
                                            strtolower(trim($u->jabatan ?? '')) ==
                                            strtolower(trim($u->jabatan_simpeg ?? ''));
                                        $isGolonganSama =
                                            strtolower(trim($u->golongan ?? '')) ==
                                            strtolower(trim($u->golongan_simpeg ?? ''));
                                        $isSinkron =
                                            $u->kode_wilayah && $isSatkerSama && $isJabatanSama && $isGolonganSama;
                                    @endphp
                                    <tr>
                                        {{-- PROFIL PEGAWAI (DIFREEZE / STICKY) --}}
                                        <td class="sticky-col sticky-col-1 border-right">
                                            <div class="profil-ringkas">
                                                <div class="avatar-inisial">{{ substr($u->name ?? 'A', 0, 1) }}</div>
                                                <div class="detail-info">
                                                    <span
                                                        class="nama-peg">{{ $u->nama_simpeg ?? ($u->name ?? 'Kosong') }}</span>
                                                    <span class="nip-peg">{{ $u->nip_pegawai }}</span>
                                                    <span class="username-peg"><i class="fa-solid fa-at"></i>
                                                        {{ $u->username }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- SATKER --}}
                                        <td class="col-user">
                                            <span class="editable-cell {{ $isSatkerSama ? 'match' : 'mismatch' }}"
                                                data-inline-edit="true" data-nip="{{ $u->nip_pegawai }}"
                                                data-kolom="kode_satker">
                                                {{ $u->kode_satker ?: 'Klik_Isi' }}
                                            </span>
                                        </td>
                                        <td class="col-simpeg border-right">
                                            <span class="badge-simpeg">{{ $u->kode_wilayah ?: '-' }}</span>
                                            <div class="teks-kecil mt-1">{{ $u->satker_simpeg ?: '-' }}</div>
                                        </td>

                                        {{-- JABATAN --}}
                                        <td class="col-user">
                                            <span class="editable-cell {{ $isJabatanSama ? 'match' : 'mismatch' }}"
                                                data-inline-edit="true" data-nip="{{ $u->nip_pegawai }}"
                                                data-kolom="jabatan">
                                                {{ $u->jabatan ?: 'Klik_Isi' }}
                                            </span>
                                        </td>
                                        <td class="col-simpeg border-right">
                                            <div class="teks-kecil">{{ $u->jabatan_simpeg ?: '-' }}</div>
                                        </td>

                                        {{-- GOLONGAN --}}
                                        <td class="col-user">
                                            <span class="editable-cell {{ $isGolonganSama ? 'match' : 'mismatch' }}"
                                                data-inline-edit="true" data-nip="{{ $u->nip_pegawai }}"
                                                data-kolom="golongan">
                                                {{ $u->golongan ?: 'Klik_Isi' }}
                                            </span>
                                        </td>
                                        <td class="col-simpeg">
                                            <strong>{{ $u->golongan_simpeg ?: '-' }}</strong>
                                        </td>

                                        {{-- AKSES (ROLE) SEMUA APLIKASI SEJAJAR --}}
                                        <td class="col-role border-left">
                                            <span class="editable-cell fw-bold text-dark" data-inline-edit="true"
                                                data-nip="{{ $u->nip_pegawai }}" data-kolom="bupesta">
                                                {{ $u->bupesta ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="col-role">
                                            <span class="editable-cell" data-inline-edit="true"
                                                data-nip="{{ $u->nip_pegawai }}" data-kolom="jazirah">
                                                {{ $u->jazirah ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="col-role">
                                            <span class="editable-cell" data-inline-edit="true"
                                                data-nip="{{ $u->nip_pegawai }}" data-kolom="cinema">
                                                {{ $u->cinema ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="col-role">
                                            <span class="editable-cell" data-inline-edit="true"
                                                data-nip="{{ $u->nip_pegawai }}" data-kolom="kinerja">
                                                {{ $u->kinerja ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="col-role">
                                            <span class="editable-cell" data-inline-edit="true"
                                                data-nip="{{ $u->nip_pegawai }}" data-kolom="ist">
                                                {{ $u->ist ?: '-' }}
                                            </span>
                                        </td>

                                        {{-- STATUS SINKRON --}}
                                        <td class="text-center border-left bg-light">
                                            @if ($isSinkron)
                                                <div class="status-pill status-ok"><i class="fa-solid fa-check"></i>
                                                    Sama</div>
                                            @else
                                                <div class="status-pill status-bad"><i class="fa-solid fa-xmark"></i>
                                                    Beda</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
            <link rel="stylesheet"
                href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const updateUrl = "{{ route('kelolauser.updateInline') }}";

                    if ($.fn.DataTable) {
                        var table = $('#tabel-pengguna').DataTable({
                            destroy: true,
                            pageLength: 10,
                            lengthMenu: [
                                [10, 25, 50, -1],
                                [10, 25, 50, "Semua Data"]
                            ],
                            scrollX: true, // Scroll Horizontal
                            fixedColumns: {
                                leftColumns: 1 // FREEZE KOLOM PERTAMA (Profil)
                            },
                            autoWidth: false,
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Cari Nama / NIP...",
                                lengthMenu: "Tampil _MENU_ baris",
                                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                                paginate: {
                                    first: "«",
                                    last: "»",
                                    next: "›",
                                    previous: "‹"
                                }
                            }
                        });

                        // FILTER SATKER (Data App - Kolom ke-2)
                        $('#filter_satker').on('change', function() {
                            var val = $(this).val();
                            table.column(1).search(val).draw();
                        });
                    }

                    // LOGIKA INLINE EDITING
                    $(document).on('click', '.editable-cell', function() {
                        let $el = $(this);
                        if ($el.find('input').length > 0) return;

                        let currentValue = $el.text().trim();
                        if (currentValue === 'Klik_Isi' || currentValue === '-') currentValue = '';

                        let isJabatan = $el.data('kolom') === 'jabatan';
                        let inputHtml =
                            `<input type="text" class="inline-input ${isJabatan ? 'input-lebar' : ''}" value="${currentValue}" />`;

                        $el.html(inputHtml);
                        let $input = $el.find('input');
                        $input.focus();

                        $input.on('blur keypress', function(e) {
                            if (e.type === 'keypress' && e.which !== 13) return;

                            let newValue = $(this).val().trim();
                            let nip = $el.data('nip');
                            let kolom = $el.data('kolom');

                            if (newValue === currentValue) {
                                $el.html(newValue || 'Klik_Isi');
                                return;
                            }

                            $input.addClass('saving');

                            $.ajax({
                                url: updateUrl,
                                method: 'POST',
                                data: {
                                    _token: csrfToken,
                                    nip_pegawai: nip,
                                    kolom: kolom,
                                    value_baru: newValue
                                },
                                success: function(res) {
                                    if (res.success) {
                                        $el.html(newValue || '-');
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Tersimpan!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                },
                                error: function() {
                                    alert("Gagal menyimpan!");
                                    $el.html(currentValue || 'Klik_Isi');
                                }
                            });
                        });
                    });
                });
            </script>

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
    <script src="{{ asset('assets-bupesta/kelolauser.js') }}"></script>

</body>

</html>
