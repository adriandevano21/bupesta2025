// ==========================================================
// STATE GLOBAL & UTILS
// ==========================================================
window.BupestaUsersMap = window.BupestaUsersMap || {};
let selectedPjs = [];

function generateData2(link) {
    if (link && link.trim() !== '') {
        window.open(link, '_blank');
    }
}

// ==========================================================
// 1. INISIALISASI DOM (EVENT LISTENER UTAMA)
// ==========================================================
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Tooltip Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, { 
            animation: true, 
            delay: { "show": 150, "hide": 100 } 
        });
    });

    // Logika Dropdown Pilar & Subpilar
    const pilarSelect = document.getElementById('select-pilar');
    const subpilarSelect = document.getElementById('select-subpilar');
    const subpilarWrapper = document.getElementById('wrapper-subpilar');

    if (pilarSelect && subpilarSelect) {
        const allSubOptions = Array.from(subpilarSelect.querySelectorAll('option:not([value=""])'));
        pilarSelect.addEventListener('change', function () {
            const selectedPilar = this.value;
            
            if (!selectedPilar) {
                if (subpilarWrapper) subpilarWrapper.style.display = 'none';
                else subpilarSelect.style.display = 'none';
                subpilarSelect.value = '';
                return;
            }
            
            if (subpilarWrapper) subpilarWrapper.style.display = 'block';
            else subpilarSelect.style.display = 'block';
            
            subpilarSelect.innerHTML = '<option value="">Semua Sub Pilar</option>';
            allSubOptions.forEach(opt => {
                if (opt.getAttribute('data-parent') === selectedPilar) {
                    subpilarSelect.appendChild(opt.cloneNode(true));
                }
            });
        });
    }

    // Sinkronisasi Checkbox ke Input Hidden (Bulan Target & Realisasi)
    const syncCheckboxToHidden = (checkboxClass, hiddenInputId) => {
        const hiddenInput = document.getElementById(hiddenInputId);
        document.querySelectorAll(checkboxClass).forEach(cb => {
            cb.addEventListener('change', () => {
                if (hiddenInput) {
                    const checkedValues = Array.from(document.querySelectorAll(`${checkboxClass}:checked`)).map(el => el.value);
                    hiddenInput.value = checkedValues.join(',');
                }
            });
        });
    };
    syncCheckboxToHidden('.e-bt-checkbox', 'e-bulan_target-hidden');
    syncCheckboxToHidden('.e-br-checkbox', 'e-bulan_realisasi-hidden');

    // Autocomplete Input Penanggung Jawab (PJ)
    const pjSearchInput = document.getElementById('pj-search-input');
    const pjTagContainer = document.getElementById('pj-tag-container');

    if (pjSearchInput && pjTagContainer) {
        pjSearchInput.addEventListener('focus', function () { tampilkanDropdownPj(this.value); });
        pjTagContainer.addEventListener('click', function () { 
            pjSearchInput.focus(); 
            tampilkanDropdownPj(pjSearchInput.value); 
        });
        pjSearchInput.addEventListener('input', function () { tampilkanDropdownPj(this.value); });
        
        // Sembunyikan dropdown jika klik di luar area
        document.addEventListener('click', function (e) {
            const wrapper = document.querySelector('.pj-wrapper');
            const pjDropdown = document.getElementById('pj-dropdown');
            if (wrapper && pjDropdown && !wrapper.contains(e.target)) {
                pjDropdown.style.display = 'none';
            }
        });
    }

    // ==========================================================
    // INISIALISASI EVENT LISTENER CHAT & VALIDASI (STATIS)
    // ==========================================================
    setupChatAndValidationEvents();
});

// ==========================================================
// 2. FUNGSI EXPORT (PDF & EXCEL)
// ==========================================================
function getCleanTableForExport() {
    const table = document.getElementById("jazirahTable");
    if (!table) return null;
    
    const cloneTable = table.cloneNode(true);
    cloneTable.classList.remove('j-modern-table', 'table');
    cloneTable.removeAttribute('id');

    cloneTable.querySelectorAll('tr').forEach(row => {
        if (row.cells.length > 0) row.deleteCell(0); // Hapus kolom aksi
        row.querySelectorAll('i').forEach(i => i.remove()); // Hapus ikon
        
        // Kembalikan teks yang terpotong (truncate) agar tampil penuh
        row.querySelectorAll('.j-text-truncate').forEach(t => { 
            t.style.display = "block"; 
            t.style.webkitLineClamp = "unset"; 
            t.style.overflow = "visible"; 
            t.style.whiteSpace = "normal";
        });
    });
    return cloneTable;
}

