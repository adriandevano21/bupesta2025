
<div class="posisitengah">
    <div class="logobps">
        <a href="index.php"><img src="fitral/img/logo.png" alt="#"></a>
    </div>
    <div>
        <ul class="menuatas">
            <li>
                <form method="GET" action="" id="tahunForm">
                    <select id="tahunSelector" name="tahun" class="tahun-dropdown"></select>
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

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Unduh Panduan</h2>
                <a href="BUKPED BUPESTA.pdf" class="button" download="BUKPED BUPESTA.pdf">Buku Pedoman BuPeSta</a>
                <a href="BUKPED BUPESTA TAGUEN.pdf" class="button" download="BUKPED BUPESTA TAGUEN.pdf">Buku Pedoman BuPeSta (Taguen)</a>
            </div>
        </div>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");
    var link = document.getElementById("showModal");
    var span = document.getElementsByClassName("close")[0];

    link.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    var tahunSelector = document.getElementById("tahunSelector");
    var tahunForm = document.getElementById("tahunForm");
    var tahunSekarang = new Date().getFullYear();
    var selectedYear = new URLSearchParams(window.location.search).get("tahun") || tahunSekarang;

    for (var i = tahunSekarang; i >= tahunSekarang - 1; i--) {
        var option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        if (i == selectedYear) {
            option.selected = true;
        }
        tahunSelector.appendChild(option);
    }

    tahunSelector.addEventListener("change", function() {
        tahunForm.submit();
    });
</script>