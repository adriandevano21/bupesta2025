<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuPeSta - {{ $data['judul'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('assets-jazirah/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets-se2026/load/load.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-jazirah/style/potrait-warning.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-bupesta/admin.css') }}">

    <script src="{{ asset('assets-se2026/load/load.js') }}"></script>
</head>

<body>

    <div id="orientation-warning" style="display: none;">
        <h1>Putar Perangkat Anda</h1>
        <p>Untuk pengalaman terbaik, silakan ubah ke <strong>mode landscape</strong>.</p>
        <div class="phone-wrapper">
            <div class="screen"></div>
            <div class="button"></div>
        </div>
    </div>

    <div id="loading">
        <div id="loader-wrapper">
            <div id="loader"></div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>

    <div id="page">
        <header>
            @include('adminbupesta.navbar-admin')
        </header>

        <div class="konten">
            @include('layout2.animasitextbps')
            <br>

            <div class="posisitengah">

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div
                        style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- KOTAK AREA INPUT COPY PASTE --}}
                <div
                    style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3 style="margin-bottom: 15px; color: var(--warna-utama, #f79039);"><i
                            class="fas fa-file-excel"></i> Import Data SIMPEG Baru</h3>
                    <p style="margin-bottom: 15px; font-size: 0.9rem; color: #6b7280;">
                        Silakan tetapkan tanggal versi data terlebih dahulu, lalu paste 14 kolom data dari Excel (Mulai
                        dari NIP BPS sampai Tempat Lahir).
                    </p>

                    <form action="{{ route('datasimpeg.store') }}" method="POST">
                        @csrf

                        {{-- INPUT TANGGAL VERSI DATA (Diisi 1x untuk semua baris) --}}
                        <div style="margin-bottom: 15px; max-width: 300px;">
                            <label style="font-weight: 600; font-size: 0.9rem; color: #374151;">Tanggal Versi Data
                                (Untuk Semua Baris):</label>
                            <input type="date" name="tanggal_versidata" required
                                style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 5px;">
                        </div>

                        <textarea id="excel_data" name="excel_data" rows="5" required
                            style="width: 100%; padding: 15px; border: 2px dashed #cbd5e1; border-radius: 8px; font-family: monospace; margin-bottom: 15px; background: #f8fafc; white-space: pre; overflow-wrap: normal; overflow-x: scroll;"
                            placeholder="Paste (Tempel) 14 Kolom data dari Excel di sini..."></textarea>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="previewExcelSimpeg()"
                                style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-eye"></i> Preview Format
                            </button>
                            <button type="submit" class="btn-simpan"
                                style="padding: 10px 20px; cursor: pointer; background: var(--warna-utama, #f79039); color: white; border: none; border-radius: 6px;">
                                <i class="fas fa-save"></i> Simpan Massal ke Database
                            </button>
                        </div>
                    </form>

                    {{-- Tabel Preview --}}
                    <div id="preview-area" style="display: none; margin-top: 20px; overflow-x: auto;">
                        <h4 style="margin-bottom: 10px; font-size: 0.9rem;">Pratinjau Data (Periksa sebelum simpan):
                        </h4>
                        <table style="width: 100%; border-collapse: collapse; min-width: 1500px; font-size: 0.8rem;"
                            border="1">
                            <thead style="background: #334155; color: white;">
                                <tr>
                                    <th>NIP BPS</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Kode Org</th>
                                    <th>Jabatan</th>
                                    <th>Wilayah</th>
                                    <th>TMT Jab</th>
                                    <th>Gol. Akhir</th>
                                    <th>TMT Gol</th>
                                    <th>Status</th>
                                    <th>Pend. SK</th>
                                    <th>MKS Thn</th>
                                    <th>MKS Bln</th>
                                    <th>Tpt Lahir</th>
                                </tr>
                            </thead>
                            <tbody id="preview-tbody" style="background: #fff; text-align: center;"></tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL DATA EXISTING --}}
                <div
                    style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow-x: auto;">

                    {{-- Header Tabel & Tombol Aksi Hapus --}}
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                        <h3 style="margin: 0;"><i class="fas fa-users"></i> Daftar Data SIMPEG</h3>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="hapusTerpilihSimpeg()"
                                style="padding: 8px 15px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-trash-alt"></i> Hapus Terpilih
                            </button>
                            <button type="button" onclick="hapusSemuaSimpeg()"
                                style="padding: 8px 15px; background: #7f1d1d; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-dumpster"></i> Kosongkan Tabel
                            </button>
                        </div>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; min-width: 1600px; font-size: 0.85rem;">
                        <thead style="background: #f1f5f9;">
                            <tr>
                                <th style="padding: 10px; border: 1px solid #e2e8f0; text-align: center; width: 40px;">
                                    <input type="checkbox" id="checkAllSimpeg" style="cursor: pointer;">
                                </th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0; width: 80px; text-align: center;">
                                    Aksi</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Tanggal Versi</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">NIP / NIP BPS</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Nama Pegawai</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Jabatan (Kode)</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Wilayah</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Gol. Akhir (TMT)</th>
                                <th style="padding: 10px; border: 1px solid #e2e8f0;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['simpeg'] ?? [] as $row)
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td style="padding: 8px; text-align: center;">
                                        <input type="checkbox" class="checkItemSimpeg" value="{{ $row->id }}"
                                            style="cursor: pointer;">
                                    </td>
                                    <td style="padding: 8px; text-align: center;">
                                        <div style="display: flex; gap: 5px; justify-content: center;">
                                            {{-- Tombol Edit --}}
                                            <button type="button" onclick="bukaModalEditSimpeg({{ $row }})"
                                                style="padding: 5px 8px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            {{-- Tombol Hapus Satuan --}}
                                            <button type="button"
                                                onclick="hapusSatuSimpeg({{ $row->id }}, '{{ addslashes($row->nama) }}')"
                                                style="padding: 5px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem;"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td style="padding: 8px; text-align: center;"><b>{{ $row->tanggal_versidata }}</b>
                                    </td>
                                    <td style="padding: 8px;">{{ $row->nip }}<br><small
                                            style="color: #6b7280;">{{ $row->nip_bps }}</small></td>
                                    <td style="padding: 8px; font-weight: 500;">{{ $row->nama }}</td>
                                    <td style="padding: 8px;">{{ $row->jabatan }}<br><small
                                            style="color: #6b7280;">{{ $row->kode_org }}</small></td>
                                    <td style="padding: 8px;">{{ $row->wilayah }}</td>
                                    <td style="padding: 8px; text-align: center;">{{ $row->gol_akhir }}<br><small
                                            style="color: #6b7280;">{{ $row->tmt_gol }}</small></td>
                                    <td style="padding: 8px;">{{ $row->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="padding: 20px; text-align: center; color: #6b7280;">
                                        Belum ada data SIMPEG.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MODAL EDIT DATA SIMPEG --}}
                <div id="modalEditSimpeg" class="modal-overlay" style="display: none;">
                    <div class="modal-content" style="max-width: 800px; width: 90%;">
                        <div class="modal-header">
                            <h3>Edit Data Pegawai</h3>
                            <button type="button"
                                onclick="document.getElementById('modalEditSimpeg').style.display='none'"
                                style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <form action="{{ route('datasimpeg.update') }}" method="POST">
                                @csrf
                                <input type="hidden" id="edit_id" name="id_edit">

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <label style="font-size: 0.85rem;">Nama Lengkap</label>
                                        <input type="text" id="edit_nama" name="nama" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Tanggal Versi Data</label>
                                        <input type="date" id="edit_tanggal_versidata" name="tanggal_versidata"
                                            class="input-form" style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">NIP</label>
                                        <input type="text" id="edit_nip" name="nip" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">NIP BPS</label>
                                        <input type="text" id="edit_nip_bps" name="nip_bps" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Jabatan</label>
                                        <input type="text" id="edit_jabatan" name="jabatan" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Kode Org</label>
                                        <input type="text" id="edit_kode_org" name="kode_org" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Wilayah</label>
                                        <input type="text" id="edit_wilayah" name="wilayah" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Status</label>
                                        <input type="text" id="edit_status" name="status" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Gol. Akhir</label>
                                        <input type="text" id="edit_gol_akhir" name="gol_akhir"
                                            class="input-form" style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">TMT Gol</label>
                                        <input type="text" id="edit_tmt_gol" name="tmt_gol" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">TMT Jab</label>
                                        <input type="text" id="edit_tmt_jab" name="tmt_jab" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Pend. SK</label>
                                        <input type="text" id="edit_pend_sk" name="pend_sk" class="input-form"
                                            style="width: 100%; padding: 8px;">
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">MKS Tahun & Bulan</label>
                                        <div style="display: flex; gap: 10px;">
                                            <input type="text" id="edit_mks_thn" name="mks_thn"
                                                class="input-form" placeholder="Thn"
                                                style="width: 50%; padding: 8px;">
                                            <input type="text" id="edit_mks_bln" name="mks_bln"
                                                class="input-form" placeholder="Bln"
                                                style="width: 50%; padding: 8px;">
                                        </div>
                                    </div>
                                    <div>
                                        <label style="font-size: 0.85rem;">Tempat Lahir</label>
                                        <input type="text" id="edit_tempat_lahir" name="tempat_lahir"
                                            class="input-form" style="width: 100%; padding: 8px;">
                                    </div>
                                </div>
                                <div class="modal-footer"
                                    style="margin-top: 20px; text-align: right; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                                    <button type="submit" class="btn-simpan"
                                        style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer;">Simpan
                                        Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <footer>
                <p><i class="fa-solid fa-mug-hot"></i>&nbsp Tim Pengolahan dan TI - BPS Provinsi Aceh </p>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets-jazirah/style/potrait-warning.js') }}"></script>
    <script src="{{ asset('assets-bupesta/admin.js') }}"></script>
    <script>
        // 1. Fungsi Preview Excel Khusus SIMPEG (14 Kolom)
        function previewExcelSimpeg() {
            const textData = document.getElementById('excel_data').value.trim();
            const tglVersi = document.querySelector('input[name="tanggal_versidata"]').value;

            if (!textData) {
                alert('Kotak teks masih kosong! Paste data dari Excel terlebih dahulu.');
                return;
            }
            if (!tglVersi) {
                alert('Harap isi Tanggal Versi Data terlebih dahulu sebelum preview!');
                return;
            }

            const tbody = document.getElementById('preview-tbody');
            tbody.innerHTML = '';

            const rows = textData.split('\n');

            rows.forEach(row => {
                // Pecah dengan pemisah TAB (\t) dan hapus karakter enter
                const cols = row.replace(/\r/g, "").split('\t');

                const tr = document.createElement('tr');

                // Loop khusus 14 kolom
                for (let i = 0; i < 14; i++) {
                    const td = document.createElement('td');
                    td.style.padding = '5px';
                    td.style.border = '1px solid #cbd5e1';
                    td.innerText = cols[i] !== undefined && cols[i] !== '' ? cols[i].trim() : '-';
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);
            });

            document.getElementById('preview-area').style.display = 'block';
        }

        // 2. Fungsi Membuka Modal Edit dan Mengisi Value Form
        function bukaModalEditSimpeg(data) {
            // Ambil elemen modal
            const modal = document.getElementById('modalEditSimpeg');

            // Suntikkan data dari parameter (JSON Object) ke masing-masing input form
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_tanggal_versidata').value = data.tanggal_versidata || '';
            document.getElementById('edit_nip_bps').value = data.nip_bps || '';
            document.getElementById('edit_nip').value = data.nip || '';
            document.getElementById('edit_nama').value = data.nama || '';
            document.getElementById('edit_kode_org').value = data.kode_org || '';
            document.getElementById('edit_jabatan').value = data.jabatan || '';
            document.getElementById('edit_wilayah').value = data.wilayah || '';
            document.getElementById('edit_tmt_jab').value = data.tmt_jab || '';
            document.getElementById('edit_gol_akhir').value = data.gol_akhir || '';
            document.getElementById('edit_tmt_gol').value = data.tmt_gol || '';
            document.getElementById('edit_status').value = data.status || '';
            document.getElementById('edit_pend_sk').value = data.pend_sk || '';
            document.getElementById('edit_mks_thn').value = data.mks_thn || '';
            document.getElementById('edit_mks_bln').value = data.mks_bln || '';
            document.getElementById('edit_tempat_lahir').value = data.tempat_lahir || '';

            // Tampilkan modal
            modal.style.display = 'flex';
        }

        // 1. Logika Checkbox Select All
        const checkAllSimpeg = document.getElementById('checkAllSimpeg');
        if (checkAllSimpeg) {
            checkAllSimpeg.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.checkItemSimpeg');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        // 2. Helper Pengirim Form Tersembunyi untuk Hapus SIMPEG
        function prosesHapusSimpeg(action, ids = [], idDelete = null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "/datasimpeg/delete"; // Menggunakan URL murni agar aman di file JS eksternal

            // CSRF Token
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                const inputCsrf = document.createElement('input');
                inputCsrf.type = 'hidden';
                inputCsrf.name = '_token';
                inputCsrf.value = csrfMeta.getAttribute('content');
                form.appendChild(inputCsrf);
            }

            // Aksi (selected / all / single)
            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = action;
            form.appendChild(inputAction);

            // Kirim Array IDs jika hapus terpilih
            if (ids.length > 0) {
                ids.forEach(id => {
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'ids[]';
                    inputId.value = id;
                    form.appendChild(inputId);
                });
            }

            // Kirim ID tunggal jika hapus satuan
            if (idDelete) {
                const inputIdSingle = document.createElement('input');
                inputIdSingle.type = 'hidden';
                inputIdSingle.name = 'id_delete';
                inputIdSingle.value = idDelete;
                form.appendChild(inputIdSingle);
            }

            document.body.appendChild(form);
            form.submit();
        }

        // 3. Fungsi Hapus Terpilih
        function hapusTerpilihSimpeg() {
            const checkboxes = document.querySelectorAll('.checkItemSimpeg:checked');
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu baris data pegawai yang ingin dihapus!');
                return;
            }

            if (confirm(`Apakah Anda yakin ingin menghapus ${selectedIds.length} data pegawai terpilih?`)) {
                prosesHapusSimpeg('selected', selectedIds);
            }
        }

        // 4. Fungsi Kosongkan Tabel
        function hapusSemuaSimpeg() {
            if (confirm(
                    'PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH data SIMPEG?\n\nSemua baris data pegawai di tabel ini akan terhapus permanent.'
                )) {
                prosesHapusSimpeg('all');
            }
        }

        // 5. Fungsi Hapus Satuan per Baris
        function hapusSatuSimpeg(id, nama) {
            if (confirm(`Yakin ingin menghapus data pegawai atas nama "${nama}"?`)) {
                prosesHapusSimpeg('single', [], id);
            }
        }
    </script>

</body>

</html>
