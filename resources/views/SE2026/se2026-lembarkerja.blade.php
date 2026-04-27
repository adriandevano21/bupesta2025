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

    <link rel="stylesheet" href="{{ asset('assets-se2026/') }}/load/load.css">
    <script src="{{ asset('assets-se2026/') }}/load/load.js"></script>
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/') }}/img/favicon.png">

    <link rel="stylesheet" href="{{ asset('assets-se2026/') }}/css-js/se2026.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">
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
            @include('layout2.navbar-se2026')
        </header>

        <div class="konten">
            <?php include 'fitral/php/animasitextbps.php'; ?>

            <br>

            <div class="posisitengah">

                <div class="welcome-banner">
                    <div class="welcome-content">

                        <div class="character-container">
                            <img src="{{ asset('assets-se2026/') }}/img/bungitung.gif" class="char-animation"
                                alt="Robot Animasi">
                        </div>

                        <h2><span id="typewriter"></span><span class="cursor">|</span></h2>

                    </div>
                </div>
            </div>
            <footer>
                <?php include 'fitral/php/footer.php'; ?>
            </footer>
        </div>
    </div>
</body>

<script src="{{ asset('assets-jazirah/') }}/style/potrait-warning.js"></script>

{{-- SCRIPT SELAMAT DATANG --}}
<script>
    // Script JavaScript tidak ada perubahan
    document.addEventListener('DOMContentLoaded', () => {
        const element = document.getElementById("typewriter");
        // Ganti baris ini dengan data blade Anda yang asli:
        const username = @json($data['user_active'][0]->name ?? 'Pengguna');
        // const username = "User"; // Contoh data dummy

        const fullText = `Halo, ${username}! Selamat Datang..`;
        const chars = Array.from(fullText);
        let i = 0;
        let isDeleting = false;

        function type() {
            if (!isDeleting) {
                i++;
                element.textContent = chars.slice(0, i).join('');
                if (i === chars.length) {
                    isDeleting = true;
                    setTimeout(type, 2000);
                    return;
                }
            } else {
                i--;
                element.textContent = chars.slice(0, i).join('');
                if (i <= 0) {
                    i = 0;
                    isDeleting = false;
                }
            }
            const speed = isDeleting ? 50 : 100;
            setTimeout(type, speed);
        }
        type();
    });
</script>

</html>
