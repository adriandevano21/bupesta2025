// =========================================================================
// FILE: public/assets-bupesta/js/timkerja.js
// =========================================================================

// --- 0. HELPER DOM SELECTOR (Lebih ringkas & hemat ketikan) ---
const $ = (id) => document.getElementById(id);
const $$ = (selector) => document.querySelectorAll(selector);

// --- 1. KENDALI OPEN/CLOSE SEMUA MODAL ---
const toggleModal = (id, show) => {
    const el = $(id);
    if (el) el.style.display = show ? 'flex' : 'none';
};

function bukaModalTambah() { toggleModal('modalTambahTim', true); }
function tutupModalTambah() { toggleModal('modalTambahTim', false); }
function tutupModalBesar() { toggleModal('modalKegiatanBesar', false); }
function tutupModalProfilPegawai() { toggleModal('modalProfilPegawai', false); }
function tutupModalEdit() { toggleModal('modalEditTimGlobal', false); }

// Instansiasi Toast SweetAlert
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// --- 2. LOGIKA MODAL EDIT GLOBAL (DOM JSON) ---
function bukaModalEdit(id) {
    const dataElement = $('data-tim-' + id);
    if (!dataElement) return;

    const data = JSON.parse(dataElement.textContent);
    $('formEditTim').action = `/timkerja/update/${data.kode_tim_kerja}`;
    $('edit_nama_tim_kerja').value = data.nama_tim_kerja;
    $('edit_nama_ketua').value = data.nip_ketua_tim;

    const wadah = $('wadah-kegiatan-edit');
    wadah.innerHTML = '';

    data.kegiatan?.forEach(keg => {
        const selectHtml = window.BupestaConfig.opsiPegawaiHtml.replace(
            `value="${keg.pjk_1100}"`,
            `value="${keg.pjk_1100}" selected`
        );

        wadah.insertAdjacentHTML('beforeend', `
            <div class="baris-kegiatan">
                <input type="hidden" name="id_kegiatan[]" value="${keg.kode_kegiatan}">
                <input type="text" name="nama_kegiatan[]" value="${keg.nama_kegiatan}" class="input-form">
                <select name="nip_pegawai[]" class="input-form select-pegawai">${selectHtml}</select>
                <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)"><i class="fa-solid fa-trash"></i></button>
            </div>
        `);
    });
    toggleModal('modalEditTimGlobal', true);
}

// --- 3. TAMBAH/HAPUS BARIS KEGIATAN DINAMIS ---
function tambahBarisKegiatan(id) {
    $('wadah-kegiatan-' + id).insertAdjacentHTML('beforeend', `
        <div class="baris-kegiatan">
            <input type="text" name="nama_kegiatan_baru[]" placeholder="Ketik kegiatan baru..." class="input-form">
            <select name="nip_pegawai_baru[]" class="input-form select-pegawai">
                ${window.BupestaConfig.opsiPegawaiHtml}
            </select>
            <button type="button" class="btn-hapus-baris" onclick="hapusBaris(this)"><i class="fa-solid fa-trash"></i></button>
        </div>
    `);
}

function hapusBaris(elemenTombol) {
    elemenTombol.parentElement.remove();
}

// --- 4. PINDAH TAB (MODAL BESAR) ---
function openTab(evt, tabName) {
    $$('.tab-content').forEach(tab => tab.classList.remove('show'));
    $$('.tab-link').forEach(link => link.classList.remove('active'));
    $(tabName).classList.add('show');
    evt.currentTarget.classList.add('active');
}

