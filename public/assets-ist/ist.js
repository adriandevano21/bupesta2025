document.addEventListener("DOMContentLoaded", function () {

    // ====================================================================
    // 1. FUNGSI MODAL PENGATURAN PERIODE (KHUSUS ADMIN/PANITIA)
    // ====================================================================
    window.bukaModalPeriode = function () {
        const modal = document.getElementById('modalPeriode');
        if (modal) modal.style.display = 'flex';
    };

    window.tutupModalPeriode = function () {
        const modal = document.getElementById('modalPeriode');
        if (modal) modal.style.display = 'none';
    };


    // ====================================================================
    // 2. FUNGSI MODAL MANAJEMEN PERTANYAAN (KHUSUS ADMIN/PANITIA)
    // ====================================================================
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


    // ====================================================================
    // 3. LOGIKA TAHAP 1.1: PENILAIAN LIVE SCORE & TABEL DINAMIS
    // ====================================================================

    // Ambil data nilai sebelumnya (Memori Edit Nilai) dari jembatan PHP Blade
    let penilaianData = window.istInitialPenilaian || {};
    let currentAssessedNip = null;

    // Saat halaman pertama dimuat, rapikan/sortir tabel (jika sedang di tahap form)
    setTimeout(() => {
        if (document.getElementById('tabel-kandidat')) updateTableUI();
    }, 300);

    // Fungsi memunculkan kembali form jika user klik "Ubah Pilihan"
    window.editPenilaianUlang = function () {
        document.getElementById('wadah-hasil').style.display = 'none';
        document.getElementById('wadah-form').style.display = 'block';
        updateTableUI();
    };

    // Fungsi Buka Modal Kuesioner untuk kandidat tertentu
    window.bukaModalPenilaian = function (nip, nama) {
        // Cek Kuota: Jika belum dinilai tapi sudah ada 3 orang di memori
        if (!penilaianData[nip] && Object.keys(penilaianData).length >= 3) {
            Swal.fire('Batas Maksimal!', 'Anda sudah menilai 3 kandidat. Silakan Hapus/Edit nilai kandidat lain untuk menggantinya.', 'warning');
            return;
        }

        currentAssessedNip = nip;
        document.getElementById('modal-nama-kandidat').innerText = nama;
        document.getElementById('live-score').innerText = penilaianData[nip] ? penilaianData[nip].total : 0;

        // Reset semua radio button terlebih dahulu
        document.querySelectorAll('.radio-nilai').forEach(el => el.checked = false);

        // Jika kandidat ini sudah pernah dinilai, isi kembali (Auto-checked)
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

    // Event Listener untuk kalkulasi Live Score setiap klik Radio Button
    document.querySelectorAll('.radio-nilai').forEach(el => {
        el.addEventListener('change', function () {
            let total = 0;
            document.querySelectorAll('.radio-nilai:checked').forEach(r => {
                total += parseInt(r.value);
            });
            document.getElementById('live-score').innerText = total;

            // Efek detak/zoom kecil saat skor berubah
            let scoreBox = document.getElementById('live-score');
            scoreBox.style.transform = 'scale(1.2)';
            setTimeout(() => scoreBox.style.transform = 'scale(1)', 200);
        });
    });

    // Simpan nilai Kuesioner ke dalam memori JavaScript
    window.simpanNilaiModal = function () {
        let answers = {};
        let total = 0;
        let totalQuestions = document.querySelectorAll('.ist-question-card').length;
        let answeredCount = document.querySelectorAll('.radio-nilai:checked').length;

        // Validasi Wajib Isi Semua
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

        // Tulis ke Object memory
        penilaianData[currentAssessedNip] = { total: total, answers: answers };

        tutupModalPenilaian();
        updateTableUI(); // Render ulang tabel
    };

    // Fungsi Hapus Penilaian dari memori
    window.hapusNilai = function (nip, event) {
        event.stopPropagation(); // Cegah event klik menjalar
        delete penilaianData[nip];
        updateTableUI();
    };

    // Fungsi Super untuk merender status tombol dan menyortir otomatis ke atas tabel
    window.updateTableUI = function () {
        let table = document.getElementById('tbody-kandidat');
        if (!table) return;

        let rows = Array.from(table.querySelectorAll('tr'));

        // Ubah warna baris dan tombol aksi berdasarkan status (dinilai/belum)
        rows.forEach(row => {
            let nip = row.getAttribute('data-nip');
            let btnArea = row.querySelector('.aksi-area');
            let scoreArea = row.querySelector('.score-area');

            if (penilaianData[nip]) {
                row.setAttribute('data-assessed', '1');
                row.style.background = '#f0fdf4'; // Hijau sangat muda
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
                scoreArea.innerHTML = `-`;
                btnArea.innerHTML = `<button type="button" class="btn-menilai" onclick="bukaModalPenilaian('${nip}', '${row.getAttribute('data-nama')}')"><i class="fa-solid fa-star"></i> Menilai</button>`;
            }
        });

        // Lakukan Sorting: Kandidat yang bernilai 1 (sudah di-assess) naik ke paling atas
        rows.sort((a, b) => {
            let aAssessed = parseInt(a.getAttribute('data-assessed'));
            let bAssessed = parseInt(b.getAttribute('data-assessed'));
            return bAssessed - aAssessed;
        });

        rows.forEach(row => table.appendChild(row));

        // Tampilkan kotak Panel Finalisasi jika pas 3 orang
        const panelFinal = document.getElementById('panel-finalisasi');
        if (Object.keys(penilaianData).length === 3) {
            panelFinal.style.display = 'block';
            panelFinal.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Fokus ke panel
        } else {
            panelFinal.style.display = 'none';
        }
    };

    // Eksekusi Submit Form setelah Finalisasi
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

                // Ambil token CSRF dan meta data hidden (NIP & Periode ID) dari form asli
                let csrfToken = document.querySelector('input[name="_token"]').value;
                let periodeId = document.querySelector('input[name="periode_id"]')?.value || '';
                let nipPemilih = document.querySelector('input[name="nip_pemilih"]')?.value || '';

                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="periode_id" value="${periodeId}">
                    <input type="hidden" name="nip_pemilih" value="${nipPemilih}">
                `;

                // Susun payload jawaban array 2 dimensi (penilaian[nip][id_soal] = jawaban)
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


    // ====================================================================
    // 4. LOGIKA UMUM: SORTING TABEL & MENUTUP MODAL DARI AREA GELAP
    // ====================================================================

    // Tutup Modal jika area gelap di-klik
    window.addEventListener('click', function (e) {
        const modalPeriode = document.getElementById('modalPeriode');
        const modalPertanyaan = document.getElementById('modalPertanyaan');
        const modalPenilaian = document.getElementById('modalPenilaian');
        const modalMonitoring = document.getElementById('modalMonitoring'); // Tambahan baru

        if (e.target === modalPeriode) tutupModalPeriode();
        if (e.target === modalPertanyaan) tutupModalPertanyaan();
        if (e.target === modalMonitoring) tutupModalMonitoring(); // Tambahan baru
    });

    // ====================================================================
    // UPDATE SCORE AREA DATA-SORT (Agar Live Score bisa di-sort manual)
    // ====================================================================
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
                // Set data-sort ke skor agar bisa disorting
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

        // Auto-sorting kandidat yang dinilai ke paling atas
        rows.sort((a, b) => {
            let aAssessed = parseInt(a.getAttribute('data-assessed'));
            let bAssessed = parseInt(b.getAttribute('data-assessed'));
            return bAssessed - aAssessed;
        });

        rows.forEach(row => table.appendChild(row));

        const panelFinal = document.getElementById('panel-finalisasi');
        if (Object.keys(penilaianData).length === 3) {
            panelFinal.style.display = 'block';
        } else {
            panelFinal.style.display = 'none';
        }
    };


    // ====================================================================
    // FUNGSI SORTING ADVANCED (TEKS & ANGKA) DENGAN IKON PANAH
    // ====================================================================
    window.istSortTable = function (n) {
        let table = document.getElementById("tabel-kandidat");
        if (!table) return;
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc";

        // Atur UI Ikon Panah
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
                if (!rows[i].getAttribute('data-nip')) continue; // Lewati baris "Data Kosong"

                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];

                // Ambil data-sort (Prioritas pertama) atau innerText
                let valX = x.getAttribute('data-sort') !== null ? x.getAttribute('data-sort') : x.innerText.toLowerCase();
                let valY = y.getAttribute('data-sort') !== null ? y.getAttribute('data-sort') : y.innerText.toLowerCase();

                // Cek apakah data murni berupa angka
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

        // Putar Ikon Sesuai Arah
        if (currentHeaderIcon) {
            currentHeaderIcon.className = dir === 'asc' ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
        }
    };


    // ====================================================================
    // FUNGSI PENCARIAN (FILTER TABEL) REAL-TIME
    // ====================================================================
    window.istFilterTable = function () {
        let input = document.getElementById("input-cari-kandidat");
        let filter = input.value.toLowerCase();
        let table = document.getElementById("tbody-kandidat");
        let tr = table.getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
            // Lewati baris kosong jika ada
            if (!tr[i].getAttribute('data-nip')) continue;

            // Mencari di kolom 2 (Profil) dan 3 (Jabatan)
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

    // ====================================================================
    // FUNGSI MODAL MONITORING PARTISIPASI
    // ====================================================================
    window.bukaModalMonitoring = function () {
        const modal = document.getElementById('modalMonitoring');
        if (modal) modal.style.display = 'flex';
    };

    // ====================================================================
    // FUNGSI SWITCH TAB DI DALAM MODAL MONITORING
    // ====================================================================
    window.switchMonTab = function (tabId) {
        // Atur UI Tombol Tab
        document.getElementById('btn-mon-1_1').classList.remove('active');
        document.getElementById('btn-mon-1_2').classList.remove('active');
        document.getElementById('btn-mon-' + tabId).classList.add('active');

        // Tampilkan/Sembunyikan Konten Tab
        document.getElementById('mon-tab-1_1').style.display = 'none';
        document.getElementById('mon-tab-1_2').style.display = 'none';
        document.getElementById('mon-tab-' + tabId).style.display = 'block';
    };

    window.tutupModalMonitoring = function () {
        const modal = document.getElementById('modalMonitoring');
        if (modal) modal.style.display = 'none';
    };

    // ====================================================================
    // FUNGSI COPY FORMAT WA UNTUK DAFTAR PEGAWAI BELUM MENILAI (VERSI TANGGUH)
    // ====================================================================
    window.copyDaftarWA = function (elementId) {
        let copyText = document.getElementById(elementId);

        if (copyText) {
            // Fungsi Notifikasi (agar kodenya rapi tidak diulang-ulang)
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

            // Cek apakah browser mendukung Clipboard API (biasanya wajib HTTPS)
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(copyText.value)
                    .then(() => suksesAlert())
                    .catch(err => console.error("Gagal menyalin via Clipboard API: ", err));
            }
            else {
                // JALUR KLASIK (Fallback) JIKA BERADA DI HTTP BIASA ATAU JARINGAN LOKAL
                copyText.style.display = 'block'; // Tampilkan textarea sejenak
                copyText.select();
                copyText.setSelectionRange(0, 99999); // Kompatibilitas untuk perangkat mobile

                try {
                    document.execCommand("copy"); // Eksekusi copy lawas
                    suksesAlert();
                } catch (err) {
                    Swal.fire('Gagal!', 'Browser Anda menolak akses papan klip.', 'error');
                }

                copyText.style.display = 'none'; // Sembunyikan lagi
            }
        }
    };

    // ====================================================================
    // FUNGSI KONFIRMASI PENETAPAN & PEMBATALAN (TAHAP 1.2)
    // ====================================================================
    document.body.addEventListener('click', function (e) {

        // Logika Klik Tombol Tetapkan
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

        // Logika Klik Tombol Batalkan
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

    // =========================================================
    // LOGIKA MODAL PERSYARATAN IST
    // =========================================================
    const btnInfoPersyaratan = document.getElementById('btn-info-persyaratan');
    const modalPersyaratan = document.getElementById('modal-persyaratan');
    const tutupModalPersyaratan = document.getElementById('tutup-modal-persyaratan');
    const btnTutupPersyaratan = document.getElementById('btn-tutup-persyaratan');

    if (btnInfoPersyaratan && modalPersyaratan) {
        // Buka Modal
        btnInfoPersyaratan.addEventListener('click', function () {
            modalPersyaratan.style.display = 'flex';
        });

        // Tutup via tombol 'X'
        if (tutupModalPersyaratan) {
            tutupModalPersyaratan.addEventListener('click', function () {
                modalPersyaratan.style.display = 'none';
            });
        }

        // Tutup via tombol 'Tutup' di bawah
        if (btnTutupPersyaratan) {
            btnTutupPersyaratan.addEventListener('click', function () {
                modalPersyaratan.style.display = 'none';
            });
        }

        // Tutup jika klik area gelap (luar modal)
        window.addEventListener('click', function (event) {
            if (event.target === modalPersyaratan) {
                modalPersyaratan.style.display = 'none';
            }
        });
    }
});
