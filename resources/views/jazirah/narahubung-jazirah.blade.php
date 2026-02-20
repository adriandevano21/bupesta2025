<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Formulir</title>
  <style>
    /* Container responsif untuk lebar penuh */
    .form-wrap {
      max-width: 900px;   /* sesuaikan jika perlu */
      margin: 0 auto;
      padding: 16px;
    }
    .gform-iframe {
      width: 100%;
      height: 1200px;    /* atur tinggi agar tidak muncul scroll di dalam iframe */
      border: 0;
    }
    @media (max-width: 600px) {
      .gform-iframe { height: 1600px; } /* beri tinggi lebih di mobile */
    }
  </style>
</head>
<body>
  <div class="form-wrap">
    <h1>Pendaftaran Kegiatan</h1>
    <iframe
      class="gform-iframe"
      src="https://docs.google.com/forms/d/e/1FAIpQLSfP7cAPokxBuCLGbC62MsWY55KmHDthnDge8-B3f1olp-O32w/viewform?embedded=true"
      allowfullscreen
      loading="lazy">
    </iframe>
  </div>
</body>
</html>