// --- 5. AJAX: FETCH PROFIL PEGAWAI ---
async function lihatProfilPegawai(elemen) {
    const nip = elemen.getAttribute('data-nip');
    if (!nip) return;

    try {
        const res = await fetch(`/profil-pegawai/${nip}`);
        const data = await res.json();

        if (data && !data.error) {
            const { name, nip_pegawai, jabatan, golongan, kode_satker, no_hp } = data; // Destructuring

            $('profil_nama').textContent = name || '-';
            $('profil_nip').textContent = nip_pegawai || '-';
            $('profil_jabatan').textContent = jabatan || '-';
            $('profil_golongan').textContent = golongan || '-';
            $('profil_satker').textContent = kode_satker || '-';

            const linkWa = $('profil_wa_link');
            const teksWa = $('profil_no_hp');

            if (no_hp) {
                const cleanHp = no_hp.replace(/\D/g, '').replace(/^0/, '62');
                teksWa.textContent = no_hp;
                linkWa.href = `https://wa.me/${cleanHp}`;
                Object.assign(linkWa.style, { display: 'inline-block', background: '#25D366', pointerEvents: 'auto' });
            } else {
                teksWa.textContent = 'Tidak ada No HP';
                linkWa.href = 'javascript:void(0)';
                Object.assign(linkWa.style, { background: '#a0aec0', pointerEvents: 'none' });
            }

            toggleModal('modalProfilPegawai', true);
        } else {
            alert('Data pegawai tidak ditemukan.');
        }
    } catch (err) {
        console.error('Error fetching profil:', err);
    }
}

