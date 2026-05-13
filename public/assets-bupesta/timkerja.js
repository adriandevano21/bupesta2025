// =========================================================================
// FILE: public/assets-bupesta/js/timkerja.js
// =========================================================================

// --- 0. HELPER DOM SELECTOR (Lebih ringkas & hemat ketikan) ---
const $ = (id) => document.getElementById(id);
const $$ = (selector) => document.querySelectorAll(selector);

// --- 1. KENDALI OPEN/CLOSE SEMUA MODAL ---
const toggleModal = (id, show) => $(id).style.display = show ? 'flex' : 'none';

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

// --- 5. AJAX: FETCH PROFIL PEGAWAI (Diubah jadi Async/Await) ---
async function lihatProfilPegawai(elemen) {
    const nip = elemen.getAttribute('data-nip');
    if (!nip) return;

    try {
        const res = await fetch(`/pegawai/get-profil/${nip}`);
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

            // Logika Tombol Edit PJK
            $$('.pjk-butuh-cek-js').forEach(tombol => {
                tombol.style.display = (userNip && (userNip === nipKetua || userNip === nipPjk1100)) ? 'block' : 'none';
            });

            $('aktif_kode_kegiatan').value = kodeKegiatan;
            toggleModal('modalKegiatanBesar', true);

            $('tab-info').classList.add('show');
            $('tab-pjk').classList.remove('show');

            const tabs = $$('.tab-link');
            tabs[0]?.classList.add('active');
            tabs[1]?.classList.remove('active');

            try {
                const res = await fetch(`/kegiatan/get-data/${kodeKegiatan}`);
                const data = await res.json();

                if (data && !data.error) {
                    ['nama_kegiatan', 'tahun_kegiatan', 'detail_anggota_tim', 'detail_informasi_penting', 'detail_jadwal'].forEach(f => {
                        $(`teks_${f}`).textContent = data[f] || '-';
                    });

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
                console.error('Gagal fetch data kegiatan:', err);
            }
        });
    });
});

// --- 7. INLINE EDIT PJK (Diubah jadi Async/Await) ---
function toggleEditPjk(kodeSatker) {
    const tampilanMode = $('tampilan_pjk_' + kodeSatker);
    const editMode = $('area_edit_pjk_' + kodeSatker);
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
            btnProfil.style.color = nipBaru ? "#f79039" : "#9ca3af";

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
