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
</head>

<body>

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

                <div class="d-flex align-items-center justify-content-between mb-3">

                    @php
                        $pilars = ['I', 'II', 'III', 'IV', 'V', 'VI'];
                        $selectedPilar = (string) ($data['pilar'] ?? '');
                        $selectedSatker = (string) ($data['satker_selected'] ?? '');
                    @endphp

                    <form method="GET" action="{{ url()->current() }}" class="filters-mini">
                        <div class="filters-mini__row">

                            <div class="filters-mini__group">
                                <label class="filters-mini__label">Pilar</label>
                                <select name="pilar" class="filters-mini__select filters-mini__select--pilar">
                                    <option value="">Semua</option>
                                    @foreach ($pilars as $p)
                                        <option value="{{ $p }}"
                                            {{ $selectedPilar === $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                                    <tr style="align-items: center;">
                                        {{-- <th></th> --}}
                                        <th style="min-width:210px">Rencana Kerja</th>
                                        <th style="min-width:120px">Rencana Aksi</th>
                                        <th style="min-width:120px">Output</th>
                                        <th style="min-width:120px">Penanggung Jawab</th>
                                        <th style="width:100px">Target</th>
                                        <th style="width:100px">Realisasi</th>
                                        <th style="min-width:50px">Dokumen</th>
                                        {{-- <th style="width:140px">Progress</th> --}}
                                        <th style="min-width:100px">Status Dokumen</th>
                                        <th style="min-width:220px">Pemeriksaan</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['indikator'] ?? [] as $row)
                                        @php
                                            $lvl = (int) ($row->level ?? 1);
                                            $lvl = max(1, min(5, $lvl));
                                            $text = $row->rencana_kerja ?? ($row->rencana_kerja ?? '-');
                                            $status = $row->status ?? null; // sesuaikan
                                        @endphp

                                        <tr>
                                            {{-- <td>
                                                @if ($row->pengisian === 1)
                                                    @if ($data['user_active'][0]->role === 'admin' or $data['user_active'][0]->role === 'operator')
                                                        <button type="button"
                                                            class="btn btn-tiny btn-light btn-icon-soft js-edit-isian"
                                                            title="Edit" aria-label="Edit" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditIsian"
                                                            data-id="{{ $row->isian->id ?? '' }}"
                                                            data-rencana_kerja="{{ $row->rencana_kerja ?? '' }}"
                                                            data-penanggungjawab="{{ $row->isian->penanggungjawab ?? '' }}"
                                                            data-bulan-target="{{ $row->isian->bulan_target ?? '' }}"
                                                            data-bulan-realisasi="{{ $row->isian->bulan_realisasi ?? '' }}"
                                                            data-link_buktidukung="{{ $row->isian->link_buktidukung ?? '' }}"
                                                            data-status-approval="{{ $row->isian->status_approval ?? '' }}"
                                                            data-status-tindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                            data-komentar-evaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                            data-komentar-operator1="{{ $row->isian->komentar_operator1 ?? '' }}"
                                                            @if ($row->isian->status_approval === 'Sudah Sesuai') disabled @endif>
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </td> --}}
                                            {{-- data-order biar sorting tetap rapi walau ada bullet/indent --}}
                                            <td data-order="{{ strip_tags($text) }}">
                                                <div class="lvl-wrap lvl-{{ $lvl }}"
                                                    style="--lvl: {{ $lvl }};">
                                                    <div class="lvl-chip">
                                                        <span class="lvl-text">{{ $text }}</span>
                                                    </div>
                                                    @if ($row->pengisian === 1)
                                                        @if ($data['user_active'][0]->role === 'admin' or $data['user_active'][0]->role === 'operator')
                                                            <hr>
                                                            <button type="button"
                                                                class="btn btn-edit-pill js-edit-isian1"
                                                                title="Edit Rencana Aksi,Output, PJ dan Target"
                                                                aria-label="Edit" data-bs-toggle="modal"
                                                                data-bs-target="#modalEditIsian1"
                                                                data-id="{{ $row->isian->id ?? '' }}"
                                                                data-rencana_kerja="{{ $row->rencana_kerja ?? '' }}"
                                                                data-rencanaaksi="{{ $row->isian->rencanaaksi ?? '' }}"
                                                                data-output="{{ $row->isian->output ?? '' }}"
                                                                data-penanggungjawab="{{ $row->isian->penanggungjawab ?? '' }}"
                                                                data-bulan-target="{{ $row->isian->bulan_target ?? '' }}">
                                                                <i class="bi bi-pencil-square"></i><span> Due:
                                                                    Rabu, 1
                                                                    April 2026</span>
                                                            </button>
                                                        @endif
                                                    @endif
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
                                                        <button type="button" class="btn btn-edit-pill">
                                                            <a href="{{ $row->isian->link_buktidukung }}"
                                                                target="_blank" style="text-decoration: none">
                                                                <i class="bi bi-eye-fill"></i><span> Tersedia</span>
                                                            </a>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-edit-pill" disabled>
                                                            <i class="bi bi-eye-slash-fill"></i><span>
                                                                Belum Ada</span>
                                                        </button>
                                                    @endif
                                                    @if ($data['user_active'][0]->role === 'admin' or $data['user_active'][0]->role === 'operator')
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
                                                    Progress
                                                @endif
                                            </td>

                                            {{-- <td>
                                                @if ($row->pengisian === 1)
                                                    @php
                                                        $statusApproval =
                                                            $row->isian->status_approval ?? 'Belum Upload';
                                                    @endphp

                                                    @if ($statusApproval === 'Belum Upload')
                                                        <button type="button" class="btn btn-tiny btn-tiny-danger"
                                                            disabled>
                                                            <i class="bi bi-x-lg"></i>
                                                            <span>Belum Upload</span>
                                                        </button>
                                                    @elseif ($statusApproval === 'Sudah Upload')
                                                        <button type="button"
                                                            class="btn btn-tiny btn-tiny-success js-edit-isian2"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian2"
                                                            data-id="{{ $row->isian->id ?? '' }}"
                                                            data-statusapproval="{{ $row->isian->status_approval ?? '' }}"
                                                            data-statustindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                            data-komentarevaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                            data-komentaroperator1="{{ $row->isian->komentar_operator1 ?? '' }}"
                                                            @if (!in_array($data['user_active'][0]->role, ['evaluator', 'admin'])) disabled @endif>
                                                            <i class="bi bi-check2"></i>
                                                            <span>Link Tersedia</span>
                                                        </button>
                                                    @elseif ($statusApproval === 'Dievaluasi')
                                                        @if (in_array($data['user_active'][0]->role, ['operator']))
                                                            <button type="button"
                                                                class="btn btn-tiny btn-tiny-warning js-edit-isian3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditIsian3"
                                                                data-id="{{ $row->isian->id ?? '' }}"
                                                                data-statusapproval="{{ $row->isian->status_approval ?? '' }}"
                                                                data-statustindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                                data-komentarevaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                                data-komentaroperator1="{{ $row->isian->komentar_operator1 ?? '' }}">
                                                                <i class="bi bi-search"></i>
                                                                <span>Terdapat Evaluasi</span>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-tiny btn-tiny-warning js-edit-isian2"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEditIsian2"
                                                                data-id="{{ $row->isian->id ?? '' }}"
                                                                data-statusapproval="{{ $row->isian->status_approval ?? '' }}"
                                                                data-statustindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                                data-komentarevaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                                data-komentaroperator1="{{ $row->isian->komentar_operator1 ?? '' }}">
                                                                <i class="bi bi-search"></i>
                                                                <span>Terdapat Evaluasi</span>
                                                            </button>
                                                        @endif
                                                    @elseif ($statusApproval === 'Sudah Tindak Lanjut')
                                                        <button type="button"
                                                            class="btn btn-tiny btn-tiny-info js-edit-isian2"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian2"
                                                            data-id="{{ $row->isian->id ?? '' }}"
                                                            data-statusapproval="{{ $row->isian->status_approval ?? '' }}"
                                                            data-statustindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                            data-komentarevaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                            data-komentaroperator1="{{ $row->isian->komentar_operator1 ?? '' }}">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            <span>Sudah Tindak Lanjut</span>
                                                        </button>
                                                    @elseif ($statusApproval === 'Sudah Sesuai')
                                                        <button type="button"
                                                            class="btn btn-tiny btn-tiny-primary js-edit-isian2"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian2"
                                                            data-id="{{ $row->isian->id ?? '' }}"
                                                            data-statusapproval="{{ $row->isian->status_approval ?? '' }}"
                                                            data-statustindaklanjut="{{ $row->isian->status_tindaklanjut ?? '' }}"
                                                            data-komentarevaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                            data-komentaroperator1="{{ $row->isian->komentar_operator1 ?? '' }}"
                                                            @if (!in_array($data['user_active'][0]->role, ['evaluator', 'admin'])) disabled @endif>
                                                            <i class="bi bi-patch-check"></i>
                                                            <span>Sudah Sesuai</span>
                                                        </button>
                                                    @else
                                                        <span
                                                            class="badge text-bg-secondary">{{ $statusApproval }}</span>
                                                    @endif
                                                @endif
                                            </td> --}}

                                            <td class="keep-enter">
                                                @if ($row->pengisian === 1)
                                                    <button type="button" class="btn btn-edit-pill js-edit-isian3"
                                                        title="Beri Evaluasi" aria-label="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#modalEditIsian3"
                                                        data-id3="{{ $row->isian->id ?? '' }}"
                                                        data-rencana_kerja3="{{ $row->rencana_kerja ?? '' }}"
                                                        data-komentar_evaluator1="{{ $row->isian->komentar_evaluator1 ?? '' }}">
                                                        <i class="bi bi-pencil-square"></i><span> Evaluasi</span>
                                                    </button>
                                                    {{-- {{ $row->isian->id ?? '' }} --}}
                                                    @if (!empty($row->isian->komentar_evaluator1))
                                                        <b>{{ $row->isian->created_by_3 ?? 'Evaluator' }} :</b>
                                                        {{ $row->isian->komentar_evaluator1 ?? '-' }}
                                                        <hr>
                                                        @if (!empty($row->isian->komentar_operator1))
                                                            <b>{{ $row->isian->created_by_4 ?? 'Operator' }} :</b>
                                                            {{ $row->isian->komentar_operator1 ?? '-' }}
                                                        @endif

                                                        <button type="button"
                                                            class="btn btn-edit-pill js-edit-isian4"
                                                            title="Beri Tindak Lanjut" aria-label="Edit"
                                                            data-bs-toggle="modal" data-bs-target="#modalEditIsian4"
                                                            data-id4="{{ $row->isian->id ?? '' }}"
                                                            data-rencana_kerja4="{{ $row->rencana_kerja ?? '' }}"
                                                            data-komentar_evaluator12="{{ $row->isian->komentar_evaluator1 ?? '' }}"
                                                            data-komentar_operator1="{{ $row->isian->komentar_operator1 ?? '' }}">
                                                            <i class="bi bi-pencil-square"></i><span> Tindak
                                                                Lanjut</span>
                                                        </button>
                                                        <br>
                                                    @endif
                                                @endif
                                            </td>

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
                                <label class="form-label fw-semibold">Rencana Aksi</label>
                                <textarea class="form-control" name="rencanaaksi" id="edit_rencanaaksi" rows="4"
                                    placeholder="Tulis Rencana Aksi mu..."></textarea>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Output</label>
                                <textarea class="form-control" name="output" id="edit_output" rows="4"
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
                            <input type="hidden" name="pengisian" id="pengisian" value="ketiga">
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
                                value="{{ $data['user_active'][0]->name }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja3"
                                    id="edit_rencana_kerja3" disabled>
                            </div>

                            <br>

                            <div class="mb-3" id="wrap_komentar_evaluator1">
                                <label class="form-label fw-semibold">Komentar Evaluator</label>
                                <textarea class="form-control" name="komentar_evaluator1" id="edit_komentar_evaluator1" rows="4"
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
                                value="{{ $data['user_active'][0]->name }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Rencana Kinerja</label>
                                <input type="text" class="form-control" name="rencana_kerja4"
                                    id="edit_rencana_kerja4" disabled>
                            </div>

                            <br>

                            <div class="mb-3" id="wrap_komentar_evaluator12">
                                <label class="form-label fw-semibold">Komentar Evaluator</label>
                                <textarea class="form-control" name="komentar_evaluator12" id="edit_komentar_evaluator12" rows="4"
                                    placeholder="Tulis komentar evaluator..." disabled></textarea>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Komentar Operator</label>
                                <textarea class="form-control" name="komentar_operator1" id="edit_komentar_operator1" rows="4"
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

<!-- JS (pastikan jQuery sudah ada) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        $('#tblLembarKerja').DataTable({
            responsive: true,
            lengthChange: false,
            paging: false,
            info: false,
            autoWidth: false,
            ordering: false,
            order: [],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_–_END_ dari _TOTAL_ baris",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: ">",
                    previous: "<"
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

        const rencana_kerjaEl = document.getElementById('edit_rencana_kerja');
        if (rencana_kerjaEl) rencana_kerjaEl.value = rencana_kerja;

        document.getElementById('edit_penanggungjawab').value = penanggungjawab;
        document.getElementById('edit_rencanaaksi').value = rencanaaksi;
        document.getElementById('edit_output').value = output;

        // auto-ceklis bulan
        setCheckedFromCsv('bulanTargetWrap', btn.dataset.bulanTarget || '');

        // set hidden csv
        document.getElementById('bulan_target_csv').value = csvFromChecked('bulanTargetWrap');

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

</html>
