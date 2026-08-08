<link rel="stylesheet" href="{{ asset('assets-se2026/css-js/header.css') }}">

@php
    // Gunakan helper request() bawaan Laravel agar lebih elegan
    $tahunDipilih = (int) request('tahun', date('Y'));
@endphp

<div class="posisitengah">
    <div class="logobps">
        <a href="/">
            <img src="{{ asset('assets-se2026/img/Logo-SE2026.png') }}" alt="Logo SE2026">
        </a>
    </div>

    <div>
        <ul class="menuatas">
            {{-- id_judul = 1 --}}
            <li>
                <a href="/dashboard-kinerja?tahun={{ $tahunDipilih }}"
                    class="{{ $data['id_judul'] === '1' ? 'active' : '' }}">
                    <i class="fa-solid fa-tachometer-alt"></i><span>&nbsp; Kinerja</span>
                </a>
            </li>

            {{-- id_judul = 2 --}}
            <li class="dropdown-menuatas">
                <a href="#" class="{{ $data['id_judul'] === '2' ? 'active' : '' }}">
                    <i class="fa-solid fa-bowl-rice"></i><span>&nbsp; BuPeSta</span>
                </a>
                <ul class="submenu-menuatas">
                    <li>
                        <a href="../timkerja?tahun={{ $tahunDipilih }}"
                            class="{{ $data['id_judul'] === '2' ? 'active' : '' }}">
                            <i class="fa-solid fa-bowl-rice"></i><span>&nbsp; Tim Kerja BPS Aceh</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="../timelinesemuakegiatan.php?tahun={{ $tahunDipilih }}">
                            <i class="fa-solid fa-calendar-days"></i><span>&nbsp; Historis Kegiatan BPS Aceh</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard-kegiatan?tahun={{ $tahunDipilih }}">
                            <i class="fa-regular fa-calendar-check"></i><span>&nbsp; Jadwal Kegiatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard-deadline?tahun={{ $tahunDipilih }}">
                            <i class="fa-solid fa-calendar-days"></i><span>&nbsp; Catatan Deadline Kegiatan</span>
                        </a>
                    </li>
                    <li>
                        <a href="tentangbupesta.php?tahun={{ $tahunDipilih }}">
                            <i class="fa-solid fa-mug-hot"></i><span>&nbsp; TanyaJawab</span>
                        </a>
                    </li> --}}
                </ul>
            </li>

            {{-- id_judul = 3 --}}
            <li>
                <a href="/jazirah-dashboard?tahun={{ $tahunDipilih }}"
                    class="{{ $data['id_judul'] === '3' ? 'active' : '' }}">
                    <i class="fa-solid fa-file"></i><span>&nbsp; Jazirah</span>
                </a>
            </li>

            {{-- id_judul = 4 --}}
            <li>
                <a href="/ist?tahun={{ $tahunDipilih }}" class="{{ $data['id_judul'] === '4' ? 'active' : '' }}">
                    <i class="fa-solid fa-award"></i><span>&nbsp; IST</span>
                </a>
            </li>

            {{-- id_judul = 5 --}}
            <li>
                <a href="/cinema?tahun={{ $tahunDipilih }}" class="{{ $data['id_judul'] === '5' ? 'active' : '' }}">
                    <i class="fa-solid fa-film"></i><span>&nbsp; Cinema</span>
                </a>
            </li>

            <li>
                <a href="https://qna.bpsaceh.com/">
                    <i class="fa-regular fa-folder"></i><span>&nbsp; Kotak</span>
                </a>
            </li>

            <li>
                <a href="#" id="showModal">
                    <i class="fa-solid fa-book"></i><span>&nbsp; Panduan</span>
                </a>
            </li>

            <li>
                <form method="GET" id="tahunForm">
                    <select id="tahunSelector" name="tahun" class="tahun-dropdown">
                        @foreach ([2024, 2025, 2026] as $th)
                            <option value="{{ $th }}" {{ $th === $tahunDipilih ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </li>

            {{-- Menu Profil --}}
            <li style="margin-left: 10px;">
                <a href="#" id="showProfil" class="no-hover" style="padding: 5px;">
                    @if (!empty($data['user_active']->urlfoto))
                        {{-- Memanggil URL foto asli dari database, bukan link hardcoded --}}
                        <img src="{{ $data['user_active']->urlfoto }}" alt="Profil"
                            style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 2px solid #f79039; vertical-align: middle;">
                    @else
                        <i class="fa-solid fa-circle-user"
                            style="color: #f79039; font-size: 25px; vertical-align: middle;"></i>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // 1. Logika Pemilihan Tahun
        const tahunSelector = document.getElementById('tahunSelector');
        if (tahunSelector) {
            tahunSelector.addEventListener('change', function() {
                const params = new URLSearchParams(window.location.search);
                params.set('tahun', this.value);
                window.location.search = params.toString();
            });
        }

        // 2. Modal Unduh Buku Pedoman
        const btnModal = document.getElementById("showModal");
        if (btnModal) {
            btnModal.addEventListener("click", function(e) {
                e.preventDefault(); // Mencegah lompatan ke atas karena href="#"
                Swal.fire({
                    title: 'Unduh Buku Pedoman',
                    html: `
                    <div style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                        <a href="BUKPED BUPESTA.pdf" download class="swal2-download-btn" style="padding: 10px; background: #f3f4f6; border-radius: 5px; text-decoration: none; color: #374151;">
                            📘 Buku Pedoman BuPeSta
                        </a>
                        <a href="BUKPED BUPESTA TAGUEN.pdf" download class="swal2-download-btn" style="padding: 10px; background: #f3f4f6; border-radius: 5px; text-decoration: none; color: #374151;">
                            📙 Buku Pedoman BuPeSta (Taguen)
                        </a>
                    </div>
                `,
                    icon: 'info',
                    showConfirmButton: false,
                    showCloseButton: true,
                    width: 460,
                    padding: '1.5em',
                    background: '#fff',
                });
            });
        }

        // 3. Modal Profil & Logout
        const btnProfil = document.getElementById("showProfil");
        if (btnProfil) {
            btnProfil.addEventListener("click", function(e) {
                e.preventDefault(); // Mencegah lompatan ke atas karena href="#"
                Swal.fire({
                    title: 'Profil Pengguna',
                    html: `
                    <div style="text-align: left; font-size: 14px; margin-top: 15px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; width: 35%; color: #4b5563;">Nama</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->name ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">Username</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->username ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">No. HP</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->no_hp ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">NIP Pegawai</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->nip_pegawai ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">Kode Satker</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->kode_satker ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">Golongan</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->golongan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; font-weight: bold; color: #4b5563;">Jabatan</td>
                                <td style="padding: 10px 0; color: #111827;">: {{ $data['user_active']->jabatan ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                `,
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#6b7280',
                    showDenyButton: true,
                    denyButtonText: '<i class="fa-solid fa-right-from-bracket"></i> Logout',
                    denyButtonColor: '#ef4444',
                    showCloseButton: true,
                    width: 450,
                    padding: '2em',
                    background: '#fff',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isDenied) {
                        window.location.href = "#";
                    }
                });
            });
        }
    });
</script>