// --- 6. DOM CONTENT LOADED (EVENT LISTENERS) ---
document.addEventListener('DOMContentLoaded', () => {

    // A. Typewriter Effect
    const element = $("typewriter");
    if (element) {
        const fullText = `Halo, ${window.BupestaConfig.userName}! Selamat Datang..`;
        const chars = Array.from(fullText);
        let i = 0, isDeleting = false;

        const type = () => {
            if (!isDeleting) {
                element.textContent = chars.slice(0, ++i).join('');
                if (i === chars.length) { isDeleting = true; return setTimeout(type, 2000); }
            } else {
                element.textContent = chars.slice(0, --i).join('');
                if (i <= 0) isDeleting = false;
            }
            setTimeout(type, isDeleting ? 50 : 100);
        };
        type();
    }

    // B. SweetAlert Notifikasi Sukses
    if (window.BupestaConfig.successMessage) {
        setTimeout(() => Toast.fire({ title: window.BupestaConfig.successMessage }), 1000);
    }

    // C. Klik Item Kegiatan -> Fetch Data
    $$('.item-kegiatan').forEach(kapsul => {
        kapsul.addEventListener('click', async function() {
            const kodeKegiatan = this.getAttribute('data-id');
            const nipKetua = this.getAttribute('data-nip-ketua');
            const nipPjk1100 = this.getAttribute('data-pjk-1100');
            const userNip = window.userActiveNip;

            // Logika Hak Akses Tombol Edit PJK & Edit Info Umum
            $$('.pjk-butuh-cek-js').forEach(tombol => {
                tombol.style.display = (userNip && (userNip === nipKetua || userNip === nipPjk1100)) ? 'inline-block' : 'none';
            });

            $('aktif_kode_kegiatan').value = kodeKegiatan;
            toggleModal('modalKegiatanBesar', true);

            // Reset tab ke posisi awal (Tab Info yang aktif)
            $('tab-info').classList.add('show');
            $('tab-pjk').classList.remove('show');
            const tabEdit = $('tab-edit-info');
            if (tabEdit) tabEdit.classList.remove('show');

            const tabs = $$('.tab-link');
            if(tabs[0]) tabs[0].classList.add('active');
            if(tabs[1]) tabs[1].classList.remove('active');
            if(tabs[2]) tabs[2].classList.remove('active');

            // Set placeholder sembari loading
            if($('judul_dinamis_kegiatan')) $('judul_dinamis_kegiatan').textContent = "Memuat...";

            try {
                const res = await fetch(`/kegiatan/get-data/${kodeKegiatan}`);
                const data = await res.json();

                if (data && !data.error) {

                    // --- 1. Isi Judul & Tab Teks Informasi Umum ---
                    if ($('judul_dinamis_kegiatan')) $('judul_dinamis_kegiatan').textContent = data.nama_kegiatan || '-';

                    ['nama_kegiatan', 'tahun_kegiatan', 'detail_informasi_penting', 'detail_jadwal'].forEach(f => {
                        if ($(`teks_${f}`)) $(`teks_${f}`).textContent = data[f] || '-';
                    });

                    // Render Nama Clickable untuk Informasi Umum
                    const wadahInfoAnggota = $('teks_detail_anggota_tim');
                    if (wadahInfoAnggota) {
                        wadahInfoAnggota.innerHTML = '';
                        if (data.detail_anggota_tim) {
                            const nips = data.detail_anggota_tim.split(',');
                            nips.forEach(nip => {
                                const cleanNip = nip.trim(); // Bersihkan spasi
                                const opt = document.querySelector(`#select_anggota_tim_multiple option[value="${cleanNip}"]`);
                                const nama = opt ? opt.text : (data.nama_pegawai?.[cleanNip] || cleanNip);

                                wadahInfoAnggota.innerHTML += `
                                    <span class="badge-anggota" onclick="lihatProfilPegawai(this)" data-nip="${cleanNip}" title="Lihat Profil">
                                        <i class="fas fa-user-circle"></i> ${nama}
                                    </span>
                                `;
                            });
                        } else {
                            wadahInfoAnggota.textContent = '-';
                        }
                    }

                    // --- 2. Isi Form Tab Edit Informasi Umum ---
                    if ($('form_edit_kode_kegiatan')) $('form_edit_kode_kegiatan').value = data.kode_kegiatan || kodeKegiatan;
                    if ($('input_nama_kegiatan')) $('input_nama_kegiatan').value = data.nama_kegiatan || '';
                    if ($('input_tahun_kegiatan')) $('input_tahun_kegiatan').value = data.tahun_kegiatan || '';
                    if ($('input_detail_informasi_penting')) $('input_detail_informasi_penting').value = data.detail_informasi_penting || '';
                    if ($('input_detail_jadwal')) $('input_detail_jadwal').value = data.detail_jadwal || '';

                    // Logika khusus Select Anggota (Render Pills di Edit Form)
                    const selectAnggota = $('select_anggota_tim_multiple');
                    if (selectAnggota) {
                        selectAnggota.multiple = true;
                        const stringAnggota = String(data.detail_anggota_tim || '');
                        const nipArray = stringAnggota ? stringAnggota.split(',').map(s => s.trim()) : [];

                        Array.from(selectAnggota.options).forEach(opt => {
                            opt.selected = nipArray.includes(opt.value);
                        });
                        renderBadgesAnggota();
                    }

                    // --- 3. Isi Tampilan Tombol/Profil PJK Kab/Kota ---
                    const arrayPjk = ['1100', '1101', '1102', '1103', '1104', '1105', '1106', '1107', '1108', '1109', '1110', '1111', '1112', '1113', '1114', '1115', '1116', '1117', '1118', '1171', '1172', '1173', '1174', '1175'];

                    arrayPjk.forEach(kode => {
                        let tombolPjk = $(`btn_pjk_${kode}`);
                        if (tombolPjk) {
                            let nip = data[`pjk_${kode}`];
                            let spanTeks = tombolPjk.querySelector('.teks-nama-pjk');

                            if (nip) {
                                spanTeks.textContent = data.nama_pegawai?.[nip] || nip;
                                tombolPjk.setAttribute('data-nip', nip);
                                tombolPjk.classList.add('ada-data');
                                tombolPjk.disabled = false;
                            } else {
                                spanTeks.textContent = 'Belum ada PJK';
                                tombolPjk.setAttribute('data-nip', '');
                                tombolPjk.classList.remove('ada-data');
                                tombolPjk.disabled = true;
                            }
                        }
                    });
                }
            } catch (err) {
                if ($('judul_dinamis_kegiatan')) $('judul_dinamis_kegiatan').textContent = "Gagal memuat data";
                console.error('Gagal fetch data kegiatan:', err);
            }
        });
    });
});

