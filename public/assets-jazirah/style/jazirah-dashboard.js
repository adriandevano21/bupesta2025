document.addEventListener('DOMContentLoaded', function() {
    // 1. Efek Typewriter
    const element = document.getElementById("typewriter");
    if (element && typeof window.userNameDashboard !== 'undefined') {
        const fullText = `Halo, ${window.userNameDashboard}! Selamat Datang..`;
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
            const speed = isDeleting ? 50 : 100;
            setTimeout(type, speed);
        }
        type();
    }

    // 2. DataTables Sorting & Search
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#dataTableMonitoring')) {
            $('#dataTableMonitoring').DataTable().destroy();
        }
        $('#dataTableMonitoring').DataTable({
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

    // 3. Download Image (html2canvas)
    const downloadBtn = document.getElementById('downloadBtn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            let btn = this;
            let originalText = btn.innerHTML;
            btn.innerHTML = '<span>Memproses...</span>';
            btn.disabled = true;

            let targetTable = document.getElementById('tabel-monitoring');

            if (typeof html2canvas !== 'undefined') {
                html2canvas(targetTable, {
                    scale: 2,
                    backgroundColor: "#ffffff",
                    useCORS: true
                }).then(canvas => {
                    let imageURL = canvas.toDataURL("image/png");
                    let downloadLink = document.createElement('a');
                    downloadLink.href = imageURL;
                    downloadLink.download = 'Monitoring_Jazirah.png';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }).catch(err => {
                    alert("Gagal mengunduh gambar.");
                    console.error("Error html2canvas: ", err);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            } else {
                alert("Pustaka html2canvas belum dimuat.");
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    }

    // 4. Kunci Layar jika Modal Lengkapi Profil Muncul
    const modalProfil = document.getElementById('modalLengkapiProfil');
    if (modalProfil) {
        document.body.style.overflow = 'hidden';
        document.body.style.height = '100vh';

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                event.preventDefault();
                event.stopPropagation();
            }
        }, true);
    }
});
