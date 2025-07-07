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
            <li><a href="#" id="showModal"><i class="fa-solid fa-book"></i><span>&nbsp Panduan</span></a></li>
            <li><a href="tentangbupesta.php"><i class="fa-solid fa-mug-hot"></i><span>&nbsp TanyaJawab</span></a></li>
            <li><a href="timelinesemuakegiatan.php"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Historis</span></a></li>
            <li><a href="/dashboard-kegiatan"><i class="fa-regular fa-calendar-check"></i><span>&nbsp Kegiatan</span></a></li>
            <li><a href="bupesta.php"><i class="fa-solid fa-bowl-rice"></i><span>&nbsp BuPeSta</span></a></li>
            <li><a href="/dashboard-deadline"><i class="fa-solid fa-calendar-days"></i><span>&nbsp Deadline</span></a></li>
            <?php
                $tahunDipilih = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date("Y"); // Konversi ke integer
            
                if ($tahunDipilih === 2024) { // Gunakan === untuk perbandingan ketat
            ?>
                    <li><a href="/dashboard-kinerja"><i class="fa-solid fa-tachometer-alt"></i><span>&nbsp; Kinerja 2024</span></a></li>
            <?php        
                } else {
            ?>
                    <li><a href="capkin.php"><i class="fa-solid fa-tachometer-alt"></i><span>&nbsp; Kinerja 2025</span></a></li>
            <?php        
                }
            ?>

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