// --- 7. INLINE EDIT PJK ---
function toggleEditPjk(kodeSatker) {
    const tampilanMode = $('tampilan_pjk_' + kodeSatker);
    const editMode = $('area_edit_pjk_' + kodeSatker);

    if(!tampilanMode || !editMode) return;

    const isHidden = tampilanMode.style.display === 'none';
    tampilanMode.style.display = isHidden ? 'block' : 'none';
    editMode.style.display = isHidden ? 'none' : 'flex';
}

async function simpanPjk(kodeSatker) {
    const elemenSelect = $('select_pjk_' + kodeSatker);
    const nipBaru = elemenSelect.value;
    const namaBaru = nipBaru ? elemenSelect.options[elemenSelect.selectedIndex].text : 'Belum ada PJK';
    const kodeKegiatan = $('aktif_kode_kegiatan').value;

    const btnSimpan = document.querySelector(`#area_edit_pjk_${kodeSatker} .btn-simpan-mini`);
    const originalIcon = btnSimpan.innerHTML;

    btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btnSimpan.disabled = true;

    try {
        const res = await fetch(`/kegiatan/update-pjk`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                kode_kegiatan: kodeKegiatan,
                kolom_pjk: 'pjk_' + kodeSatker,
                nip_pegawai: nipBaru
            })
        });

        const data = await res.json();

        if (data.success) {
            const btnProfil = $('btn_pjk_' + kodeSatker);
            btnProfil.setAttribute('data-nip', nipBaru);
            btnProfil.querySelector('.teks-nama-pjk').textContent = namaBaru;

            toggleEditPjk(kodeSatker);
            Toast.fire({ icon: 'success', title: 'Berhasil diperbarui' });
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    } catch (err) {
        console.error('Error:', err);
        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
    } finally {
        btnSimpan.innerHTML = originalIcon;
        btnSimpan.disabled = false;
    }
}

// --- 8. LOGIKA MULTI-SELECT ANGGOTA TIM ---

function renderBadgesAnggota() {
    const wadah = document.getElementById('wadah_badges_anggota');
    const hiddenSelect = document.getElementById('select_anggota_tim_multiple'); // [UBAH DI SINI]

    if (!wadah || !hiddenSelect) return;

    wadah.innerHTML = '';
    let adaYangDipilih = false;

    Array.from(hiddenSelect.options).forEach(opt => {
        if (opt.selected) {
            adaYangDipilih = true;
            const badge = document.createElement('span');
            badge.className = 'badge-anggota badge-anggota-hapus';
            badge.title = 'Klik untuk menghapus';
            badge.onclick = function() { hapusAnggotaTim(opt.value); };
            badge.innerHTML = `<i class="fas fa-times-circle"></i> ${opt.text}`;
            wadah.appendChild(badge);
        }
    });

    if (!adaYangDipilih) {
        wadah.innerHTML = '<span style="color:#9ca3af; font-size:12px; font-style:italic;">Belum ada anggota dipilih.</span>';
    }
}

function tambahAnggotaTim(nip) {
    if (!nip) return;
    const hiddenSelect = document.getElementById('select_anggota_tim_multiple'); // [UBAH DI SINI]
    const dummySelect = document.getElementById('pilih_anggota_dummy');

    Array.from(hiddenSelect.options).forEach(opt => {
        if (opt.value === nip) opt.selected = true;
    });

    renderBadgesAnggota();
    dummySelect.value = '';
}

function hapusAnggotaTim(nip) {
    const hiddenSelect = document.getElementById('select_anggota_tim_multiple'); // [UBAH DI SINI]

    Array.from(hiddenSelect.options).forEach(opt => {
        if (opt.value === nip) opt.selected = false;
    });

    renderBadgesAnggota();
}

