<div class="posisitengah">
    <div class="logobps">
        <a href="index.php"><img src="{{ asset('assets-cinema/') }}/img/logo.png" alt="#"></a>
    </div>

    <!-- Tombol Menu Toggle (Burger) -->
    <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
    </div>
    
    <div>
        <ul class="menuatas">
            
            <li>
                <form method="GET" action="" id="tahunForm">
                    <select id="tahunSelector" name="tahun" class="tahun-dropdown">
                        <option value="2025">2025</option>
                    </select>
                </form>
            </li>
            <?php $tahunDipilih = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date("Y"); ?>
            <li><a href="#" id="showModal"><i class="fa-solid fa-book"></i><span>&nbsp Panduan</span></a></li>
            <li><a href="https://qna.bpsaceh.com/"><i class="fa-regular fa-folder"></i><span>&nbsp Kotak</span></a></li>
            <li><a href="/cinema"><i class="fa-solid fa-film"></i><span>&nbsp Cinema</span></a></li>
            <li class="dropdown-menuatas">
                <a href="#"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp;BuPeSta</span></a>
                <ul class="submenu-menuatas">
                    <li><a href="../bupesta.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp Tim Kerja BPS Aceh</span></a></li>
                    <li><a href="../timelinesemuakegiatan.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Historis Kegiatan BPS Aceh</span></a></li>
                    <li><a href="/dashboard-kegiatan?tahun=<?= $tahunDipilih ?>"><i class="fa-regular fa-calendar-check"></i><span>&nbsp Jadwal Kegiatan</span></a></li>
                    <li><a href="/dashboard-deadline?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Catatan Deadline Kegiatan</span></a></li>
                    <li><a href="tentangbupesta.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-mug-hot"></i><span>&nbsp TanyaJawab</span></a></li>
                </ul>
            </li>
            <li><a href="/dashboard-kinerja?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-tachometer-alt"></i><span>&nbsp; Kinerja</span></a></li>
        </ul>
    </div>
</div>