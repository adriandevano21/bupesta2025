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
        <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/header-form.css">
        <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/jazirah.css">
        <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            /* Iframe isi 1 layar penuh */
            .frame {
                position: absolute;
                inset: 0;
                width: 100vw;
                height: 100vh;   /* fallback */
                height: 100svh;  /* stabil di mobile modern */
                height: 100dvh;  /* dynamic viewport */
                border: 0;
            }
        </style>

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
                    <iframe class="frame"
                        src="https://docs.google.com/spreadsheets/d/1w9ocxlzdMNJ700TPCoyQQdZ6dPngYP2i3gxMXYg1Vmk/edit?usp=sharing">
                    </iframe>

                    <script>
                        // Jaga-jaga untuk mobile: sesuaikan tinggi saat address bar berubah
                        const f = document.querySelector('.frame');
                        const setH = () => f.style.height = window.innerHeight + 'px';
                        setH(); addEventListener('resize', setH);
                    </script>
                </div>
        </div>
    </body>

    <script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>


</html>