// Fungsi memproses data saat header tim diklik
function klikHeaderTim(kodeTim) {
    const jsonElement = document.getElementById('data-tim-' + kodeTim);
    if (!jsonElement) {
        console.error("Data tim tidak ditemukan!");
        return;
    }

    let dataTimRaw = document.getElementById('data-tim-' + kodeTim).textContent;
    let dataTim = JSON.parse(dataTimRaw);
    
    // 2. Tampilkan Modal
    document.getElementById('modalTimKerjaBesar').style.display = 'flex';

    // Aktifkan tab pertama secara default
    openTabTim({ currentTarget: document.querySelector('.tab-link-tim') }, 'tab-info-tim');

    // ==========================================
    // 3. SET DATA UNTUK TAB INFO & EDIT
    // ==========================================
    document.getElementById('judul_dinamis_tim').textContent = dataTim.nama_tim_kerja;
    document.getElementById('teks_nama_tim_kerja').textContent = dataTim.nama_tim_kerja;

    // Set form edit
    document.getElementById('form_edit_kode_tim').value = dataTim.kode_tim_kerja;
    document.getElementById('input_edit_nama_tim').value = dataTim.nama_tim_kerja;

    // Cari & Tampilkan Nama Ketua Provinsi (1100)
    const selectProv = document.getElementById('input_edit_ketua_prov');
    selectProv.selectedIndex = 0; // RESET WAJIB DULU

    if (dataTim.nip_ketua_tim && dataTim.nip_ketua_tim !== 'null') {
        selectProv.value = dataTim.nip_ketua_tim;
    }
    const namaKetuaProv = selectProv.selectedIndex > 0 ? selectProv.options[selectProv.selectedIndex].text : 'Belum ada ketua';
    document.getElementById('teks_ketua_tim_prov').textContent = namaKetuaProv;

    // ==========================================
    // 4. SET DATA 23 KETUA TIM KAB/KOTA (1101-1175)
    // ==========================================
    document.getElementById('aktif_kode_tim').value = dataTim.kode_tim_kerja;

    // Daftar Satker 23 Kab/Kota
    const satkerCodes = [
        '1101','1102','1103','1104','1105','1106','1107','1108','1109','1110',
        '1111','1112','1113','1114','1115','1116','1117','1118',
        '1171','1172','1173','1174','1175'
    ];

    satkerCodes.forEach(satkerCode => {
        let nipKetuaKab = dataTim['ketuatim_' + satkerCode];

        const selectEl = document.getElementById('select_ketuatim_' + satkerCode);
        const textNamaEl = document.querySelector('#btn_ketuatim_' + satkerCode + ' .teks-nama-ketua');
        const btnProfil = document.getElementById('btn_ketuatim_' + satkerCode);

        if (selectEl && textNamaEl) {
            // A. SAPU BERSIH TOTAL (Paksa kosongkan value dropdown)
            selectEl.value = "";
            selectEl.selectedIndex = 0;

            // B. Masukkan nilai baru HANYA JIKA VALID & ADA DI DATABASE
            if (nipKetuaKab !== null && nipKetuaKab !== 'null' && nipKetuaKab !== undefined && String(nipKetuaKab).trim() !== '') {
                selectEl.value = nipKetuaKab;
            }

            // C. Evaluasi Ulang Tampilan (Cek apakah dropdown berhasil terisi NIP)
            if (selectEl.value !== "") {
                // Tampilkan Nama jika pegawai ditemukan di dalam dropdown
                textNamaEl.textContent = selectEl.options[selectEl.selectedIndex].text;
                btnProfil.setAttribute('data-nip', selectEl.value);
                btnProfil.classList.add('ada-data'); // Memberi warna oranye (jika css digunakan)
            } else {
                // Kembalikan ke text default jika kosong
                selectEl.value = ""; // Kunci lagi agar tidak nyangkut
                textNamaEl.textContent = 'Belum ada Ketua';
                btnProfil.removeAttribute('data-nip');
                btnProfil.classList.remove('ada-data');
            }

            // D. Pastikan Form tertutup dan Label View terbuka
            document.getElementById('area_edit_ketuatim_' + satkerCode).style.display = 'none';
            document.getElementById('tampilan_ketuatim_' + satkerCode).style.display = 'flex';
        }
    });
}

