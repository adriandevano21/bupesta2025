<link rel="stylesheet" href="{{ asset('assets-se2026/') }}/css-js/header.css">

<div class="posisitengah">
    <div class="logobps">
        <a href="/dashboard-jazirah"><img src="{{ asset('assets-se2026/') }}/img/Logo-SE2026.png" alt="#"></a>
    </div>
    <div>
        <ul class="menuatas">
            <li><a href="/dashboard-kinerja?tahun=2026"><i class="fa-solid fa-tachometer-alt"></i><span>&nbsp;
                        Kinerja</span></a></li>
            <li class="dropdown-menuatas">
                <a href="#" class="active"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp;BuPeSta</span></a>
                <ul class="submenu-menuatas">
                    <li><a href="../bupesta.php?tahun=2026" class="active"><i
                                class="fa-solid fa-bowl-rice"></i><span>&nbsp Tim Kerja
                                BPS Aceh</span></a></li>
                    <li><a href="../timelinesemuakegiatan.php?tahun=2026"><i
                                class="fa-solid fa-calendar-days"></i><span>&nbsp Historis Kegiatan BPS Aceh</span></a>
                    </li>
                    <li><a href="/dashboard-kegiatan?tahun=2026"><i class="fa-regular fa-calendar-check"></i><span>&nbsp
                                Jadwal Kegiatan</span></a></li>
                    <li><a href="/dashboard-deadline?tahun=2026"><i class="fa-solid fa-calendar-days"></i><span>&nbsp
                                Catatan Deadline Kegiatan</span></a>
                    </li>
                    <li><a href="tentangbupesta.php?tahun=2026"><i class="fa-solid fa-mug-hot"></i><span>&nbsp
                                TanyaJawab</span></a></li>
                </ul>
            </li>
            <li><a href="/jazirah-dashboard"><i class="fa-solid fa-file"></i><span>&nbsp Jazirah</span></a></li>
            <li><a href="/cinema"><i class="fa-solid fa-film"></i><span>&nbsp Cinema</span></a></li>
            <li><a href="https://qna.bpsaceh.com/"><i class="fa-regular fa-folder"></i><span>&nbsp Kotak</span></a></li>
            <li><a href="#" id="showModal"><i class="fa-solid fa-book"></i><span>&nbsp Panduan</span></a></li>
            <li>
                <form method="GET" id="tahunForm">
                    <select id="tahunSelector" name="tahun" class="tahun-dropdown">
                        @foreach ([2024, 2025, 2026] as $th)
                            <option value="{{ $th }}"
                                {{ (string) $th === request('tahun', '2026') ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </li>
            {{-- Menu Profil Baru --}}
            <li style="margin-left: 10px;">
                <a href="javascript:void(0)" id="showProfil" class="no-hover" style="padding: 5px;">
                    @if (!empty($data['user_active']->urlfoto))
                        <img src="https://community.bps.go.id/images/avatar/340060473_20220614202049.jpg" alt="Profil"
                            style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover; border: 2px solid #f79039; vertical-align: middle;">
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
    document.getElementById("showModal").addEventListener("click", function() {
        Swal.fire({
            title: 'Unduh Buku Pedoman',
            html: `
                <div style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                    <a href="BUKPED BUPESTA.pdf" download class="swal2-download-btn">
                        📘 Buku Pedoman BuPeSta
                    </a>
                    <a href="BUKPED BUPESTA TAGUEN.pdf" download class="swal2-download-btn">
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
</script>

<script>
    document.getElementById('tahunSelector').addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('tahun', this.value); // ganti/isi parameter tahun
        // biar parameter lain (search, page, dll.) tetap ikut
        window.location.search = params.toString();
    });
</script>

<script>
    document.getElementById("showProfil").addEventListener("click", function() {
        Swal.fire({
            title: 'Profil Pengguna',
            html: `
                <div style="text-align: left; font-size: 14px; margin-top: 15px;">
                    <div style="text-align: center; margin-bottom: 25px;">

                    </div>
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
            confirmButtonColor: '#6b7280', // Warna abu-abu untuk tutup
            showDenyButton: true,
            denyButtonText: '<i class="fa-solid fa-right-from-bracket"></i> Logout',
            denyButtonColor: '#ef4444', // Warna merah untuk logout
            showCloseButton: true,
            width: 450,
            padding: '2em',
            background: '#fff',
            // Membalik urutan agar tombol Logout (deny) ada di kiri dan Tutup (confirm) di kanan
            reverseButtons: true
        }).then((result) => {
            if (result.isDenied) {
                // Eksekusi ketika tombol Logout diklik
                // Pastikan URL '/logout' disesuaikan dengan route logout aplikasi Laravel Anda
                window.location.href = "{{ url('/logout') }}";
            }
        });
    });
</script>
