document.addEventListener('DOMContentLoaded', () => {
    // 1. EFEK TYPEWRITER
    const typeWriterElement = document.getElementById("typewriter");
    if (typeWriterElement && window.BupestaConfig?.userName) {
        const fullText = `Halo, ${window.BupestaConfig.userName}! Selamat Datang..`;
        const chars = Array.from(fullText);
        let i = 0;
        let isDeleting = false;

        const type = () => {
            if (!isDeleting) {
                typeWriterElement.textContent = chars.slice(0, ++i).join('');
                if (i === chars.length) {
                    isDeleting = true;
                    setTimeout(type, 2000);
                    return;
                }
            } else {
                typeWriterElement.textContent = chars.slice(0, --i).join('');
                if (i <= 0) {
                    i = 0;
                    isDeleting = false;
                }
            }
            setTimeout(type, isDeleting ? 50 : 100);
        };
        type();
    }

    // 2. DATATABLES
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
        const $table =$('#dataTableMonitoring');
        if ($table.length) {
            if ($.fn.DataTable.isDataTable($table)) {$table.DataTable().destroy();
            }
            $table.DataTable({
                paging: false, 
                info: false, 
                searching: true,
                order: [[0, 'asc'], [1, 'asc']],
                columnDefs: [
                    { targets: [0, 1], orderable: true }, 
                    { targets: '_all', orderable: false }
                ]
            });
        }
    }

    // 3. HTML2CANVAS DOWNLOAD IMAGE (FIXED EXPORT LENGKAP)
    const downloadBtn = document.getElementById("downloadBtn");
    const tabelMonitoring = document.getElementById("tabel-monitoring");

    if (downloadBtn && tabelMonitoring) {
        downloadBtn.addEventListener("click", () => {
            const originalContent = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
            downloadBtn.disabled = true;

            // Simpan gaya CSS asli sebelum dirender
            const originalOverflow = tabelMonitoring.style.overflow;
            const originalWidth = tabelMonitoring.style.width;

            // Ubah gaya sementara agar seluruh tabel membentang penuh tanpa scroll
            tabelMonitoring.style.overflow = 'visible';
            tabelMonitoring.style.width = tabelMonitoring.scrollWidth + 'px';

            html2canvas(tabelMonitoring, { 
                scale: 2, 
                backgroundColor: "#ffffff", 
                useCORS: true,
                width: tabelMonitoring.scrollWidth,
                windowWidth: tabelMonitoring.scrollWidth
            }).then((canvas) => {
                // Eksekusi proses unduh
                const link = document.createElement("a");
                link.download = "Monitoring_Jazirah.png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            }).catch((error) => {
                console.error("Error html2canvas:", error);
                alert("Gagal mengunduh gambar.");
            }).finally(() => {
                // Kembalikan semua state seperti semula, terlepas sukses atau gagal
                tabelMonitoring.style.overflow = originalOverflow;
                tabelMonitoring.style.width = originalWidth;
                downloadBtn.innerHTML = originalContent;
                downloadBtn.disabled = false;
            });
        });
    }

    // 4. MODAL KUNCI LAYAR PROFIL
    const modalProfil = document.getElementById('modalLengkapiProfil');
    if (modalProfil) {
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';
        
        document.addEventListener('keydown', (event) => {
            if (event.key === "Escape") { 
                event.preventDefault(); 
                event.stopPropagation(); 
            }
        }, true);
    }
});


// 5. GLOBAL CRUD MENU LOGIC UNTUK ADMIN
// Helper untuk mengelompokkan elemen DOM Menu Modal agar kode lebih rapi
const getMenuElements = () => ({
    modal: document.getElementById('modalMenuForm'),
    form: document.getElementById('formMenuAction'),
    title: document.getElementById('menuModalTitle'),
    method: document.getElementById('menuMethod'),
    inputTitle: document.getElementById('menuTitle'),
    inputUrl: document.getElementById('menuUrl'),
    inputBg: document.getElementById('menuBg'),
    inputIcon: document.getElementById('menuIcon'),
    inputUrutan: document.getElementById('menuUrutan'),
});

function openMenuModal() {
    const els = getMenuElements();
    if (!els.modal) return;
    
    els.title.innerText = 'Tambah Menu Baru';
    els.form.reset();
    els.form.action = '/jazirah-menu';
    els.method.value = 'POST';
    
    els.modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function editMenu(menuData) {
    const els = getMenuElements();
    if (!els.modal) return;
    
    els.title.innerText = `Edit Menu (${menuData.title})`;
    els.form.action = `/jazirah-menu/${menuData.id}`;
    els.method.value = 'PUT';
    
    els.inputTitle.value = menuData.title;
    els.inputUrl.value = menuData.url;
    if (els.inputBg) els.inputBg.value = menuData.bg;
    els.inputIcon.value = menuData.icon;
    els.inputUrutan.value = menuData.urutan;
    
    els.modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeMenuModal() {
    const modal = document.getElementById('modalMenuForm');
    if (modal) { 
        modal.style.display = 'none'; 
        document.body.style.overflow = 'auto'; 
    }
}

document.addEventListener('keydown', (event) => {
    const modal = document.getElementById('modalMenuForm');
    if (event.key === "Escape" && modal && modal.style.display === 'flex') {
        closeMenuModal();
    }
});

// Fungsi untuk membuka modal Kritik & Saran
        function openKritikModal() {
            document.getElementById('modalKritikSaran').style.display = 'flex';
        }

        // Fungsi untuk menutup modal Kritik & Saran
        function closeKritikModal() {
            document.getElementById('modalKritikSaran').style.display = 'none';
        }

        // Opsional: Menutup modal jika user mengklik area luar modal (overlay)
        window.onclick = function(event) {
            const modalKritik = document.getElementById('modalKritikSaran');
            if (event.target === modalKritik) {
                closeKritikModal();
            }
        }