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

        <link rel="stylesheet" href="assets-cinema/load/load.css">
        <script src="assets-cinema/load/load.js"></script>
        <title>BuPeSta - {{ $data["judul"] }}</title>
        <link rel="icon" type="image/x-icon" href="fitral/img/favicon.png">

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
        <link rel="stylesheet" href="assets-cinema/style/header.css">
        <link rel="stylesheet" href="assets-cinema/style/dashboard-activity.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- DataTables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    </head>

    <body>

        <div id="loading">
            <div id="loader-wrapper">
                <div id="loader"></div>
                <div class="loader-section section-left"></div>
                <div class="loader-section section-right"></div>
            </div>
        </div>
        <div id="page">
            <header>
                @include('layout2.navbar-cinema')
            </header>

            <div class="konten">
                <?php include 'fitral/php/animasitextbps.php'; ?>

                <br>

                <div class="posisitengah">

                    <div class="dashboard-wrapper">
                        <div class="dashboard-header">
                            <h1>📊 Aktivitas Pengguna</h1>
                            <p class="subtitle">Pantau aktivitas login harian dan rekap penggunaan sistem oleh pengguna terdaftar.</p>
                        </div>

                        <div class="filter-section">
                            <div class="date-control">
                                <label for="dateFilter">📅 Tanggal:</label>
                                <input type="text" id="dateFilter" class="date-picker" placeholder="Pilih tanggal">
                            </div>
                            <div class="checkbox-control">
                                <label>
                                    <input type="checkbox" id="showAll"> Tampilkan Semua
                                </label>
                            </div>
                        </div>

                        <div id="loginTableContainer" class="table-card">
                            @include('aktivitasuser.partials.tabel_login', ['data' => $data])
                        </div>

                        <div id="rekapTableContainer" class="table-card">
                            @include('aktivitasuser.partials.rekap_aktivitas', ['data' => $data])
                        </div>
                    </div>

                </div>
            </div>
            <br>
            <br>


            <footer>
                <?php include 'fitral/php/footer.php'; ?>
            </footer>
        </div>
    </body>

    {{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        initFlatpickr();
        initEventListeners();
        initDataTable();
    });

    function initFlatpickr() {
        flatpickr("#dateFilter", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ request('date') ?? now()->format('Y-m-d') }}",
            maxDate: "today",
            onChange: function (selectedDates, dateStr) {
                if (!document.getElementById("showAll").checked) {
                    fetchLoginData(dateStr, false);
                }
            }
        });
    }

    function initEventListeners() {
        document.getElementById("showAll").addEventListener("change", function () {
            const isChecked = this.checked;
            const dateInput = document.getElementById("dateFilter");
            dateInput.disabled = isChecked;

            fetchLoginData(dateInput.value, isChecked);
        });
    }

    function initDataTable() {
        ['#loginTable', '#rekapTable'].forEach(id => {
            if ($.fn.DataTable.isDataTable(id)) {
                $(id).DataTable().destroy();
            }
            $(id).DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "🔍 Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
                    paginate: {
                        previous: "⬅️",
                        next: "➡️"
                    }
                }
            });
        });
    }

    function fetchLoginData(date, all) {
        let url = "{{ route('user-activity') }}";
        url += all ? '?all=true' : '?date=' + date;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html;

            const loginTable = temp.querySelector('#loginTableContainer');
            const rekapTable = temp.querySelector('#rekapTableContainer');

            if (loginTable) document.getElementById('loginTableContainer').innerHTML = loginTable.innerHTML;
            if (rekapTable) document.getElementById('rekapTableContainer').innerHTML = rekapTable.innerHTML;

            initDataTable();
            initFlatpickr();
            initEventListeners();
        });
    }
</script>

</html>