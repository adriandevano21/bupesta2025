<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets-se2026/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-bupesta/aktivitas.css') }}">

    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>

    <div id="orientation-warning" style="display: none;">
        <h1>Putar Perangkat Anda</h1>
        <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
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

    <div id="page">
        <header>
            @include('adminbupesta.navbar-admin')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>

            <div class="posisitengah">

                <div class="header-dashboard">
                    <div class="header-title">
                        <h2><i class="fa-solid fa-chart-pie"></i> Dashboard Log Aktivitas Pengguna</h2>
                        <p>Pantau rekapan aktivitas harian, tren statistik, dan riwayat log sistem BuPeSta.</p>
                    </div>
                    <div class="header-filter-tanggal">
                        <form method="GET" action="{{ route('aktivitasuser.index') }}" id="formFilterTanggal">
                            <input type="hidden" name="tahun" value="{{ $data['tahun_pilihan'] }}">
                            <label for="tanggal_input"><i class="fa-solid fa-calendar-day"></i> Pilih Tanggal:</label>
                            <input type="date" id="tanggal_input" name="tanggal"
                                value="{{ $data['tanggal_pilihan'] }}"
                                onchange="document.getElementById('formFilterTanggal').submit()">
                        </form>
                    </div>
                </div>

                <div class="bagian-section">
                    <div class="section-title">
                        <h3><i class="fa-solid fa-calendar-check text-oren"></i> Rekap Aktivitas Tanggal:
                            <span>{{ \Carbon\Carbon::parse($data['tanggal_pilihan'])->translatedFormat('d F Y') }}</span>
                        </h3>
                    </div>

                    <div class="grid-stat DuaKolom">
                        <div class="card-stat oren">
                            <div class="icon-box"><i class="fa-solid fa-user-check"></i></div>
                            <div class="stat-info">
                                <h3>{{ number_format($data['total_login_hari_ini']) }} Pegawai</h3>
                                <p>Pegawai Login
                                    ({{ \Carbon\Carbon::parse($data['tanggal_pilihan'])->translatedFormat('d M Y') }})
                                </p>
                            </div>
                        </div>

                        <div class="card-stat hijau">
                            <div class="icon-box"><i class="fa-solid fa-fire"></i></div>
                            <div class="stat-info">
                                <h3>{{ $data['aktivitas_terbanyak_hari_ini'] }}</h3>
                                <p>Aktivitas Terbanyak Hari Ini / Terpilih (Kecuali Login)</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid-grafik">
                        <div class="card-widget">
                            <h4><i class="fa-solid fa-list-check text-oren"></i> Top 5 Aktivitas Hari Ini / Terpilih
                                (Kecuali Login)</h4>
                            <ul class="list-ranking">
                                @forelse($data['top_aktivitas_hari_ini'] as $index => $act)
                                    <li>
                                        <span class="rank-num">{{ $index + 1 }}</span>
                                        <span class="rank-title"
                                            title="{{ $act->activity }}">{{ $act->activity }}</span>
                                        <span class="rank-badge">{{ number_format($act->total) }}x Akses</span>
                                    </li>
                                @empty
                                    <li class="text-kosong">Tidak ada aktivitas pada tanggal ini</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="card-widget">
                            <h4><i class="fa-solid fa-laptop-code text-oren"></i> Top 5 User Agent / Browser Hari Ini /
                                Terpilih</h4>
                            <ul class="list-ranking">
                                @forelse($data['top_user_agent_hari_ini'] as $index => $ua)
                                    <li>
                                        <span class="rank-num alt">{{ $index + 1 }}</span>
                                        <span class="rank-title" title="{{ $ua->user_agent }}">
                                            {{ \Illuminate\Support\Str::limit($ua->user_agent ?? 'Unknown', 40) }}
                                        </span>
                                        <span class="rank-badge alt">{{ number_format($ua->total) }}x Akses</span>
                                    </li>
                                @empty
                                    <li class="text-kosong">Tidak ada data browser pada tanggal ini</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bagian-section">
                    <div class="section-title">
                        <h3><i class="fa-solid fa-chart-line text-oren"></i> Tren Rekapan Aktivitas</h3>
                    </div>

                    <div class="grid-grafik">
                        <div class="card-chart">
                            <div class="chart-header">
                                <h4><i class="fa-solid fa-chart-line text-oren"></i> Tren Pegawai yang Akses Harian (30
                                    Hari Terakhir)</h4>
                            </div>
                            <div class="chart-body">
                                <canvas id="chartHarian30"></canvas>
                            </div>
                        </div>

                        <div class="card-chart">
                            <div class="chart-header">
                                <h4><i class="fa-solid fa-chart-column text-oren"></i> Tren Pegawai yang Akses Per Bulan
                                    (12 Bulan - Tahun {{ $data['tahun_pilihan'] }})</h4>
                            </div>
                            <div class="chart-body">
                                <canvas id="chartBulanan12"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kartu-tabel">
                    <div class="tabel-top-bar">
                        <h3><i class="fa-solid fa-table-list"></i> Data Log Keseluruhan (Tahun
                            {{ $data['tahun_pilihan'] }})</h3>
                        <div class="search-box">
                            <i class="fa-solid fa-search"></i>
                            <input type="text" id="pencarianAktivitas" placeholder="Cari dalam tabel...">
                        </div>
                    </div>

                    @php
                        function getSortUrl($col, $cur, $dir, $thn, $tgl)
                        {
                            $next = $cur === $col && $dir === 'asc' ? 'desc' : 'asc';
                            return route('aktivitasuser.index', [
                                'tahun' => $thn,
                                'tanggal' => $tgl,
                                'sort_by' => $col,
                                'sort_dir' => $next,
                            ]);
                        }
                        function getSortIcon($col, $cur, $dir)
                        {
                            if ($cur !== $col) {
                                return '<i class="fa-solid fa-sort sort-gray"></i>';
                            }
                            return $dir === 'asc'
                                ? '<i class="fa-solid fa-sort-up sort-active"></i>'
                                : '<i class="fa-solid fa-sort-down sort-active"></i>';
                        }
                    @endphp

                    <div class="tabel-responsive">
                        <table id="tabelAktivitas" class="tabel-modern">
                            <thead>
                                <tr>
                                    <th><a href="{{ getSortUrl('created_at', $data['sort_by'], $data['sort_dir'], $data['tahun_pilihan'], $data['tanggal_pilihan']) }}"
                                            class="sort-link">Waktu {!! getSortIcon('created_at', $data['sort_by'], $data['sort_dir']) !!}</a></th>
                                    <th><a href="{{ getSortUrl('user_id', $data['sort_by'], $data['sort_dir'], $data['tahun_pilihan'], $data['tanggal_pilihan']) }}"
                                            class="sort-link">User ID {!! getSortIcon('user_id', $data['sort_by'], $data['sort_dir']) !!}</a></th>
                                    <th><a href="{{ getSortUrl('activity', $data['sort_by'], $data['sort_dir'], $data['tahun_pilihan'], $data['tanggal_pilihan']) }}"
                                            class="sort-link">Aktivitas {!! getSortIcon('activity', $data['sort_by'], $data['sort_dir']) !!}</a></th>
                                    <th><a href="{{ getSortUrl('ip_address', $data['sort_by'], $data['sort_dir'], $data['tahun_pilihan'], $data['tanggal_pilihan']) }}"
                                            class="sort-link">IP Address {!! getSortIcon('ip_address', $data['sort_by'], $data['sort_dir']) !!}</a></th>
                                    <th><a href="{{ getSortUrl('user_agent', $data['sort_by'], $data['sort_dir'], $data['tahun_pilihan'], $data['tanggal_pilihan']) }}"
                                            class="sort-link">User Agent {!! getSortIcon('user_agent', $data['sort_by'], $data['sort_dir']) !!}</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['aktivitas'] as $log)
                                    <tr>
                                        <td><span class="waktu-badge"><i class="fa-regular fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i:s') }}</span>
                                        </td>
                                        <td><span class="pengguna-badge"><i class="fa-solid fa-circle-user"></i>
                                                {{ $log->user_id ?? 'Anonim' }}</span></td>
                                        <td><span class="aktivitas-teks">{{ $log->activity }}</span></td>
                                        <td><span class="ip-badge"><i class="fa-solid fa-network-wired"></i>
                                                {{ $log->ip_address ?? '-' }}</span></td>
                                        <td>
                                            <div class="user-agent-teks" title="{{ $log->user_agent }}">
                                                @if (stripos($log->user_agent, 'mobile') !== false || stripos($log->user_agent, 'android') !== false)
                                                    <i class="fa-solid fa-mobile-screen-button text-oren"></i>
                                                @else
                                                    <i class="fa-solid fa-desktop text-oren"></i>
                                                @endif
                                                {{ \Illuminate\Support\Str::limit($log->user_agent ?? 'Unknown', 45) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-kosong-tabel"><i
                                                class="fa-solid fa-folder-open"></i> Belum ada rekaman aktivitas pada
                                            tahun {{ $data['tahun_pilihan'] }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (method_exists($data['aktivitas'], 'links'))
                        <div class="pagination-wrapper">
                            {{ $data['aktivitas']->links() }}
                        </div>
                    @endif
                </div>

            </div>

            <br>
            <footer>
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-bupesta/aktivitas.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof Chart === 'undefined') {
                console.error("Gagal memuat Chart.js dari CDN!");
                return;
            }

            // 1. Chart Harian 30 Hari
            const harian30Data = @json($data['chart_harian_30']);
            if (document.getElementById('chartHarian30')) {
                new Chart(document.getElementById('chartHarian30').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: harian30Data.labels,
                        datasets: [{
                            label: 'Pegawai yang Akses Harian',
                            data: harian30Data.data,
                            borderColor: '#f79039',
                            backgroundColor: 'rgba(247, 144, 57, 0.15)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // 2. Chart Bulanan 12 Bulan
            const bulanan12Data = @json($data['chart_bulanan_12']);
            if (document.getElementById('chartBulanan12')) {
                new Chart(document.getElementById('chartBulanan12').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: bulanan12Data.labels,
                        datasets: [{
                            label: 'Pegawai yang Akses Bulanan',
                            data: bulanan12Data.data,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>
