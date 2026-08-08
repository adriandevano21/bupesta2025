function previewExcel() {
        const textData = document.getElementById('excel_data').value.trim();
        if (!textData) {
            alert('Kotak masih kosong! Silakan paste data dari Excel terlebih dahulu.');
            return;
        }

        const tbody = document.getElementById('preview-tbody');
        tbody.innerHTML = ''; // Bersihkan tabel sebelumnya

        // Pisahkan antar baris
        const rows = textData.split('\n');

        rows.forEach(row => {
            // Pisahkan antar kolom (Gunakan Tab / \t)
            const cols = row.split('\t');

            // Buat elemen tr (baris)
            const tr = document.createElement('tr');

            // Loop 6 kolom yang diharapkan (sesuai urutan Excel)
            for (let i = 0; i < 6; i++) {
                const td = document.createElement('td');
                td.style.padding = '8px';
                td.style.border = '1px solid #e2e8f0';
                td.innerText = cols[i] !== undefined ? cols[i].trim() : '-';
                tr.appendChild(td);
            }

            tbody.appendChild(tr);
        });

        // Munculkan area preview
        document.getElementById('preview-area').style.display = 'block';
    }


    // 1. Logika Centang Semua (Check All)
    const checkAll = document.getElementById('checkAll');
    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.checkItem');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // 2. Helper untuk mengirim data ke Controller via Form tersembunyi
    function prosesHapusData(action, ids = []) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "/hukdispegawai/delete";

        // CSRF Token Keamanan Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = '_token';
        inputCsrf.value = csrfToken;
        form.appendChild(inputCsrf);

        // Input Penanda Aksi (all / selected)
        const inputAction = document.createElement('input');
        inputAction.type = 'hidden';
        inputAction.name = 'action';
        inputAction.value = action;
        form.appendChild(inputAction);

        // Jika ada ID terpilih, masukkan sebagai array
        if (ids.length > 0) {
            ids.forEach(id => {
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'ids[]';
                inputId.value = id;
                form.appendChild(inputId);
            });
        }

        document.body.appendChild(form);
        form.submit();
    }

    // 3. Fungsi Tombol Hapus Terpilih
    function hapusTerpilih() {
        const checkboxes = document.querySelectorAll('.checkItem:checked');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Pilih setidaknya satu baris data yang ingin dihapus!');
            return;
        }

        if (confirm(`Yakin ingin menghapus ${selectedIds.length} data terpilih?`)) {
            prosesHapusData('selected', selectedIds);
        }
    }

    // 4. Fungsi Tombol Kosongkan Tabel
    function hapusSemua() {
        if (confirm('PERINGATAN KERAS: Anda yakin ingin menghapus SELURUH data di tabel ini?\n\nAksi ini tidak dapat dibatalkan!')) {
            prosesHapusData('all');
        }
    }
