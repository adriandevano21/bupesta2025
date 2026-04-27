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
                <a href="#"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp;BuPeSta</span></a>
                <ul class="submenu-menuatas">
                    <li><a href="../bupesta.php?tahun=2026"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp Tim Kerja
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
            <li><a href="/cinema"><i class="fa-solid fa-file"></i><span>&nbsp Jazirah</span></a></li>
            <li><a href="/dashboard-jazirah"><i class="fa-solid fa-film"></i><span>&nbsp Cinema</span></a></li>
            <li><a href="https://qna.bpsaceh.com/"><i class="fa-regular fa-folder"></i><span>&nbsp Kotak</span></a></li>
            <li><a href="#" id="showModal"><i class="fa-solid fa-book"></i><span>&nbsp Panduan</span></a></li>
            <li>
                <form method="GET" id="tahunForm">
                    <select id="tahunSelector" name="tahun" class="tahun-dropdown">
                        @foreach ([2025, 2026] as $th)
                            <option value="{{ $th }}"
                                {{ (string) $th === request('tahun', '2026') ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </form>
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
