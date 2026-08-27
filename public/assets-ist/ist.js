document.addEventListener("DOMContentLoaded", function () {

    /* ====================================================================
       1. FUNGSI MODAL PENGATURAN UMUM (PERIODE & PERTANYAAN)
       ==================================================================== */
    window.bukaModalPeriode = function () {
        const modal = document.getElementById('modalPeriode');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalPeriode = function () {
        const modal = document.getElementById('modalPeriode');
        if (modal) modal.style.display = 'none';
    };

    window.bukaModalPertanyaan = function () {
        const modal = document.getElementById('modalPertanyaan');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalPertanyaan = function () {
        const modal = document.getElementById('modalPertanyaan');
        if (modal) modal.style.display = 'none';
    };

    window.tambahBarisPertanyaan = function () {
        const wadah = document.getElementById('wadah-pertanyaan');
        const barisBaru = document.createElement('div');
        barisBaru.className = 'grup-input baris-pertanyaan';
        barisBaru.style.cssText = 'flex-direction: row; gap: 15px; align-items: flex-start; border-bottom: 1px dashed #e2e8f0; padding-bottom: 10px; margin-top: 15px;';

        barisBaru.innerHTML = `
            <input type="hidden" name="id_pertanyaan[]" value="">
            <div style="flex: 1;">
                <textarea name="pertanyaan[]" class="input-form" rows="2" style="padding-top:10px;" placeholder="Tuliskan pertanyaan kuesioner baru..." required></textarea>
            </div>
            <div style="width: 120px;">
                <select name="is_active[]" class="input-form">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <button type="button" class="btn-hapus-baris" onclick="this.closest('.baris-pertanyaan').remove()">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        wadah.appendChild(barisBaru);
        wadah.scrollTo({ top: wadah.scrollHeight, behavior: 'smooth' }); // Scroll otomatis ke bawah
    };

    /* ====================================================================
       2. LOGIKA TAHAP 1.1: PENILAIAN LIVE SCORE & TABEL DINAMIS
       ==================================================================== */
    let penilaianData = window.istInitialPenilaian || {};
    let currentAssessedNip = null;

    // Sortir/rapikan tabel saat pertama kali dimuat
    setTimeout(() => {
        if (document.getElementById('tabel-kandidat')) window.updateTableUI();
    }, 300);

    window.editPenilaianUlang = function () {
        document.getElementById('wadah-hasil').style.display = 'none';
        document.getElementById('wadah-form').style.display = 'block';
        window.updateTableUI();
    };

    window.bukaModalPenilaian = function (nip, nama) {
        if (!penilaianData[nip] && Object.keys(penilaianData).length >= 3) {
            Swal.fire('Batas Maksimal!', 'Anda sudah menilai 3 kandidat. Silakan Hapus/Edit nilai kandidat lain untuk menggantinya.', 'warning');
            return;
        }

        currentAssessedNip = nip;
        document.getElementById('modal-nama-kandidat').innerText = nama;
        document.getElementById('live-score').innerText = penilaianData[nip] ? penilaianData[nip].total : 0;

        // Reset radio button
        document.querySelectorAll('.radio-nilai').forEach(el => el.checked = false);

        // Auto-checked jika sudah pernah dinilai
        if (penilaianData[nip]) {
            let answers = penilaianData[nip].answers;
            for (let qId in answers) {
                let radio = document.querySelector(`input[name="temp_q_${qId}"][value="${answers[qId]}"]`);
                if (radio) radio.checked = true;
            }
        }

        const modal = document.getElementById('modalPenilaian');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalPenilaian = function () {
        const modal = document.getElementById('modalPenilaian');
        if (modal) modal.style.display = 'none';
    };

    // Kalkulasi Live Score per Klik
    document.querySelectorAll('.radio-nilai').forEach(el => {
        el.addEventListener('change', function () {
            let total = 0;
            document.querySelectorAll('.radio-nilai:checked').forEach(r => {
                total += parseInt(r.value);
            });
            document.getElementById('live-score').innerText = total;

            // Efek detak zoom
            let scoreBox = document.getElementById('live-score');
            scoreBox.style.transform = 'scale(1.2)';
            setTimeout(() => scoreBox.style.transform = 'scale(1)', 200);
        });
    });

    window.simpanNilaiModal = function () {
        let answers = {};
        let total = 0;
        let totalQuestions = document.querySelectorAll('.ist-question-card').length;
        let answeredCount = document.querySelectorAll('.radio-nilai:checked').length;

        if (answeredCount < totalQuestions) {
            Swal.fire('Belum Lengkap!', `Anda baru mengisi ${answeredCount} dari ${totalQuestions} pertanyaan kuesioner.`, 'warning');
            return;
        }

        document.querySelectorAll('.radio-nilai:checked').forEach(r => {
            let qId = r.getAttribute('data-qid');
            let val = parseInt(r.value);
            answers[qId] = val;
            total += val;
        });

        penilaianData[currentAssessedNip] = { total: total, answers: answers };
        window.tutupModalPenilaian();
        window.updateTableUI();
    };

    window.hapusNilai = function (nip, event) {
        event.stopPropagation();
        delete penilaianData[nip];
        window.updateTableUI();
    };

    // UPDATE UI SCORE & DATA-SORT (Satu-satunya versi yang dipertahankan)
    window.updateTableUI = function () {
        let table = document.getElementById('tbody-kandidat');
        if (!table) return;
        let rows = Array.from(table.querySelectorAll('tr'));

        rows.forEach(row => {
            let nip = row.getAttribute('data-nip');
            let btnArea = row.querySelector('.aksi-area');
            let scoreArea = row.querySelector('.score-area');

            if (penilaianData[nip]) {
                row.setAttribute('data-assessed', '1');
                row.style.background = '#f0fdf4';
                scoreArea.setAttribute('data-sort', penilaianData[nip].total);
                scoreArea.innerHTML = `<span class="badge-skor" style="font-size:14px; background:#dcfce7; color:#059669;">${penilaianData[nip].total} Poin</span>`;
                btnArea.innerHTML = `
                    <div style="display:flex; justify-content:center; gap:5px;">
                        <button type="button" class="btn-edit-nilai" onclick="bukaModalPenilaian('${nip}', '${row.getAttribute('data-nama')}')"><i class="fa-solid fa-pen"></i></button>
                        <button type="button" class="btn-hapus-nilai" onclick="hapusNilai('${nip}', event)"><i class="fa-solid fa-trash"></i></button>
                    </div>
                `;
            } else {
                row.setAttribute('data-assessed', '0');
                row.style.background = '';
                scoreArea.setAttribute('data-sort', '0');
                scoreArea.innerHTML = `-`;
                btnArea.innerHTML = `<button type="button" class="btn-menilai" onclick="bukaModalPenilaian('${nip}', '${row.getAttribute('data-nama')}')"><i class="fa-solid fa-star"></i> Menilai</button>`;
            }
        });

        // Sorting otomatis ke atas
        rows.sort((a, b) => {
            let aAssessed = parseInt(a.getAttribute('data-assessed'));
            let bAssessed = parseInt(b.getAttribute('data-assessed'));
            return bAssessed - aAssessed;
        });

        rows.forEach(row => table.appendChild(row));

        const panelFinal = document.getElementById('panel-finalisasi');
        if (Object.keys(penilaianData).length === 3) {
            panelFinal.style.display = 'block';
            panelFinal.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            panelFinal.style.display = 'none';
        }
    };

    window.submitFinalisasi = function () {
        Swal.fire({
            title: 'Kirim Penilaian?',
            text: "Data 3 kandidat ini akan disimpan ke sistem.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Kirim Sekarang!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('form-penilaian-final');
                let csrfToken = document.querySelector('input[name="_token"]').value;
                let periodeId = document.querySelector('input[name="periode_id"]')?.value || '';
                let nipPemilih = document.querySelector('input[name="nip_pemilih"]')?.value || '';

                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="periode_id" value="${periodeId}">
                    <input type="hidden" name="nip_pemilih" value="${nipPemilih}">
                `;

                for (let nip in penilaianData) {
                    let answers = penilaianData[nip].answers;
                    for (let qId in answers) {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `penilaian[${nip}][${qId}]`;
                        input.value = answers[qId];
                        form.appendChild(input);
                    }
                }
                form.submit();
            }
        });
    };

    /* ====================================================================
       3. FUNGSI FILTER & SORTING TABEL (ADVANCED & MONITORING)
       ==================================================================== */
    window.istFilterTable = function () {
        let input = document.getElementById("input-cari-kandidat");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("tbody-kandidat");
        if(!table) return;
        let tr = table.getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
            if (!tr[i].getAttribute('data-nip')) continue;

            let tdProfil = tr[i].getElementsByTagName("td")[2];
            let tdJabatan = tr[i].getElementsByTagName("td")[3];

            if (tdProfil || tdJabatan) {
                let textProfil = tdProfil.textContent || tdProfil.innerText;
                let textJabatan = tdJabatan.textContent || tdJabatan.innerText;

                if (textProfil.toLowerCase().indexOf(filter) > -1 || textJabatan.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    };

    window.istSortTable = function (n) {
        let table = document.getElementById("tabel-kandidat");
        if (!table) return;
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc";

        let headers = table.getElementsByTagName("TH");
        for (let h = 0; h < headers.length; h++) {
            let icon = headers[h].querySelector('i.fa-sort, i.fa-sort-up, i.fa-sort-down');
            if (icon) {
                icon.className = 'fa-solid fa-sort';
                icon.style.opacity = '0.3';
            }
        }
        let currentHeaderIcon = headers[n].querySelector('i');
        if (currentHeaderIcon) currentHeaderIcon.style.opacity = '1';

        while (switching) {
            switching = false;
            rows = table.getElementsByTagName("TR");

            for (i = 1; i < (rows.length - 1); i++) {
                if (!rows[i].getAttribute('data-nip')) continue;

                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                let valX = x.getAttribute('data-sort') !== null ? x.getAttribute('data-sort') : x.innerText.toLowerCase();
                let valY = y.getAttribute('data-sort') !== null ? y.getAttribute('data-sort') : y.innerText.toLowerCase();

                if (!isNaN(valX) && !isNaN(valY)) {
                    valX = parseFloat(valX);
                    valY = parseFloat(valY);
                } else {
                    valX = valX.toString().toLowerCase();
                    valY = valY.toString().toLowerCase();
                }

                if (dir == "asc") {
                    if (valX > valY) { shouldSwitch = true; break; }
                } else if (dir == "desc") {
                    if (valX < valY) { shouldSwitch = true; break; }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }

        if (currentHeaderIcon) {
            currentHeaderIcon.className = dir === 'asc' ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
        }
    };

    // --- Inisialisasi Sorting Tabel Monitoring Khusus (Tabel 1_1 & 1_2) ---
    var sortState = {};
    function initTableSort(tableId) {
        var table = document.getElementById(tableId);
        if (!table) return;
        var ths = table.querySelectorAll('thead th[data-sort-col]');
        ths.forEach(function(th) {
            th.addEventListener('click', function() {
                var col = parseInt(th.getAttribute('data-sort-col'));
                var type = th.getAttribute('data-sort-type') || 'string';
                if (!sortState[tableId]) sortState[tableId] = { col: -1, asc: true };
                
                var state = sortState[tableId];
                var asc = (state.col === col) ? !state.asc : true;
                state.col = col;
                state.asc = asc;

                ths.forEach(function(h) {
                    var icon = h.querySelector('.sort-icon');
                    if (icon) icon.textContent = '⇅';
                    h.style.background = '';
                });

                var myIcon = th.querySelector('.sort-icon');
                if (myIcon) myIcon.textContent = asc ? '↑' : '↓';
                th.style.background = 'rgba(99,102,241,0.08)';

                var tbody = table.querySelector('tbody');
                var rows = Array.from(tbody.querySelectorAll('tr'));
                rows.sort(function(a, b) {
                    var cellA = a.cells[col];
                    var cellB = b.cells[col];
                    if (!cellA || !cellB) return 0;
                    var valA = (cellA.getAttribute('data-val') || cellA.textContent).trim();
                    var valB = (cellB.getAttribute('data-val') || cellB.textContent).trim();
                    var cmp = (type === 'number') ? (parseFloat(valA) - parseFloat(valB)) : valA.localeCompare(valB, 'id');
                    return asc ? cmp : -cmp;
                });
                rows.forEach(r => tbody.appendChild(r));
            });
        });
    }

    initTableSort('tbl-mon-1_1');
    initTableSort('tbl-mon-1_2');
    window._monSortInited = true;


    /* ====================================================================
       4. FUNGSI MODAL MONITORING & DOWNLOAD GAMBAR
       ==================================================================== */
    window.bukaModalMonitoring = function () {
        const modal = document.getElementById('modalMonitoring');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalMonitoring = function () {
        const modal = document.getElementById('modalMonitoring');
        if (modal) modal.style.display = 'none';
    };

    // Gabungan Switch Tab dan Tampilan Tombol Download
    window.switchMonTab = function (tabId) {
        document.getElementById('btn-mon-1_1').classList.remove('active');
        document.getElementById('btn-mon-1_2').classList.remove('active');
        document.getElementById('btn-mon-' + tabId).classList.add('active');

        document.getElementById('mon-tab-1_1').style.display = 'none';
        document.getElementById('mon-tab-1_2').style.display = 'none';
        document.getElementById('mon-tab-' + tabId).style.display = 'block';

        const btn11 = document.getElementById('btn-download-mon-1_1');
        const btn12 = document.getElementById('btn-download-mon-1_2');
        if (btn11) btn11.style.display = tabId === '1_1' ? 'inline-flex' : 'none';
        if (btn12) btn12.style.display = tabId === '1_2' ? 'inline-flex' : 'none';
    };

    window.downloadMonitoringImage = function(tabId, filename) {
        var sourceEl = (tabId === 'mon-tab-1_1') ? document.getElementById('rekap-partisipasi-wrapper') : document.getElementById(tabId);
        
        if (!sourceEl) {
            alert('Konten tidak ditemukan.');
            return;
        }

        var wrapper = document.createElement('div');
        wrapper.style.cssText = 'background:#f8fafc; padding:24px; font-family:Poppins,sans-serif; min-width:700px; display:inline-block;';

        var header = document.createElement('div');
        header.style.cssText = 'text-align:center; margin-bottom:18px; padding-bottom:14px; border-bottom:2px solid #e2e8f0;';
        header.innerHTML = '<div style="font-size:18px; font-weight:700; color:#1e293b;"><span style="color:#f79039;">&#9733;</span> BPS Provinsi Aceh &mdash; Monitoring IST</div>' +
                           '<div style="font-size:12px; color:#64748b; margin-top:4px;">Diunduh pada: ' + new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + '</div>';
        
        wrapper.appendChild(header);
        var clone = sourceEl.cloneNode(true);
        clone.style.display = 'block';
        wrapper.appendChild(clone);

        document.body.appendChild(wrapper);
        wrapper.style.position = 'fixed';
        wrapper.style.top = '-9999px';
        wrapper.style.left = '-9999px';

        var btn = document.getElementById(tabId === 'mon-tab-1_1' ? 'btn-download-mon-1_1' : 'btn-download-mon-1_2');
        var origText = btn ? btn.innerHTML : '';
        if (btn) btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyiapkan...';

        html2canvas(wrapper, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#f8fafc',
            logging: false
        }).then(function(canvas) {
            document.body.removeChild(wrapper);
            if (btn) btn.innerHTML = origText;
            var link = document.createElement('a');
            link.download = filename + '-' + new Date().toISOString().slice(0, 10) + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(function(err) {
            document.body.removeChild(wrapper);
            if (btn) btn.innerHTML = origText;
            console.error(err);
            alert('Gagal mengunduh gambar. Pastikan library html2canvas termuat.');
        });
    };

    window.copyDaftarWA = function (elementId) {
        let copyText = document.getElementById(elementId);
        if (copyText) {
            const suksesAlert = () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin!',
                    text: 'Daftar nama berhasil disalin. Silakan Paste (Tempel) langsung di obrolan WhatsApp.',
                    timer: 2500,
                    showConfirmButton: false,
                    backdrop: `rgba(0,0,0,0.4)`
                });
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(copyText.value)
                    .then(() => suksesAlert())
                    .catch(err => console.error("Gagal menyalin via Clipboard API: ", err));
            } else {
                copyText.style.display = 'block';
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                try {
                    document.execCommand("copy");
                    suksesAlert();
                } catch (err) {
                    Swal.fire('Gagal!', 'Browser Anda menolak akses papan klip.', 'error');
                }
                copyText.style.display = 'none';
            }
        }
    };

    /* ====================================================================
       5. FUNGSI TAHAP 2 (BERKAS ADMIN & VALIDASI SK)
       ==================================================================== */
    window.bukaModalBerkasAdmin = function() {
        document.getElementById('modalBerkasAdmin').style.display = 'flex';
    };

    window.tutupModalBerkasAdmin = function() {
        document.getElementById('modalBerkasAdmin').style.display = 'none';
        window.resetFormBerkas();
    };

    window.editBerkasAdmin = function(id, urutan, nama, ket, link) {
        document.getElementById('input_berkas_id').value = id;
        document.getElementById('input_urutan').value = urutan;
        document.getElementById('input_nama_dokumen').value = nama;
        document.getElementById('input_keterangan').value = ket;
        document.getElementById('input_link_template').value = link;
        document.getElementById('btnSubmitBerkas').innerText = "Update Dokumen";
    };

    window.resetFormBerkas = function() {
        document.getElementById('input_berkas_id').value = "";
        document.getElementById('formBerkasAdmin').reset();
        document.getElementById('btnSubmitBerkas').innerText = "Simpan Dokumen";
    };

    window.bukaModalSubmitBerkas = function(berkasId, namaDokumen, linkEksisting, catatanEksisting) {
        document.getElementById('submit_berkas_id').value = berkasId;
        document.getElementById('submit_nama_dokumen').value = namaDokumen;
        document.getElementById('submit_link_berkas').value = linkEksisting;
        document.getElementById('submit_catatan').value = catatanEksisting;
        document.getElementById('modalSubmitBerkas').style.display = 'flex';
    };

    window.tutupModalSubmitBerkas = function() {
        document.getElementById('modalSubmitBerkas').style.display = 'none';
    };

    window.bukaPopupValidasiSk = function(kodeWilayah, namaSatker, namaKandidat, linkSk, sudahValidasi, periodeId, nipKandidat) {
        document.getElementById('popup-sk-satker').textContent = namaSatker;
        document.getElementById('popup-sk-nama').textContent = namaKandidat;
        document.getElementById('popup-sk-link').href = linkSk;
        document.getElementById('popup-sk-link-text').textContent = 'Buka Dokumen SK (' + linkSk.substring(0, 40) + '...)';
        document.getElementById('popup-sk-periode-id').value = periodeId;
        document.getElementById('popup-sk-nip').value = nipKandidat;

        var statusBox = document.getElementById('popup-sk-status-box');
        var btnArea = document.getElementById('popup-sk-buttons-area');

        if (sudahValidasi === 'tervalidasi') {
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#dcfce7;color:#16a34a;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #86efac;"><i class="fa-solid fa-circle-check"></i> Status Saat Ini: Tervalidasi</span>';
            btnArea.innerHTML = '<button type="button" onclick="konfirmasiValidasiPopup(\'cabut\')" style="flex:1; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(249,115,22,0.4);"><i class="fa-solid fa-rotate-left"></i> Cabut Validasi</button>';
        } else if (sudahValidasi === 'ditolak') {
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#fee2e2;color:#dc2626;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #fca5a5;"><i class="fa-solid fa-circle-xmark"></i> Status Saat Ini: Ditolak</span>';
            btnArea.innerHTML = '<button type="button" onclick="konfirmasiValidasiPopup(\'cabut\')" style="flex:1; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(249,115,22,0.4);"><i class="fa-solid fa-rotate-left"></i> Cabut / Reset Status</button>';
        } else {
            statusBox.innerHTML = '<span style="display:inline-flex;align-items:center;gap:8px;background:#fefce8;color:#ca8a04;padding:8px 18px;border-radius:30px;font-size:12px;font-weight:700;border:1px solid #fde68a;"><i class="fa-solid fa-clock"></i> Status Saat Ini: Menunggu Validasi</span>';
            btnArea.innerHTML = '<button type="button" onclick="konfirmasiValidasiPopup(\'validasi\')" style="flex:1; min-width:140px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(16,185,129,0.4);"><i class="fa-solid fa-check-double"></i> Validasi SK</button>' +
                                '<button type="button" onclick="konfirmasiValidasiPopup(\'tolak\')" style="flex:1; min-width:140px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border:none; padding:12px 20px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 3px 10px rgba(239,68,68,0.4);"><i class="fa-solid fa-xmark"></i> Tolak Dokumen</button>';
        }

        document.getElementById('modalValidasiSk').style.display = 'flex';
    };

    window.tutupPopupValidasiSk = function() {
        document.getElementById('modalValidasiSk').style.display = 'none';
    };

    window.konfirmasiValidasiPopup = function(aksi) {
        var teks = '';
        if (aksi === 'validasi') teks = 'Yakin ingin memvalidasi dokumen SK ini?';
        else if (aksi === 'tolak') teks = 'Yakin ingin menolak dokumen SK ini? Kandidat akan diminta upload ulang.';
        else if (aksi === 'cabut') teks = 'Yakin ingin mencabut status validasi dokumen SK ini?';

        document.getElementById('teks-konfirmasi-sk').textContent = teks;
        
        document.getElementById('btn-lanjutkan-sk').onclick = function() {
            document.getElementById('popup-sk-aksi').value = aksi;
            document.getElementById('form-validasi-sk-popup').submit();
        };

        document.getElementById('modalConfirmSk').style.display = 'flex';
    };

    /* ====================================================================
       6. GLOBAL CLICK LISTENER & KONFIRMASI TAHAP 1.2
       ==================================================================== */
    window.addEventListener('click', function (e) {
        // Area Gelap Modal
        const modalPeriode = document.getElementById('modalPeriode');
        const modalPertanyaan = document.getElementById('modalPertanyaan');
        const modalPenilaian = document.getElementById('modalPenilaian');
        const modalMonitoring = document.getElementById('modalMonitoring');
        const modalPersyaratan = document.getElementById('modal-persyaratan');
        const modalValidasiSk = document.getElementById('modalValidasiSk');
        const modalConfirmSk = document.getElementById('modalConfirmSk');

        if (e.target === modalPeriode) window.tutupModalPeriode();
        if (e.target === modalPertanyaan) window.tutupModalPertanyaan();
        if (e.target === modalMonitoring) window.tutupModalMonitoring();
        if (e.target === modalPersyaratan) modalPersyaratan.style.display = 'none';
        if (e.target === modalValidasiSk) window.tutupPopupValidasiSk();
        if (e.target === modalConfirmSk) modalConfirmSk.style.display = 'none';
    });

    document.body.addEventListener('click', function (e) {
        // Konfirmasi Penetapan Tahap 1.2
        let btnTetapkan = e.target.closest('.btn-tetapkan');
        if (btnTetapkan) {
            e.preventDefault();
            const form = btnTetapkan.closest('.form-tetapkan');
            Swal.fire({
                title: 'Tetapkan IST?',
                text: "Pegawai ini akan menjadi IST perwakilan Satker Anda ke tingkat Provinsi Aceh.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f79039',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tetapkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }

        // Konfirmasi Pembatalan Tahap 1.2
        let btnBatalkan = e.target.closest('.btn-batalkan');
        if (btnBatalkan) {
            e.preventDefault();
            const form = btnBatalkan.closest('.form-batalkan');
            Swal.fire({
                title: 'Batalkan Penetapan?',
                text: "Kandidat ini tidak lagi menjadi perwakilan Satker Anda dan tabel klasemen akan kembali terbuka.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    });

    /* ====================================================================
       7. EVENT LISTENER LAINNYA (TOMBOL PERSYARATAN)
       ==================================================================== */
    const btnInfoPersyaratan = document.getElementById('btn-info-persyaratan');
    const modalPersyaratan = document.getElementById('modal-persyaratan');
    const tutupModalPersyaratan = document.getElementById('tutup-modal-persyaratan');
    const btnTutupPersyaratan = document.getElementById('btn-tutup-persyaratan');

    if (btnInfoPersyaratan && modalPersyaratan) {
        btnInfoPersyaratan.addEventListener('click', function () {
            modalPersyaratan.style.display = 'flex';
        });

        if (tutupModalPersyaratan) {
            tutupModalPersyaratan.addEventListener('click', function () {
                modalPersyaratan.style.display = 'none';
            });
        }

        if (btnTutupPersyaratan) {
            btnTutupPersyaratan.addEventListener('click', function () {
                modalPersyaratan.style.display = 'none';
            });
        }
    }

    window.switchTabTahap2 = function (tabId) {
        // Reset tombol active
        document.getElementById('btn-tab-t2-kandidat').classList.remove('active');
        document.getElementById('btn-tab-t2-kepala').classList.remove('active');
        
        // Sembunyikan semua wadah
        document.getElementById('wadah-t2-kandidat').style.display = 'none';
        document.getElementById('wadah-t2-kepala').style.display = 'none';
        
        // Aktifkan yang dipilih
        document.getElementById('btn-tab-t2-' + tabId).classList.add('active');
        document.getElementById('wadah-t2-' + tabId).style.display = 'block';
    };

    /* ====================================================================
   FUNGSI TAHAP 2 (PENGUMPULAN BERKAS PIMPINAN)
   ==================================================================== */
    window.bukaModalSubmitBerkasKepala = function(berkasId, namaDokumen, linkEksisting, catatanEksisting) {
        document.getElementById('submit_berkas_id_kepala').value = berkasId;
        document.getElementById('submit_nama_dokumen_kepala').value = namaDokumen;
        document.getElementById('submit_link_berkas_kepala').value = linkEksisting;
        document.getElementById('submit_catatan_kepala').value = catatanEksisting;
        
        document.getElementById('modalSubmitBerkasKepala').style.display = 'flex';
    };

    window.tutupModalSubmitBerkasKepala = function() {
        document.getElementById('modalSubmitBerkasKepala').style.display = 'none';
    };

    window.addEventListener('click', function (e) {
        // ... elemen modal lainnya ...
        const modalSubmitBerkasKepala = document.getElementById('modalSubmitBerkasKepala');
        
        // ... kondisi lainnya ...
        if (e.target === modalSubmitBerkasKepala) window.tutupModalSubmitBerkasKepala();
    });

    /* ====================================================================
   FUNGSI MANAJEMEN MASTER BERKAS PIMPINAN (KHUSUS ADMIN)
   ==================================================================== */
    window.bukaModalBerkasKepala = function() {
        const modal = document.getElementById('modalBerkasKepala');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalBerkasKepala = function() {
        const modal = document.getElementById('modalBerkasKepala');
        if (modal) modal.style.display = 'none';
        window.resetFormBerkasKepala();
    };

    window.editBerkasKepala = function(id, urutan, nama, ket, link) {
        document.getElementById('input_berkas_id_kepala').value = id;
        document.getElementById('input_urutan_kepala').value = urutan;
        document.getElementById('input_nama_dokumen_kepala').value = nama;
        document.getElementById('input_keterangan_kepala').value = ket;
        document.getElementById('input_link_template_kepala').value = link;
        document.getElementById('btnSubmitBerkasKepala').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Update Dokumen';
    };

    window.resetFormBerkasKepala = function() {
        document.getElementById('input_berkas_id_kepala').value = "";
        document.getElementById('formBerkasKepala').reset();
        document.getElementById('btnSubmitBerkasKepala').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Dokumen';
    };
});