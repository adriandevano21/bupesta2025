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
        <title>BuPeSta - {{ $data["judul"] }}</title>
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
        <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/background-dinamis.css">
        <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Datatable --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.semanticui.css">
        
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

                    <table id="example" class="ui celled table">
                        <thead>
                            <tr>
                                <th>Rencana Kerja</th>
                                <th>Bukti Dukung</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data["indikator"] as $indikator)
                                <tr>
                                    <td>
                                        <div style="padding-left: {{ ($indikator->level - 1) * 24 }}px;">
                                            <span>{{ $indikator->rencana_kerja }}</span>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <br>
            <br>


            <footer>
                <?php include 'fitral/php/footer.php'; ?>
            </footer>
        </div>
    </body>

<script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.semanticui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    new DataTable('#example', {
      ordering: false,           // tidak ada sorting awal (ikut urutan DOM)
      lengthChange: false, // sembunyikan "Show X entries"
      paging: false        // tampilkan semua baris (tanpa pagination)
    });
  });
</script>

</html>