// ==========================================
// FUNGSI PENDUKUNG
// ==========================================

// Fungsi Tab Khusus Modal Tim
function openTabTim(evt, tabName) {
    const tabContents = document.getElementsByClassName("tab-content-tim");
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].style.display = "none";
    }

    const tabLinks = document.getElementsByClassName("tab-link-tim");
    for (let i = 0; i < tabLinks.length; i++) {
        tabLinks[i].classList.remove("active");
    }

    document.getElementById(tabName).style.display = "block";
    if (evt) evt.currentTarget.classList.add("active");
}

// Toggling mode Edit Inline Ketua Tim
function toggleEditKetuaTim(kodeSatker) {
    const areaTampil = document.getElementById('tampilan_ketuatim_' + kodeSatker);
    const areaEdit = document.getElementById('area_edit_ketuatim_' + kodeSatker);

    if (areaEdit.style.display === 'none') {
        areaTampil.style.display = 'none';
        areaEdit.style.display = 'flex'; // Atau block menyesuaikan css Anda
    } else {
        areaTampil.style.display = 'block';
        areaEdit.style.display = 'none';
    }
}

// ==========================================
// FUNGSI MEMPROSES DATA SAAT HEADER TIM DIKLIK
// ==========================================
function klikHeaderTim(kodeTim) {
    const jsonElement = document.getElementById('data-tim-' + kodeTim);
    if (!jsonElement) {
        console.error("Data tim tidak ditemukan!");
        return;
    }

    // 1. Parsing JSON Data
    const dataTim = JSON.parse(jsonElement.textContent);

    // 2. CEK OTORISASI DINAMIS: Apakah user yang login adalah Ketua Tim ini?
    let isKetuaTimProv = (window.userActiveNip === dataTim.nip_ketua_tim);

    // 3. Tampilkan paksa tombol edit daerah jika dia adalah Ketua Tim
    document.querySelectorAll('.tim-butuh-cek-js').forEach(tombolEdit => {
        if (isKetuaTimProv) {
            tombolEdit.style.display = 'block';
        } else {
            // Sembunyikan kembali (mengandalkan style inline asli dari PHP)
            tombolEdit.style.display = 'none';
        }
    });

    // 4. Tampilkan Modal
    document.getElementById('modalTimKerjaBesar').style.display = 'flex';

    // Aktifkan tab pertama secara default
    openTabTim({ currentTarget: document.querySelector('.tab-link-tim') }, 'tab-info-tim');

    // ==========================================
    // 3. SET DATA UNTUK TAB INFO & EDIT UMUM
    // ==========================================
    document.getElementById('judul_dinamis_tim').textContent = dataTim.nama_tim_kerja;
    document.getElementById('teks_nama_tim_kerja').textContent = dataTim.nama_tim_kerja;

    // Set form edit
    const formKodeTim = document.getElementById('form_edit_kode_tim');
    const inputNamaTim = document.getElementById('input_edit_nama_tim');
    if(formKodeTim) formKodeTim.value = dataTim.kode_tim_kerja;
    if(inputNamaTim) inputNamaTim.value = dataTim.nama_tim_kerja;

    // Cari & Tampilkan Nama Ketua Provinsi (1100)
    const selectProv = document.getElementById('input_edit_ketua_prov');
    if(selectProv) {
        selectProv.selectedIndex = 0; // RESET WAJIB DULU

        if (dataTim.nip_ketua_tim && dataTim.nip_ketua_tim !== 'null') {
            selectProv.value = dataTim.nip_ketua_tim;
        }
        const namaKetuaProv = selectProv.selectedIndex > 0 ? selectProv.options[selectProv.selectedIndex].text : 'Belum ada ketua';

        const teksKetuaProv = document.getElementById('teks_ketua_tim_prov');
        if(teksKetuaProv) teksKetuaProv.textContent = namaKetuaProv;
    }

    // ==========================================
    // 4. SET DATA 23 KETUA TIM KAB/KOTA (1101-1175)
    // ==========================================
    const aktifKodeTim = document.getElementById('aktif_kode_tim');
    if(aktifKodeTim) aktifKodeTim.value = dataTim.kode_tim_kerja;

    // Daftar Satker 23 Kab/Kota
    const satkerCodes = [
        '1101','1102','1103','1104','1105','1106','1107','1108','1109','1110',
        '1111','1112','1113','1114','1115','1116','1117','1118',
        '1171','1172','1173','1174','1175'
    ];

    satkerCodes.forEach(satkerCode => {
        let nipKetuaKab = dataTim['ketuatim_' + satkerCode];

        const selectEl = document.getElementById('select_ketuatim_' + satkerCode);
        const textNamaEl = document.querySelector('#btn_ketuatim_' + satkerCode + ' .teks-nama-ketua');
        const btnProfil = document.getElementById('btn_ketuatim_' + satkerCode);

        // Hanya proses jika elemen select dan tombol tersebut ADA di HTML
        if (selectEl && textNamaEl && btnProfil) {

            // A. SAPU BERSIH TOTAL (Paksa kosongkan value dropdown)
            selectEl.value = "";
            selectEl.selectedIndex = 0;

            // B. Masukkan nilai baru HANYA JIKA VALID & ADA DI DATABASE
            if (nipKetuaKab !== null && nipKetuaKab !== 'null' && nipKetuaKab !== undefined && String(nipKetuaKab).trim() !== '') {
                selectEl.value = nipKetuaKab;
            }

            // C. Evaluasi Ulang Tampilan (Cek apakah dropdown berhasil terisi NIP)
            if (selectEl.value !== "") {
                // Tampilkan Nama jika pegawai ditemukan di dalam dropdown
                textNamaEl.textContent = selectEl.options[selectEl.selectedIndex].text;
                btnProfil.setAttribute('data-nip', selectEl.value);
                btnProfil.classList.add('ada-data'); // Memberi warna oranye
            } else {
                // Kembalikan ke text default jika kosong
                selectEl.value = ""; // Kunci lagi agar tidak nyangkut
                textNamaEl.textContent = 'Belum ada Ketua';
                btnProfil.removeAttribute('data-nip');
                btnProfil.classList.remove('ada-data');
            }

            // D. Pastikan Form tertutup dan Label View terbuka
            const areaEdit = document.getElementById('area_edit_ketuatim_' + satkerCode);
            const areaTampil = document.getElementById('tampilan_ketuatim_' + satkerCode);

            if(areaEdit) areaEdit.style.display = 'none';
            if(areaTampil) areaTampil.style.display = 'flex';
        }
    });
}


