<style>
    .dropdown-menuatas {
        position: relative;
        display: inline-block;
        float: right;
        padding: 25px 25px 0px 0px;
        font-size: 13px;
    }

    .dropdown-menuatas li {
        padding: 0;
    }

    .dropdown-menuatas>a {
        color: #000000;
        text-decoration: none;
    }

    .dropdown-menuatas:hover>a {
        color: #1a71a7;
        border-bottom: 3px solid #1a71a7;
    }

    ul.submenu-menuatas:hover {
        display: flex;
        flex-direction: column;
    }

    ul.submenu-menuatas li {
        float: none;
    }

    .submenu-menuatas {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background-color: #ffffff;
        min-width: 200px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        padding: 0;
        margin: 0;
        list-style: none;
        border: 1px solid #ddd;
    }

    .submenu-menuatas li a {
        display: block;
        text-wrap: nowrap;
        padding: 10px 15px;
        text-decoration: none;
        color: #000000;
        font-size: 13px;
    }

    .submenu-menuatas li a:hover {
        background-color: #f0f0f0;
        color: #1a71a7;
    }

    .dropdown-menuatas:hover .submenu-menuatas {
        display: block;
    }
</style>

<div class="posisitengah">
    <div class="logobps">
        <a href="index.php"><img src="{{ asset('assets-cinema/') }}/img/logo.png" alt="#"></a>
    </div>
    <div>
        <ul class="menuatas">
            <li class="dropdown-menuatas">
                <a href="#"><i class="fa-solid fa-user-circle"></i><span>&nbsp Adrian Devano</span></a>
                <ul class="submenu-menuatas">
                    <li><a href="#"><i class="fa-solid fa-right-from-bracket"></i><span>&nbsp Keluar</span></a></li>
                </ul>
            </li>
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
                    <li><a href="bupesta.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp Tim Kerja BPS Aceh</span></a></li>
                    <li><a href="timelinesemuakegiatan.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Historis Kegiatan BPS Aceh</span></a></li>
                    <li><a href="/dashboard-kegiatan?tahun=<?= $tahunDipilih ?>"><i class="fa-regular fa-calendar-check"></i><span>&nbsp Jadwal Kegiatan</span></a></li>
                    <li><a href="/dashboard-deadline?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Catatan Deadline Kegiatan</span></a></li>
                    <li><a href="tentangbupesta.php?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-mug-hot"></i><span>&nbsp TanyaJawab</span></a></li>
                </ul>
            </li>
            <li><a href="/dashboard-kinerja?tahun=<?= $tahunDipilih ?>"><i class="fa-solid fa-tachometer-alt"></i><span>&nbsp; Kinerja</span></a></li>
        </ul>
    </div>
</div>