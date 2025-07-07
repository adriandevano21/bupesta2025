<!DOCTYPE html>
<html>

<head>
    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Poppins Google -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <!-- Icon Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Fitral CSS -->

    <link rel="stylesheet" href="fitral/style/load.css">
    <script src="fitral/style/load.js"></script>
    <title>BuPeSta - Kegiatan</title>
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
            @include('layout2.navbar')
        </header>

        <div class="konten">
            <!-- <img style="width:100%;" src="fitral/img/gedung1.jpg"> -->
            <?php include 'fitral/php/animasitextbps.php'; ?>

            <br>

            <div class="posisitengah">
                <div class="kumpulan-kartu">

                </div>
            </div>
        </div>

        <footer>
            <?php include 'fitral/php/footer.php'; ?>
        </footer>
    </div>
</body>

</html>