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

    <style>
        .btn {
            color: black !important;
            border: 1px solid black !important;
        }

        .btn:hover {
            background-color: #54acff !important;
        }
    </style>
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

                <div class="card">
                    <div class="card-body">
                        <h1>Setting Admin</h1>
                        <hr>
                        <button type="button" class="btn settingevaluator" title="Setting Evaluator ZI"
                            aria-label="Edit" data-bs-toggle="modal" data-bs-target="#settingevaluator">
                            <i class="bi bi-person-square"></i>
                            Setting Evaluator Perpilar</span>
                        </button>
                    </div>
                </div>

            </div>
            <br>
            <br>
        </div>


        {{-- Modal Pengisian Pertama --}}
        <div class="modal fade" id="settingevaluator" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ url('/setting-evaluator') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Atur Evaluator</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Tahun
                                </label>
                                <select id="tahun" name="tahun" class="form-select">
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Satker
                                </label>
                                <select id="satker" name="satker" class="form-select">
                                    @foreach ($data['satker'] ?? [] as $s)
                                        <option value="{{ $s->kode_satker }}">
                                            {{ $s->nama_satker }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <br>
                            @php
                                $pilars = ['I', 'II', 'III', 'IV', 'V', 'VI'];
                            @endphp

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Pilar
                                </label>
                                <select id="pilar" name="pilar" class="form-select">
                                    @foreach ($data['pilars'] as $p)
                                        <option value="{{ $p }}">
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <br>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Evaluator
                                </label>
                                <select id="edit_penanggungjawab" name="penanggungjawab" class="form-select">
                                    @foreach ($data['users'] ?? [] as $u)
                                        <option value="{{ $u->username }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br>

                        </div>



                        <div class="modal-footer">
                            <p style="font-size: 12px">Diupdate oleh : {{ $data['user_active'][0]->name }} </p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