function exportToPDF() {
    const cleanTable = getCleanTableForExport();
    if (!cleanTable) { alert("Tabel tidak ditemukan!"); return; }

    const satkerSelect = document.querySelector('select[name="satker"]');
    let namaSatker = "BPS Provinsi Aceh"; 
    if (satkerSelect && satkerSelect.options.length > 0) {
        namaSatker = satkerSelect.options[satkerSelect.selectedIndex].text.trim();
    }
    const tahun = "2026"; 

    const htmlString = `
        <!DOCTYPE html><html><head><style>
            * { box-sizing: border-box; }
            body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #ffffff; color: #000000; }
            h2 { text-align: center; color: #ea580c; margin-top: 10px; margin-bottom: 25px; font-size: 16px; }
            table { width: 100%; border-collapse: collapse; font-size: 9px; table-layout: fixed; }
            tr, td, th { page-break-inside: avoid !important; break-inside: avoid !important; }
            th { background-color: #f79039 !important; color: white !important; font-weight: bold; padding: 6px 4px; border: 1px solid #cbd5e1; text-align: center; vertical-align: middle; }
            td { padding: 6px 4px; border: 1px solid #cbd5e1; vertical-align: top; line-height: 1.3; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; }
            tr:nth-child(even) td { background-color: #f8fafc; }
            th:nth-child(1), td:nth-child(1) { width: 22%; } th:nth-child(2), td:nth-child(2) { width: 27%; } th:nth-child(3), td:nth-child(3) { width: 21%; } 
            th:nth-child(4), td:nth-child(4) { width: 13%; text-align: center; } th:nth-child(5), td:nth-child(5) { width: 8%; text-align: center; } th:nth-child(6), td:nth-child(6) { width: 9%; text-align: center; } 
            .j-indent { display: block; } .j-indent-lvl-1 { font-weight: bold; font-size: 10px; } .j-indent-lvl-2 { padding-left: 8px; font-weight: bold; }
            .j-indent-lvl-3 { padding-left: 16px; } .j-indent-lvl-4 { padding-left: 24px; } .j-indent-lvl-5 { padding-left: 32px; } .j-dash-modern { display: none; } 
            .badge, .j-badge { display: block !important; width: 100% !important; white-space: normal !important; font-size: 7px !important; padding: 4px 2px !important; text-align: center; border-radius: 4px !important; }
            .j-target-text { display: block; font-size: 7px; background: #f1f5f9; padding: 4px 2px; border-radius: 4px; border: 1px solid #e2e8f0; white-space: normal; text-align: center; }
            .btn-profil-pjk { display: block !important; width: 100%; border: 1px solid #cbd5e1 !important; padding: 3px 2px !important; background: #ffffff !important; border-radius: 4px !important; font-size: 7px !important; margin-bottom: 3px !important; color: #334155 !important; text-align: center !important; white-space: normal !important; }
        </style></head>
        <body><div style="width: 100%; padding: 0 2px;"><h2>Lembar Kerja Jazirah - ${namaSatker} Tahun ${tahun}</h2>${cleanTable.outerHTML}</div></body></html>`;

    const opt = {
        margin: [0.5, 0.3, 0.6, 0.3], 
        filename: 'LKE_' + namaSatker.replace(/[^a-zA-Z0-9]/g, '_') + '_' + tahun + '.pdf',
        image: { type: 'jpeg', quality: 0.98 }, 
        pagebreak: { mode: ['css', 'legacy'] },
        html2canvas: { scale: 2, useCORS: true, logging: false, scrollX: 0, scrollY: 0 }, 
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(htmlString).save();
}

function exportToExcel() {
    const cleanTable = getCleanTableForExport();
    if (!cleanTable) return alert("Tabel tidak ditemukan!");
    
    const wb = XLSX.utils.table_to_book(cleanTable, { sheet: "Lembar Kerja", raw: true });
    const ws = wb.Sheets["Lembar Kerja"];
    if (ws) ws['!cols'] = [ { wch: 45 }, { wch: 40 }, { wch: 30 }, { wch: 25 }, { wch: 20 } ];
    XLSX.writeFile(wb, "Lembar_Kerja_Jazirah.xlsx");
}

// ==========================================================
// 3. MODAL PROFIL PEGAWAI
// ==========================================================
function showProfilPegawai(username) {
    const user = window.BupestaUsersMap[username];
    if (!user) return alert("Data pegawai tidak ditemukan pada database.");

    const setSafeText = (id, text) => { 
        const el = document.getElementById(id); 
        if (el) el.innerText = text; 
    };

    setSafeText('profil_nama', user.name || username);
    setSafeText('profil_nip', user.nip_pegawai || '-');
    setSafeText('profil_jabatan', user.jabatan || '-');
    setSafeText('profil_golongan', user.golongan || '-');
    setSafeText('profil_satker', user.kode_satker || '-');
    
    const noHp = user.no_hp || '-';
    const waLink = document.getElementById('profil_wa_link');
    const waText = document.getElementById('profil_no_hp');

    if (waLink) {
        if (noHp !== '-' && noHp.trim() !== '') {
            const phone = noHp.toString().replace(/[^0-9]/g, '').replace(/^0/, '62');
            waLink.href = 'https://wa.me/' + phone;
            waLink.style.cssText = 'pointer-events: auto; background: #25D366;';
            if (waText) waText.innerText = noHp;
        } else {
            waLink.href = 'javascript:void(0)';
            waLink.style.cssText = 'pointer-events: none; background: #cbd5e1;';
            if (waText) waText.innerText = '-';
        }
    }

    const modalEl = document.getElementById('modalProfilPegawai');
    if (modalEl) modalEl.style.display = 'flex';
}

function tutupModalProfilPegawai() {
    const modalEl = document.getElementById('modalProfilPegawai');
    if (modalEl) modalEl.style.display = 'none';
}

// ==========================================================
// 4. MODAL EDIT (FORM KUSTOM) & LOGIKA PENANGGUNG JAWAB
// ==========================================================
function bukaModalEdit(btn) {
    const getAttr = (attr) => btn.getAttribute(attr) || '';
    
    document.getElementById('e-id_isian').value = getAttr('data-id_isian');
    document.getElementById('e-rencana_kerja').innerText = getAttr('data-rencana_kerja') || '-';
    document.getElementById('e-rencanaaksi').value = getAttr('data-rencanaaksi');
    document.getElementById('e-output').value = getAttr('data-output');
    document.getElementById('e-link_buktidukung').value = getAttr('data-link_buktidukung');
    document.getElementById('e-status_dokumen').value = getAttr('data-status_dokumen') || '0'; 

    const kodeGabungan = [
        getAttr('data-kode_1'), getAttr('data-kode_2'), getAttr('data-kode_3'), 
        getAttr('data-kode_4'), getAttr('data-kode_5')
    ].filter(k => k.trim() !== '').join(' ');
    document.getElementById('e-kode_gabungan').innerText = kodeGabungan || '-';

    const btRaw = getAttr('data-bulan-target');
    const brRaw = getAttr('data-bulan_realisasi');
    document.getElementById('e-bulan_target-hidden').value = btRaw;
    document.getElementById('e-bulan_realisasi-hidden').value = brRaw;

    const checkCheckboxes = (checkboxClass, rawData) => {
        const dataArray = rawData.split(',').map(item => item.trim());
        document.querySelectorAll(checkboxClass).forEach(cb => { 
            cb.checked = dataArray.includes(cb.value); 
        });
    };
    
    checkCheckboxes('.e-bt-checkbox', btRaw);
    checkCheckboxes('.e-br-checkbox', brRaw);

    const pjRaw = getAttr('data-penanggungjawab');
    selectedPjs = pjRaw.split(',').map(u => u.trim()).filter(u => u !== '');
    renderPjTags();

    const rencanaAksiLalu = getAttr('data-rencanaaksi_lalu');
    const outputLalu = getAttr('data-output_lalu');

    document.getElementById('btn-copy-rencanaaksi').onclick = () => {
        if (rencanaAksiLalu.trim() !== '') document.getElementById('e-rencanaaksi').value = rencanaAksiLalu;
        else alert('Data Rencana Aksi tahun lalu kosong atau tidak ditemukan.');
    };

    document.getElementById('btn-copy-output').onclick = () => {
        if (outputLalu.trim() !== '') document.getElementById('e-output').value = outputLalu;
        else alert('Data Output tahun lalu kosong atau tidak ditemukan.');
    };

    document.getElementById('modalEditKustom').classList.add('tampil');
}

function tutupModalEdit() {
    document.getElementById('modalEditKustom').classList.remove('tampil');
    document.getElementById('formEditKustom').reset();
    
    const pjDropdown = document.getElementById('pj-dropdown');
    const pjSearch = document.getElementById('pj-search-input');
    if (pjDropdown) pjDropdown.style.display = 'none';
    if (pjSearch) pjSearch.value = '';
}

function renderPjTags() {
    const pjTagContainer = document.getElementById('pj-tag-container');
    const pjSearchInput = document.getElementById('pj-search-input');
    const pjHiddenInput = document.getElementById('e-penanggungjawab-hidden');
    
    if (!pjTagContainer || !pjSearchInput || !pjHiddenInput) return;

    document.querySelectorAll('.pj-tag').forEach(e => e.remove());
    
    selectedPjs.forEach(username => {
        const userObj = window.BupestaUsersMap[username];
        const displayName = userObj ? userObj.name : username;
        const tag = document.createElement('span');
        tag.className = 'pj-tag';
        tag.innerHTML = `${displayName} <span class="remove-tag" onclick="removePj(event, '${username}')">&times;</span>`;
        pjTagContainer.insertBefore(tag, pjSearchInput);
    });
    pjHiddenInput.value = selectedPjs.join(',');
}

window.removePj = function(event, username) {
    event.stopPropagation(); 
    selectedPjs = selectedPjs.filter(u => u !== username);
    renderPjTags();
};

function tampilkanDropdownPj(keyword = '') {
    const pjDropdown = document.getElementById('pj-dropdown');
    const pjSearchInput = document.getElementById('pj-search-input');
    if (!pjDropdown || !pjSearchInput) return;

    const val = keyword.toLowerCase().trim();
    pjDropdown.innerHTML = '';
    
    const listPegawai = window.SatkerUsersAktif || [];
    const matchedUsers = listPegawai.filter(u => 
        (u.name.toLowerCase().includes(val) || u.username.toLowerCase().includes(val)) && 
        !selectedPjs.includes(u.username)
    );

    if (matchedUsers.length > 0) {
        matchedUsers.forEach(u => {
            const item = document.createElement('div');
            item.className = 'pj-dropdown-item';
            item.innerHTML = `${u.name} <small class="text-muted">(${u.username})</small>`;
            item.onclick = function (e) {
                e.stopPropagation();
                selectedPjs.push(u.username);
                pjSearchInput.value = '';
                pjDropdown.style.display = 'none';
                renderPjTags();
            };
            pjDropdown.appendChild(item);
        });
        pjDropdown.style.display = 'block';
    } else {
        pjDropdown.style.display = 'none';
    }
}

// ==========================================================
// 5. TIMELINE CHART LOGIC (FOOLPROOF & DINAMIS)
// ==========================================================
function renderTimelineKustom(rawStatus) {
    // Mapping baru: Memisahkan Step yang selesai (completedUpTo) dan yang sedang aktif (activeStep)
    const statusMap = {
        '0': { completedUpTo: 0, activeStep: 1, color: '#cbd5e1', isWarning: false },
        '1': { completedUpTo: 1, activeStep: 2, color: '#059669', isWarning: false },
        '2': { completedUpTo: 2, activeStep: 3, color: '#059669', isWarning: false },
        '3': { completedUpTo: 3, activeStep: 4, color: '#ea580c', isWarning: true },
        // Status 4: Selesai di step 4, tapi garis progres menggantung di tengah menuju 5 (Tidak menyentuh Selesai)
        '4': { completedUpTo: 4, activeStep: null, color: '#059669', isWarning: false, lineHalfwayTo: 5 },
        '5': { completedUpTo: 5, activeStep: null, color: '#059669', isWarning: false }
    };
    
    const config = statusMap[rawStatus] || statusMap['0'];
    const defaultIcons = {
        1: 'fa-solid fa-pen-to-square',
        2: 'fa-solid fa-upload',
        3: 'fa-solid fa-magnifying-glass',
        4: 'fa-solid fa-wrench',
        5: 'fa-solid fa-check-double'
    };

    for (let i = 1; i <= 5; i++) {
        const stepEl = document.getElementById('step-' + i);
        if (!stepEl) continue;
        
        const circleIcon = stepEl.querySelector('.timeline-circle i');
        const circleDiv = stepEl.querySelector('.timeline-circle');
        stepEl.className = 'timeline-step'; 

        if (circleDiv) {
            circleDiv.style.borderColor = '';
            circleDiv.style.color = '';
            circleDiv.style.backgroundColor = '';
        }

        if (i <= config.completedUpTo) {
            stepEl.classList.add('completed');
            circleIcon.className = 'fa-solid fa-check';
        } else if (i === config.activeStep) {
            stepEl.classList.add(config.isWarning ? 'error' : 'active');
            circleIcon.className = config.isWarning ? 'fa-solid fa-exclamation' : defaultIcons[i];
            
            if (config.isWarning && circleDiv) {
                circleDiv.style.borderColor = config.color;
                circleDiv.style.color = config.color;
            }
        } else {
            circleIcon.className = defaultIcons[i];
        }
    }

    // PENGUKURAN GARIS DINAMIS
    setTimeout(() => {
        const wrapper = document.querySelector('.timeline-wrapper');
        const progressLine = document.getElementById('timeline-progress');
        const bgLine = document.querySelector('.timeline-bg-line');
        
        const firstStep = document.getElementById('step-1');
        const lastStep = document.getElementById('step-5');
        
        if (wrapper && progressLine && firstStep && lastStep) {
            const startX = firstStep.offsetLeft + (firstStep.offsetWidth / 2);
            const endX = lastStep.offsetLeft + (lastStep.offsetWidth / 2);

            // Set garis abu-abu background
            if (bgLine) {
                bgLine.style.left = startX + 'px';
                bgLine.style.width = (endX - startX) + 'px';
            }

            let activeX = startX;

            // Jika status 4 (lineHalfwayTo terisi), hitung koordinat tengah
            if (config.lineHalfwayTo) {
                const currentStepEl = document.getElementById('step-' + config.completedUpTo);
                const nextStepEl = document.getElementById('step-' + config.lineHalfwayTo);
                if (currentStepEl && nextStepEl) {
                    const currentX = currentStepEl.offsetLeft + (currentStepEl.offsetWidth / 2);
                    const nextX = nextStepEl.offsetLeft + (nextStepEl.offsetWidth / 2);
                    activeX = currentX + ((nextX - currentX) / 2); // Stop di tengah
                }
            } else {
                const targetIndex = config.activeStep || config.completedUpTo || 1;
                const targetStepEl = document.getElementById('step-' + targetIndex);
                if (targetStepEl) {
                    activeX = targetStepEl.offsetLeft + (targetStepEl.offsetWidth / 2);
                }
            }

            progressLine.style.background = config.color;
            progressLine.style.left = startX + 'px';
            progressLine.style.width = (activeX - startX) + 'px';
        }
    }, 50);
}

// ==========================================================
// 6. MODAL DETAIL KUSTOM (READ-ONLY & CHAT ROOM)
// ==========================================================
function bukaModalKustom(btn) {
    const setVal = (id, attr) => {
        const val = btn.getAttribute(attr);
        const el = document.getElementById(id);
        if (el) el.textContent = (val && val.trim() !== '') ? val : '-';
    };

    // --- LOGIKA UPDATE TOOLTIP TIMELINE ---
    for (let i = 1; i <= 5; i++) {
        let cb = btn.getAttribute('data-cb' + i);
        let ca = btn.getAttribute('data-ca' + i);
        let stepEl = document.getElementById('step-' + i);

        if (stepEl) {
            let tooltipText = "Belum ada riwayat";
            if (cb && ca && cb !== '-' && ca !== '') {
                tooltipText = `<strong>Oleh:</strong> ${cb}<br><strong>Tanggal:</strong> ${ca}`;
            }

            let tooltipInstance = bootstrap.Tooltip.getInstance(stepEl);
            if (tooltipInstance) {
                tooltipInstance.dispose();
            }

            stepEl.removeAttribute('title');
            stepEl.setAttribute('data-bs-original-title', tooltipText);

            new bootstrap.Tooltip(stepEl, {
                html: true,
                placement: 'top',
                container: '#modalDetailKustom',
                trigger: 'hover focus'
            });
        }
    }

    setVal('k-rencana_kerja', 'data-rencana_kerja');
    setVal('k-dokumen_ped', 'data-dokumen_ped');
    setVal('k-rencanaaksi', 'data-rencanaaksi');
    setVal('k-output', 'data-output');
    setVal('k-bulan_target', 'data-bulan-target');
    setVal('k-bulan_realisasi', 'data-bulan_realisasi');

    const kodeGabungan = [
        btn.getAttribute('data-kode_1'), btn.getAttribute('data-kode_2'),
        btn.getAttribute('data-kode_3'), btn.getAttribute('data-kode_4'),
        btn.getAttribute('data-kode_5')
    ].filter(k => k && k.trim() !== '').join(' ');
    
    document.getElementById('k-kode_gabungan').textContent = kodeGabungan || '-';

    const pjRaw = btn.getAttribute('data-penanggungjawab');
    const pjContainer = document.getElementById('k-penanggungjawab-container');
    pjContainer.innerHTML = '';
    
    if (pjRaw && pjRaw.trim() !== '') {
        pjRaw.split(',').forEach(pj => {
            if (pj.trim() !== '') {
                const span = document.createElement('span');
                span.className = 'kustom-badge-pj';
                span.textContent = pj.trim();
                span.title = "Klik untuk melihat profil pegawai";
                span.onclick = () => showProfilPegawai(pj.trim());
                pjContainer.appendChild(span);
            }
        });
    } else {
        pjContainer.textContent = '-';
    }

    [{ id: 'k-contoh_link_ped', attr: 'data-contoh_link_ped' }, 
     { id: 'k-link_lainnya', attr: 'data-link_lainnya' }].forEach(item => {
        const el = document.getElementById(item.id);
        const val = btn.getAttribute(item.attr);
        
        if (val && val.trim() !== '') { 
            el.textContent = 'Buka Tautan'; 
            el.href = val; 
        } else { 
            el.textContent = '-'; 
            el.removeAttribute('href'); 
        }
    });

    const linkBukti = btn.getAttribute('data-link_buktidukung');
    const iframe = document.getElementById('k-iframe-bukti');
    const fallback = document.getElementById('k-iframe-fallback');
    const directBtn = document.getElementById('k-btn-direct-bukti');

    if (linkBukti && linkBukti.trim() !== '') {
        let embedUrl = linkBukti;
        try {
            const urlObj = new URL(linkBukti);
            if (urlObj.hostname.includes('drive.google.com')) {
                // Cari ID folder Google Drive
                const folderMatch = linkBukti.match(/\/folders\/([a-zA-Z0-9-_]+)/);
                if (folderMatch && folderMatch[1]) {
                    // MENGGUNAKAN #list AGAR TAMPILAN MENJADI BENTUK DAFTAR & MUNCUL TANGGAL UPDATE
                    embedUrl = `https://drive.google.com/embeddedfolderview?id=${folderMatch[1]}#list`;
                } else if (urlObj.pathname.includes('/view')) {
                    embedUrl = linkBukti.replace('/view', '/preview');
                }
            }
        } catch (e) {
            // Abaikan parsing error jika URL bukan format standar
        }

        iframe.src = embedUrl;
        iframe.style.display = 'block';
        fallback.style.display = 'none';
        
        directBtn.style.display = 'inline-flex';
        directBtn.href = linkBukti;
    } else {
        iframe.src = '';
        iframe.style.display = 'none';
        fallback.style.display = 'block';
        directBtn.style.display = 'none';
    }   

    const rawStatus = btn.getAttribute('data-status_dokumen') || '0';
    renderTimelineKustom(rawStatus);

    //================================================
    // SETUP KONTROL VALIDASI
    //================================================
    const idIsian = btn.getAttribute('data-id_isian');
    const canComment = btn.getAttribute('data-can_comment') === '1';
    const canValidate = btn.getAttribute('data-can_validate') === '1';
    const statusDokumen = parseInt(rawStatus) || 0; 

    const chatInputArea = document.querySelector('.wa-input-area');
    const chatInput = document.getElementById('k-chat-input');
    const btnKirimPesan = document.getElementById('btn-kirim-pesan');
    const chatContainer = document.getElementById('k-chat-messages');
    const btnValidasi = document.getElementById('btn-validasi-dokumen');

    if (btnKirimPesan) btnKirimPesan.setAttribute('data-id_hasil', idIsian);
    
    if (btnValidasi) {
        if (canValidate && [2, 3, 4].includes(statusDokumen)) {
            btnValidasi.style.display = 'inline-flex';
            btnValidasi.setAttribute('data-id', idIsian);
        } else {
            btnValidasi.style.display = 'none';
        }
    }

    if (chatContainer) chatContainer.innerHTML = ''; 

    // Jika Dokumen Belum Diisi Bukti
    if (statusDokumen < 2) {
        if (chatInputArea) chatInputArea.style.display = 'none'; 
        if (chatContainer) {
            chatContainer.innerHTML = `
                <div class="text-center text-muted shadow-sm" style="margin: auto; background: rgba(255,255,255,0.9); padding: 20px; border-radius: 15px; font-size:0.85rem; width: 85%;">
                    <i class="fa-solid fa-lock text-warning mb-3" style="font-size: 2.5rem;"></i><br>
                    <strong style="font-size: 1rem; color: #334155;">Ruang Diskusi Terkunci</strong><br>
                    Diskusi dan evaluasi baru dapat dilakukan setelah bukti dukung diunggah/diisi<br>
                    <span class="badge bg-secondary mt-2">Status Dokumen Minimal Tahap 2</span>
                </div>`;
        }
        document.getElementById('modalDetailKustom').classList.add('tampil');
        return; 
    } 

    //================================================
    // RENDER CHAT JIKA STATUS >= 2
    //================================================
    let komentars = [];
    try {
        const rawData = btn.getAttribute('data-komentars');
        if (rawData && rawData.trim() !== '' && rawData !== 'W10=') { 
            komentars = JSON.parse(decodeURIComponent(escape(atob(rawData))));
        }
    } catch (e) {
        try { 
            komentars = JSON.parse(atob(btn.getAttribute('data-komentars'))); 
        } catch (err) {}
    }

    const nipAktif = String(window.userActiveNip); 

    if (komentars.length === 0) {
        chatContainer.innerHTML = `
            <div class="text-center text-muted shadow-sm" style="margin: auto; background: rgba(255,255,255,0.9); padding: 8px 15px; border-radius: 15px; font-size:0.8rem; width: max-content;">
                <i class="fa-solid fa-lock text-warning mb-1"></i><br>Pesan terenkripsi secara end-to-end.<br>Belum ada obrolan.
            </div>`;
    } else {
        let htmlChat = '';
        komentars.forEach((chat) => {
            try {
                const isMe = String(chat.nip) === nipAktif;
                let namaSender = isMe ? 'Anda' : (chat.pegawai?.name || 'Unknown User');
                const inisial = namaSender.substring(0, 2).toUpperCase();
                
                const arrayPj = (btn.getAttribute('data-penanggungjawab') || '').split(',').map(s => s.trim());
                const arrayCreator = (btn.getAttribute('data-created_by_3') || '').split(',').map(s => s.trim());
                
                const senderUsername = chat.pegawai?.username || '';
                const senderRoleRaw = chat.pegawai?.jazirah || 'Guest';
                const formatRole = (str) => str.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
                
                let roleLabel = '';
                if (senderUsername && arrayCreator.includes(senderUsername)) {
                    roleLabel = 'Evaluator';
                } else if (senderUsername && arrayPj.includes(senderUsername)) {
                    roleLabel = 'Penanggung Jawab';
                } else {
                    roleLabel = formatRole(senderRoleRaw);
                }
                
                const warnaLabel = isMe ? '#047857' : '#94a3b8';
                let namaSenderTampil = `${namaSender} <span style="font-weight: normal; font-size: 0.65rem; color: ${warnaLabel}; opacity: 0.9; margin-left: 4px;">(${roleLabel})</span>`;

                let waktu = '--:--';
                if (chat.created_at) {
                    const d = new Date(chat.created_at);
                    if (!isNaN(d.getTime())) { 
                        waktu = `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
                    }
                }
                const isiKomentar = chat.komentar || '';

                if (isMe) {
                    let iconHapus = statusDokumen === 5 ? '' : `<i class="fa-solid fa-trash ms-2 text-danger btn-hapus-chat" data-id="${chat.id}" style="font-size: 0.75rem; cursor: pointer; opacity: 0.7;" title="Hapus pesan ini"></i>`;
                    
                    htmlChat += `
                        <div class="wa-msg me">
                            <div class="wa-bubble">
                                <div class="wa-sender-name text-end mb-1" style="color: #059669;">${namaSenderTampil}</div>
                                <div class="wa-text">${isiKomentar}</div>
                                <div class="wa-time">${waktu} &nbsp; <i class="fa-solid fa-check-double ms-1 text-info" style="font-size: 0.6rem;"></i> ${iconHapus}</div>
                            </div>
                        </div>`;
                } else {
                    let fotoPath = chat.pegawai?.urlfoto || null;
                    let avatarHtml = fotoPath ? `<img src="${fotoPath}" alt="Avatar" onerror="this.outerHTML='<div class=\\'wa-avatar-text\\'>${inisial}</div>'">` : `<div class="wa-avatar-text">${inisial}</div>`;

                    htmlChat += `
                        <div class="wa-msg other">
                            <div class="wa-avatar">${avatarHtml}</div>
                            <div class="wa-bubble shadow-sm">
                                <div class="wa-sender-name">${namaSenderTampil}</div>
                                <div class="wa-text">${isiKomentar}</div>
                                <div class="wa-time">${waktu}</div>
                            </div>
                        </div>`;
                }
            } catch (loopError) {}
        });
        
// ... (Kode sebelum pengecekan status == 5)
        if (statusDokumen === 5) {
            htmlChat += `
                <div class="locked-chat-wrapper">
                    <div class="locked-chat-card">
                        <div class="locked-chat-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div class="locked-chat-text">
                            <strong>Diskusi Ditutup</strong>
                            <span>Dokumen ini telah divalidasi</span>
                        </div>
                    </div>
                </div>`;
        }
        
        chatContainer.innerHTML = htmlChat;
    }

    // KONTROL INPUT CHAT BERDASARKAN STATUS
    if (statusDokumen === 5) {
        if (chatInputArea) chatInputArea.style.display = 'none'; 
    } else {
        if (chatInputArea) chatInputArea.style.display = 'flex'; 
        if (chatInput && btnKirimPesan) {
            if (canComment) {
                chatInput.disabled = false;
                chatInput.placeholder = "Ketik pesan...";
                btnKirimPesan.disabled = false;
                btnKirimPesan.style.opacity = '1';
                btnKirimPesan.style.cursor = 'pointer';
            } else {
                chatInput.disabled = true;
                chatInput.placeholder = "Hanya PJK & Evaluator yang dapat membalas.";
                btnKirimPesan.disabled = true;
                btnKirimPesan.style.opacity = '0.5';
                btnKirimPesan.style.cursor = 'not-allowed';
            }
        }
    }

    setTimeout(() => { chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: 'smooth' }); }, 200);
    document.getElementById('modalDetailKustom').classList.add('tampil');
}

function tutupModalKustom() {
    document.getElementById('modalDetailKustom').classList.remove('tampil');
    const iframe = document.getElementById('k-iframe-bukti');
    if (iframe) iframe.src = '';
}

// ==========================================================
// 7. FUNGSI AJAX CHAT & VALIDASI
// ==========================================================
function setupChatAndValidationEvents() {
    // Validasi Dokumen
    const btnValidasiDok = document.getElementById('btn-validasi-dokumen');
    if (btnValidasiDok) {
        btnValidasiDok.addEventListener('click', function () {
            const idDokumen = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Validasi Dokumen?',
                text: "Dokumen yang divalidasi tidak dapat diedit kembali dan ruang diskusi akan ditutup. Anda yakin?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Validasi!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch('/jazirah/validasi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ id_jazirah2_hasil: parseInt(idDokumen, 10) })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            tutupModalKustom();
                            Swal.fire({
                                icon: 'success',
                                title: 'Divalidasi!',
                                text: 'Dokumen berhasil divalidasi secara permanen.',
                                timer: 2500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                            
                            btnValidasiDok.style.display = 'none';
                            updateTableBadge(idDokumen, '5');
                            
                            const btnMata = document.querySelector(`button.btn-mata-modern[data-id_isian="${idDokumen}"]`);
                            if (btnMata) btnMata.setAttribute('data-status_dokumen', '5');
                            
                            const chatInputArea = document.querySelector('.wa-input-area');
                            if (chatInputArea) chatInputArea.style.display = 'none';
                            
                            // Jika berhasil Validasi, munculkan banner kapsul secara real-time
                            const chatContainer = document.getElementById('k-chat-messages');
                            if (chatContainer && !chatContainer.innerHTML.includes('Diskusi Ditutup')) {
                                chatContainer.insertAdjacentHTML('beforeend', `
                                    <div class="locked-chat-wrapper">
                                        <div class="locked-chat-card">
                                            <div class="locked-chat-icon">
                                                <i class="fa-solid fa-lock"></i>
                                            </div>
                                            <div class="locked-chat-text">
                                                <strong>Diskusi Ditutup</strong>
                                                <span>Dokumen ini telah divalidasi</span>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            }

                            const rowTable = document.getElementById(`row-${idDokumen}`);
                            if (rowTable) {
                                const editBtn = rowTable.querySelector('.btn-edit-modern');
                                if (editBtn) editBtn.remove();
                            }
                            
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'Terjadi masalah koneksi server.', 'error');
                    });
                }
            });
        });
    }

    // Kirim Pesan Chat
    const btnKirim = document.getElementById('btn-kirim-pesan');
    const inputPesan = document.getElementById('k-chat-input');
    const chatContainer = document.getElementById('k-chat-messages');

    if (btnKirim && inputPesan) {
        const kirimPesanAction = function () {
            const pesan = inputPesan.value.trim();
            const idHasil = btnKirim.getAttribute('data-id_hasil');

            if (pesan === '') return; 
            if (!idHasil || idHasil === 'null' || idHasil === 'undefined' || idHasil === '') {
                alert('ID Dokumen tidak valid! Pastikan Anda sudah menyimpan isian dokumen ini minimal 1 kali sebelum berdiskusi.');
                return;
            }

            const originalIcon = btnKirim.innerHTML;
            btnKirim.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            btnKirim.disabled = true;
            inputPesan.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData();
            formData.append('id_jazirah2_hasil', idHasil);
            formData.append('komentar', pesan);

            fetch('/jazirah/komentar/store', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const btnMataCurrent = document.querySelector(`button.btn-mata-modern[data-id_isian="${idHasil}"]`);
                    const arrayPj = btnMataCurrent ? (btnMataCurrent.getAttribute('data-penanggungjawab') || '').split(',').map(s => s.trim()) : [];
                    const arrayCreator = btnMataCurrent ? (btnMataCurrent.getAttribute('data-created_by_3') || '').split(',').map(s => s.trim()) : [];
                    
                    const senderUsername = result.data.pegawai?.username || '';
                    const senderRoleRaw = result.data.pegawai?.jazirah || 'Guest';
                    const formatRole = (str) => str.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
                    
                    let roleLabel = '';
                    if (senderUsername && arrayCreator.includes(senderUsername)) {
                        roleLabel = 'Evaluator';
                    } else if (senderUsername && arrayPj.includes(senderUsername)) {
                        roleLabel = 'Penanggung Jawab';
                    } else {
                        roleLabel = formatRole(senderRoleRaw);
                    }
                    
                    const namaSenderTampilAjax = `Anda <span style="font-weight: normal; font-size: 0.65rem; color: #047857; opacity: 0.9; margin-left: 4px;">(${roleLabel})</span>`;

                    const d = new Date();
                    const waktu = `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;

                    const newBubble = `
                        <div class="wa-msg me" style="animation: fadeIn 0.3s ease-in-out;">
                            <div class="wa-bubble">
                                <div class="wa-sender-name text-end mb-1" style="color: #059669;">${namaSenderTampilAjax}</div>
                                <div class="wa-text">${result.data.komentar}</div>
                                <div class="wa-time">
                                    ${waktu} 
                                    &nbsp; <i class="fa-solid fa-check-double ms-1 text-info" style="font-size: 0.6rem;"></i>
                                    &nbsp; <i class="fa-solid fa-trash ms-2 text-danger btn-hapus-chat" data-id="${result.data.id}" style="font-size: 0.75rem; cursor: pointer; opacity: 0.7;" title="Hapus pesan ini"></i>
                                </div>
                            </div>
                        </div>`;   
                    chatContainer.insertAdjacentHTML('beforeend', newBubble);
                    
                    if (result.status_dokumen) {
                        renderTimelineKustom(result.status_dokumen);
                        if (btnMataCurrent) {
                            btnMataCurrent.setAttribute('data-status_dokumen', result.status_dokumen);
                        }
                        updateTableBadge(idHasil, result.status_dokumen);
                    }

                    // Update Cache Base64
                    if (btnMataCurrent) {
                        let currentKomentars = [];
                        try {
                            const rawData = btnMataCurrent.getAttribute('data-komentars');
                            if (rawData && rawData.trim() !== '' && rawData !== 'W10=') {
                                currentKomentars = JSON.parse(decodeURIComponent(escape(atob(rawData))));
                            }
                        } catch (e) {
                            try {
                                currentKomentars = JSON.parse(atob(btnMataCurrent.getAttribute('data-komentars')));
                            } catch (err) {}
                        }
                        
                        currentKomentars.push(result.data);
                        const newBase64 = btoa(unescape(encodeURIComponent(JSON.stringify(currentKomentars))));
                        btnMataCurrent.setAttribute('data-komentars', newBase64);
                    }

                    inputPesan.value = '';
                    setTimeout(() => { chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: 'smooth' }); }, 100);
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan di server.'));
                }
            })
            .catch(error => { alert('Terjadi kesalahan koneksi jaringan atau server.'); })
            .finally(() => {
                btnKirim.innerHTML = originalIcon;
                btnKirim.disabled = false;
                inputPesan.disabled = false;
                inputPesan.focus();
            });
        };

        btnKirim.addEventListener('click', kirimPesanAction);
        inputPesan.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); kirimPesanAction(); }
        });
    }

    // Hapus Pesan Chat
    if (chatContainer) {
        chatContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-hapus-chat')) {
                const chatId = e.target.getAttribute('data-id');
                const bubbleElement = e.target.closest('.wa-msg');

                Swal.fire({
                    title: 'Hapus pesan?', 
                    text: "Pesan ini akan dihapus secara permanen.", 
                    icon: 'warning',
                    showCancelButton: true, 
                    confirmButtonColor: '#d33', 
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!', 
                    cancelButtonText: 'Batal', 
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        fetch(`/jazirah/komentar/${chatId}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                        })
                        .then(response => response.json())
                        .then(res => {
                            if (res.success) {

                                // === AWAL UPDATE CACHE BASE64 PADA TOMBOL ===
                                const btnKirimPesan = document.getElementById('btn-kirim-pesan');
                                const idHasil = btnKirimPesan ? btnKirimPesan.getAttribute('data-id_hasil') : null;

                                if (idHasil) {
                                    const btnMataCurrent = document.querySelector(`button.btn-mata-modern[data-id_isian="${idHasil}"]`);
                                    if (btnMataCurrent) {
                                        let currentKomentars = [];
                                        try {
                                            const rawData = btnMataCurrent.getAttribute('data-komentars');
                                            if (rawData && rawData.trim() !== '' && rawData !== 'W10=') {
                                                currentKomentars = JSON.parse(decodeURIComponent(escape(atob(rawData))));
                                            }
                                        } catch (e) {
                                            try {
                                                currentKomentars = JSON.parse(atob(btnMataCurrent.getAttribute('data-komentars')));
                                            } catch (err) {}
                                        }
                                        
                                        // Filter hapus pesan yang id-nya sesuai dari array cache
                                        currentKomentars = currentKomentars.filter(chat => String(chat.id) !== String(chatId));
                                        
                                        // Setel kembali datanya ke tombol mata
                                        const newBase64 = btoa(unescape(encodeURIComponent(JSON.stringify(currentKomentars))));
                                        btnMataCurrent.setAttribute('data-komentars', newBase64);
                                    }
                                }
                                // === AKHIR UPDATE CACHE ===

                                bubbleElement.style.transition = "opacity 0.3s, transform 0.3s";
                                bubbleElement.style.opacity = '0';
                                bubbleElement.style.transform = 'scale(0.9)';
                                setTimeout(() => {
                                    bubbleElement.remove();
                                    if (chatContainer.children.length === 0) {
                                        chatContainer.innerHTML = `
                                            <div class="text-center text-muted shadow-sm" style="margin: auto; background: rgba(255,255,255,0.9); padding: 8px 15px; border-radius: 15px; font-size:0.8rem; width: max-content;">
                                                <i class="fa-solid fa-lock text-warning mb-1"></i><br>Pesan terenkripsi secara end-to-end.<br>Belum ada obrolan.
                                            </div>`;
                                    }
                                }, 300);
                            } else {
                                Swal.fire('Gagal!', res.message || 'Tidak dapat menghapus pesan.', 'error');
                            }
                        })
                        .catch(err => { Swal.fire('Error!', 'Terjadi kesalahan koneksi jaringan.', 'error'); });
                    }
                });
            }
        });
    }
}

