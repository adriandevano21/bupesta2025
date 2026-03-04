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

    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/header.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/jazirah-dashboard.css">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/') }}/style/potrait-warning.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .menuatas {
            padding: 15px;
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
            <?php include 'fitral/php/animasitextbps.php'; ?>

            <br>

            <div class="posisitengah">

                <div class="welcome-banner">
                    <div class="welcome-content">

                        <div class="character-container">
                            <img src="{{ asset('assets-jazirah/') }}/img/robot-body.png" class="char-body"
                                alt="Robot Body">

                            <img src="{{ asset('assets-jazirah/') }}/img/robot-arm.png" class="char-arm"
                                alt="Robot Arm">
                        </div>
                        <h2><span id="typewriter"></span><span class="cursor">|</span></h2>
                    </div>
                </div>

                @php
                    $menus = [
                        [
                            'title' => 'New Lembar Kerja',
                            'url' => url('/lembar-kerja/new'),
                            'color' => 'from-blue-500 to-cyan-400',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />',
                        ],
                        [
                            'title' => 'Seulanga',
                            'url' => url('/seulanga'),
                            'color' => 'from-amber-400 to-yellow-500', // Warna kuning keemasan seperti bunga Seulanga
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />',
                        ],
                        [
                            'title' => 'Rangkuman',
                            'url' => url('/rangkuman'),
                            'color' => 'from-indigo-500 to-blue-600',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />',
                        ],
                        [
                            'title' => 'Pengisian Matriks Aksi',
                            'url' => url('/matriks-aksi'),
                            'color' => 'from-purple-500 to-indigo-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25m0-5.25C18 5.004 17.496 4.5 16.875 4.5H7.125M18 10.875V16.125m0-5.25c0 .621-.504 1.125-1.125 1.125H7.125m10.875-1.125h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 16.125c0 .621-.504 1.125-1.125 1.125H7.125m10.875-1.125c0 .621-.504 1.125-1.125 1.125v1.5m-10.875-2.625v2.625m0-2.625C6 15.621 5.496 15 4.875 15h-1.5m1.5 0c.621 0 1.125.504 1.125 1.125v1.5M3.375 15c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125" />',
                        ],
                        [
                            'title' => 'Pedoman ZI',
                            'url' => url('/pedoman-zi'),
                            'color' => 'from-emerald-400 to-teal-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />',
                        ],
                        [
                            'title' => 'SOP',
                            'url' => url('/sop'),
                            'color' => 'from-rose-400 to-red-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />',
                        ],
                        [
                            'title' => 'LHE TPP ZI 2024',
                            'url' => url('/lhe-tpp-2024'),
                            'color' => 'from-fuchsia-500 to-purple-600',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />',
                        ],
                        [
                            'title' => 'LKE Satker 2024',
                            'url' => url('/lke-satker-2024'),
                            'color' => 'from-orange-400 to-orange-600',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />',
                        ],
                        [
                            'title' => 'Event Jazirah',
                            'url' => url('/jazirah/event'),
                            'color' => 'from-cyan-400 to-blue-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />',
                        ],
                        [
                            'title' => 'Satker Lolos TPI',
                            'url' => url('/satker-tpi'),
                            'color' => 'from-green-400 to-emerald-600', // Hijau melambangkan kesuksesan/lolos
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />',
                        ],
                        [
                            'title' => 'QNA',
                            'url' => url('/qna'),
                            'color' => 'from-sky-400 to-cyan-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.221-1.125-2.13-2.33-2.13-1.656 0-3.305.11-4.935.323-1.13.148-1.996 1.11-1.996 2.231v.667m0 0a2.25 2.25 0 0 0-2.25 2.25v3.5m0 0c0 1.136.847 2.1 1.98 2.193.34.027.68.052 1.02.072v3.091l3-3c1.354 0 2.694-.055 4.02-.163a2.115 2.115 0 0 0 .825-.242m-9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951" />',
                        ],
                        [
                            'title' => 'Narahubung',
                            'url' => url('/narahubung'),
                            'color' => 'from-teal-400 to-emerald-500',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.25-3.95-6.847-6.847l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />',
                        ],
                    ];
                @endphp

                <div class="w-full mx-auto">

                    <div class="flex overflow-x-auto gap-6 pb-6 pt-4 snap-x snap-mandatory modern-scrollbar">

                        @foreach ($menus as $menu)
                            <a href="{{ $menu['url'] }}"
                                class="group relative snap-center shrink-0 w-[180px] bg-gray-100 rounded-[1.5rem] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-slate-100 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_12px_30px_rgb(0,0,0,0.08)] flex flex-col items-center justify-center gap-5 text-center">

                                <div
                                    class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $menu['color'] }} flex items-center justify-center text-white shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-8 h-8">
                                        {!! $menu['icon'] !!}
                                    </svg>
                                </div>

                                <h3
                                    class="text-slate-700 font-semibold text-sm leading-snug transition-colors duration-300 group-hover:text-indigo-600">
                                    {{ $menu['title'] }}
                                </h3>

                            </a>
                        @endforeach

                    </div>
                </div>

                <br>

                <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                    <form method="GET" action="{{ url()->current() }}" class="mb-6">
                        <div class="flex items-center space-x-3">
                            <label for="jenis_data" class="text-gray-800 font-bold text-base">
                                Data:
                            </label>

                            <div class="relative">
                                <select name="jenis_data" id="jenis_data" onchange="this.form.submit()"
                                    class="block w-full appearance-none bg-white border-2 border-green-200 text-gray-900 font-bold py-2 pl-4 pr-10 rounded-xl shadow-sm focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-50 cursor-pointer transition ease-in-out duration-150">
                                    <option value="penetapan_target"
                                        {{ request('jenis_data') == 'penetapan_target' ? 'selected' : '' }}>
                                        Penetapan Target
                                    </option>
                                    <option value="realisasi"
                                        {{ request('jenis_data') == 'realisasi' ? 'selected' : '' }}>
                                        Realisasi
                                    </option>
                                    <option value="evaluasi"
                                        {{ request('jenis_data') == 'evaluasi' ? 'selected' : '' }}>
                                        Evaluasi
                                    </option>
                                    <option value="tindak_lanjut"
                                        {{ request('jenis_data') == 'tindak_lanjut' ? 'selected' : '' }}>
                                        Tindak Lanjut
                                    </option>
                                    <option value="selesai" {{ request('jenis_data') == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                </select>

                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>

                            <input type="hidden" name="tahun" value="{{ request('tahun', '2024') }}">
                        </div>
                    </form>

                    <button id="downloadBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-xl shadow-sm transition duration-150 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Download Image</span>
                    </button>
                </div>

                <div class="overflow-x-auto bg-white rounded-xl shadow-md border border-gray-200">

                    <table id="tabel-monitoring" class="w-full text-sm text-center border-collapse whitespace-nowrap">

                        <thead class="text-xs uppercase font-bold text-gray-800">
                            <tr>
                                <th rowspan="2" class="border border-gray-200 px-4 py-3 align-middle bg-blue-50">
                                </th>
                                <th rowspan="2" class="border border-gray-200 px-4 py-3 align-middle bg-blue-50">
                                    Pilar</th>
                                <th colspan="{{ count($data['satkers']) }}"
                                    class="border border-gray-200 px-4 py-2 bg-blue-50">
                                    Satuan Kerja
                                </th>
                            </tr>
                            <tr>
                                @foreach ($data['satkers'] as $satker)
                                    <th class="border border-gray-200 px-3 py-2 bg-blue-50/50">{{ $satker }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="text-gray-700">
                            @foreach ($data['pivotData'] as $row)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">

                                    <td class="border border-gray-200 px-4 py-2 font-bold bg-white">
                                        @if ($row['indikator'] === 'I.')
                                            A
                                        @else
                                            B
                                        @endif
                                    </td>
                                    <td class="border border-gray-200 px-4 py-2 font-bold bg-white">
                                        {{ $row['pilar'] }}
                                    </td>

                                    @foreach ($data['satkers'] as $satker)
                                        @php
                                            $nilai = $row[$satker];
                                            $warnaClass = '';

                                            // Logika Pewarnaan menggunakan Arbitrary Values Tailwind
                                            if ($nilai == 100) {
                                                $warnaClass = 'bg-[#00fa9a] text-black font-bold'; // Hijau terang
                                            } elseif ($nilai > 0 && $nilai < 100) {
                                                $warnaClass = 'bg-[#ffeb3b] text-black font-bold'; // Kuning
                                            } elseif ($nilai === '0' || $nilai === 0 || $nilai === '0.00') {
                                                $warnaClass = 'bg-[#ffe4e1] text-black'; // Pink muda
                                            } else {
                                                $warnaClass = 'bg-white text-gray-400'; // Untuk nilai kosong (-)
                                            }
                                        @endphp

                                        <td class="border border-gray-200 px-3 py-2 {{ $warnaClass }}">
                                            {{ $nilai !== null ? $nilai : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <br>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    document.getElementById('downloadBtn').addEventListener('click', function() {
        // Ubah teks tombol saat proses loading (opsional tapi bagus untuk UX)
        let btn = this;
        let originalText = btn.innerHTML;
        btn.innerHTML = '<span>Memproses...</span>';
        btn.disabled = true;

        // Ambil elemen tabel berdasarkan ID
        let targetTable = document.getElementById('tabel-monitoring');

        // Jalankan fungsi html2canvas
        html2canvas(targetTable, {
            scale: 2, // Bikin resolusi gambar 2x lipat lebih tajam (HD)
            backgroundColor: "#ffffff", // Pastikan latar belakang putih
            useCORS: true // Berguna jika ada resource dari luar
        }).then(canvas => {
            // Ubah canvas menjadi URL data gambar
            let imageURL = canvas.toDataURL("image/png");

            // Buat elemen link sementara (invisible) untuk men-trigger download
            let downloadLink = document.createElement('a');
            downloadLink.href = imageURL;

            // Nama file saat di-download
            downloadLink.download = 'Monitoring_Jazirah_Zi.png';

            // Simulasikan klik pada link
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            // Kembalikan tombol ke keadaan semula
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => {
            alert("Gagal mengunduh gambar. Silakan coba lagi.");
            console.error("Error html2canvas: ", err);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
</script>

</html>