// ==========================================
// FUNGSI SIMPAN KETUA TIM KAB/KOTA VIA AJAX
// ==========================================
async function simpanKetuaTim(kodeSatker) {

    // 1. Validasi Keberadaan Elemen Input Terlebih Dahulu
    // [PERHATIKAN ID INI: form_edit_kode_tim. Apakah ID ini ada di Blade Anda?]
    const formKodeTim = document.getElementById('form_edit_kode_tim');
    const selectEl = document.getElementById('select_ketuatim_' + kodeSatker);
    const btnSimpan = document.querySelector(`#area_edit_ketuatim_${kodeSatker} .btn-simpan-mini`);

    // --- LOGIKA PENCARI JEJAK ERROR ---
    if (!formKodeTim || !selectEl || !btnSimpan) {
        let elemenHilang = [];
        if (!formKodeTim) elemenHilang.push("Input ID Tim (form_edit_kode_tim)");
        if (!selectEl) elemenHilang.push("Dropdown Pegawai (select_ketuatim_" + kodeSatker + ")");
        if (!btnSimpan) elemenHilang.push("Tombol Simpan (#area_edit_ketuatim_" + kodeSatker + " .btn-simpan-mini)");

        alert("Gagal menyimpan! Elemen HTML ini tidak ditemukan:\n- " + elemenHilang.join("\n- "));
        return;
    }
    // ----------------------------------

    const kodeTim = formKodeTim.value;

    if (!kodeTim || kodeTim.trim() === '') {
        alert("Gagal membaca ID Tim Kerja! Silakan tutup modal dan klik ulang tim kerja.");
        return;
    }

    const nipBaru = selectEl.value;
    const namaBaru = selectEl.selectedIndex > 0 ? selectEl.options[selectEl.selectedIndex].text : '';
    const originalIcon = btnSimpan.innerHTML;

    // Ubah tombol jadi loading
    btnSimpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btnSimpan.disabled = true;

    try {
        // Cek Keberadaan Token CSRF
        const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenEl) {
            throw new Error("Meta tag CSRF-Token tidak ditemukan di Layout HTML Anda!");
        }

        // Kirim data via fetch
        const response = await fetch('/timkerja/update-ketua-kabkota', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfTokenEl.getAttribute('content')
            },
            body: JSON.stringify({
                kode_tim_kerja: kodeTim,
                kode_satker: kodeSatker,
                nip_pegawai: nipBaru
            })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("🚨 DETAIL ERROR DARI LARAVEL:", errorText);
            alert(`Penyimpanan Gagal (Error ${response.status}). Tekan tombol F12 untuk melihat detail!`);

            btnSimpan.innerHTML = originalIcon;
            btnSimpan.disabled = false;
            return;
        }

        // Baca Response JSON
        const data = await response.json();

        if (data.success) {
            // Update Tampilan Label/Badge di layar
            const textNamaEl = document.querySelector(`#btn_ketuatim_${kodeSatker} .teks-nama-ketua`);
            const btnProfil = document.getElementById(`btn_ketuatim_${kodeSatker}`);

            if(textNamaEl && btnProfil) {
                if (nipBaru !== "") {
                    textNamaEl.textContent = namaBaru;
                    btnProfil.setAttribute('data-nip', nipBaru);
                    btnProfil.classList.add('ada-data');
                } else {
                    textNamaEl.textContent = 'Belum ada Ketua';
                    btnProfil.removeAttribute('data-nip');
                    btnProfil.classList.remove('ada-data');
                }
            }

            // Update data JSON di HTML agar tidak nyangkut saat dibuka lagi
            const jsonElement = document.getElementById('data-tim-' + kodeTim);
            if (jsonElement) {
                let dataTimObj = JSON.parse(jsonElement.textContent);
                dataTimObj['ketuatim_' + kodeSatker] = nipBaru;
                jsonElement.textContent = JSON.stringify(dataTimObj);
            }

            // Tutup form edit kembali ke mode view
            toggleEditKetuaTim(kodeSatker);

            if (typeof Toast !== 'undefined') {
                Toast.fire({ icon: 'success', title: data.message });
            }

        } else {
            alert('Gagal: ' + data.message);
        }

    } catch (error) {
        console.error("Sistem JS Crash:", error);
        alert('Terjadi kesalahan JavaScript: ' + error.message);
    } finally {
        // Kembalikan tombol ke keadaan semula
        btnSimpan.innerHTML = originalIcon;
        btnSimpan.disabled = false;
    }
}