// ==========================================================
// 8. FUNGSI UPDATE BADGE TABEL UTAMA REAL-TIME
// ==========================================================
function updateTableBadge(idIsian, statusDokumen, bulanTargetRaw = null) {
    const container = document.getElementById(`status-container-${idIsian}`);
    if (!container) return;

    // Ambil data bulan_target dari tombol aksi (mata) jika tidak dipassing langsung
    if (bulanTargetRaw === null) {
        const btnMata = document.querySelector(`button.btn-mata-modern[data-id_isian="${idIsian}"]`);
        bulanTargetRaw = btnMata ? btnMata.getAttribute('data-bulan-target') : '';
    }

    let badgeHtml = '';
    
    // Ambil bulan saat ini (getMonth() nilainya 0-11, jadi kita tambah 1)
    const currentMonth = new Date().getMonth() + 1; 
    const targetArr = bulanTargetRaw ? String(bulanTargetRaw).split(',').map(m => parseInt(m.trim())) : [];
    const adaTargetBerjalan = targetArr.some(m => m <= currentMonth);

    // LOGIKA PENGECEKAN
    if (String(statusDokumen) === '0' || !statusDokumen) {
        badgeHtml = '<span class="badge j-badge j-bg-gray"><i class="bi bi-dash-circle-fill me-1"></i>Perlu Menetapkan Target</span>';
    } else if (!adaTargetBerjalan) {
        badgeHtml = '<span class="badge j-badge j-bg-gray"><i class="bi bi-calendar-x me-1"></i>Belum Ada Target Jatuh Tempo</span>';
    } else {
        switch (String(statusDokumen)) {
            case '1':
                badgeHtml = '<span class="badge j-badge j-bg-orange"><i class="bi bi-bullseye me-1"></i>Target Sudah Ditetapkan</span>';
                break;
            case '2':
                badgeHtml = '<span class="badge j-badge j-bg-blue"><i class="bi bi-search me-1"></i>Perlu Evaluasi (Tim Evaluator)</span>';
                break;
            case '3':
                badgeHtml = '<span class="badge j-badge j-bg-red"><i class="bi bi-exclamation-circle-fill me-1"></i>Perlu Ditindaklanjuti</span>';
                break;
            case '4':
                badgeHtml = '<span class="badge j-badge j-bg-yellow"><i class="bi bi-search me-1"></i>Perlu Evaluasi (Tim Evaluator)</span>';
                break;
            case '5':
                badgeHtml = '<span class="badge j-badge j-bg-green"><i class="bi bi-check-circle-fill me-1"></i>Dokumen Sudah Validasi</span>';
                break;
        }
    }
    
    // Inject ke HTML
    container.innerHTML = badgeHtml;
}

// ==========================================================
// 9. FUNGSI BATAL VALIDASI
// ==========================================================
function batalValidasi(btn) {
    const idDokumen = btn.getAttribute('data-id_isian');

    Swal.fire({
        title: 'Batal Validasi?',
        text: "Status akan dikembalikan ke tahap Evaluasi dan Ruang Diskusi akan dibuka kembali.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Kembali',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/jazirah/batal-validasi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id_jazirah2_hasil: parseInt(idDokumen, 10) })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dibatalkan!',
                        text: 'Validasi berhasil dicabut.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Terjadi masalah koneksi server.', 'error');
            });
        }
    });
}