document.addEventListener('DOMContentLoaded', function() {
    // 1. Efek Typewriter
    const element = document.getElementById("typewriter");
    if (element && typeof window.BupestaConfig !== 'undefined' && window.BupestaConfig.userName) {
        const fullText = `Halo, ${window.BupestaConfig.userName}! Selamat Datang..`;
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
            setTimeout(type, isDeleting ? 50 : 100);
        }
        type();
    }

    // 2. DataTables
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#dataTableMonitoring')) {
            $('#dataTableMonitoring').DataTable().destroy();
        }
        $('#dataTableMonitoring').DataTable({
            paging: false, info: false, searching: true,
            order: [[0, 'asc'], [1, 'asc']],
            columnDefs: [ { targets: [0, 1], orderable: true }, { targets: '_all', orderable: false } ]
        });
    }

    // 3. html2canvas Download Image
    const downloadBtn = document.getElementById("downloadBtn");
    const tabelMonitoring = document.getElementById("tabel-monitoring");

    if (downloadBtn && tabelMonitoring) {
        downloadBtn.addEventListener("click", function () {
            const originalContent = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
            downloadBtn.disabled = true;

            html2canvas(tabelMonitoring, { scale: 2, backgroundColor: "#ffffff", useCORS: true })
            .then(function (canvas) {
                let link = document.createElement("a");
                link.download = "Monitoring_Jazirah.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
                downloadBtn.innerHTML = originalContent;
                downloadBtn.disabled = false;
            }).catch(function(error) {
                alert("Gagal mengunduh gambar.");
                downloadBtn.innerHTML = originalContent;
                downloadBtn.disabled = false;
            });
        });
    }

    // 4. Modal Kunci Layar Profil
    const modalProfil = document.getElementById('modalLengkapiProfil');
    if (modalProfil) {
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") { event.preventDefault(); event.stopPropagation(); }
        }, true);
    }
});

// 5. GLOBAL CRUD MENU LOGIC UNTUK ADMIN
const modalMenu = document.getElementById('modalMenuForm');
const formMenu = document.getElementById('formMenuAction');

function openMenuModal() {
    if(!modalMenu) return;
    document.getElementById('menuModalTitle').innerText = 'Tambah Menu Baru';
    formMenu.reset();
    formMenu.action = '/jazirah-menu';
    document.getElementById('menuMethod').value = 'POST';
    modalMenu.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function editMenu(menuData) {
    if(!modalMenu) return;
    document.getElementById('menuModalTitle').innerText = 'Edit Menu (' + menuData.title + ')';
    formMenu.action = '/jazirah-menu/' + menuData.id;
    document.getElementById('menuMethod').value = 'PUT';
    
    document.getElementById('menuTitle').value = menuData.title;
    document.getElementById('menuUrl').value = menuData.url;
    
    // Auto-select dropdown background
    let bgSelect = document.getElementById('menuBg');
    if(bgSelect) {
        bgSelect.value = menuData.bg;
    }
    
    document.getElementById('menuIcon').value = menuData.icon;
    document.getElementById('menuUrutan').value = menuData.urutan;
    
    modalMenu.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMenuModal() {
    if(modalMenu) { modalMenu.style.display = 'none'; document.body.style.overflow = 'auto'; }
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape" && modalMenu && modalMenu.style.display === 'flex') closeMenuModal();
});