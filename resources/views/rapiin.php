<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Matriks (simple + heatmap + Select2 triwulan)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- DataTables + FixedColumns CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    <style>
        body {
            font-family: system-ui, Arial, sans-serif;
            margin: 0;
            padding: 12px;
            color: #111827
        }

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap
        }

        .toolbar label {
            font-weight: 600;
            color: #374151
        }

        #info {
            margin: 4px 0 10px;
            color: #4b5563
        }

        .wrap {
            max-width: 100%;
            overflow: auto
        }

        /* Select2: sederhana & rapi */
        .tri-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
        }

        .tri-wrap:focus-within {
            outline: 2px solid #93c5fd;
            outline-offset: 2px
        }

        .tri-wrap .select2-container {
            min-width: 240px;
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827;
            font-weight: 700;
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
        }

        /* Tint wrapper sesuai triwulan (diubah via JS) */
        .tri-wrap.tri-1 {
            background: #dcfce7;
            border-color: #86efac
        }

        .tri-wrap.tri-2 {
            background: #e0f2fe;
            border-color: #93c5fd
        }

        .tri-wrap.tri-3 {
            background: #ffedd5;
            border-color: #fdba74
        }

        .tri-wrap.tri-4 {
            background: #efe9ff;
            border-color: #c4b5fd
        }

        /* Tabel simple + warna lembut */
        table.dataTable thead th {
            background: linear-gradient(90deg, #e8f8e8, #e8f0ff);
            color: #1f2937;
            font-weight: 700;
            text-align: center;
        }

        table.dataTable td,
        table.dataTable th {
            white-space: nowrap;
            text-align: center;
            padding: 8px 10px !important;
        }

        .col-indikator {
            background: #fff8e6;
            font-weight: 700;
            border-left: 3px solid #60a5fa !important
        }

        .col-pilar {
            background: #f3faf3;
            font-weight: 700
        }

        .cell-num {
            font-weight: 700
        }

        .cell-empty {
            background: #ffe5e5 !important;
            color: #7f1d1d !important;
            font-weight: 700
        }

        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0
        }

        table.dataTable tbody td {
            border-right: 1px solid #f0f0f0
        }

        table.dataTable tbody tr:nth-child(odd) {
            background: #fafafa
        }

        /* Samakan lebar semua kolom kode_satker (th & td) */
        #tbl th.satker-col,
        #tbl td.satker-col {
            width: 84px;
            min-width: 84px;
            max-width: 84px;
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <label for="triSelect">Triwulan:</label>
        <div id="triWrap" class="tri-wrap tri-1">
            <select id="triSelect">
                <option value="1" selected>Triwulan 1 (nilai_1)</option>
                <option value="2">Triwulan 2 (nilai_2)</option>
                <option value="3">Triwulan 3 (nilai_3)</option>
                <option value="4">Triwulan 4 (nilai_4)</option>
            </select>
        </div>
    </div>

    <div class="wrap">
        <div id="info">Memuat data…</div>
        <table id="tbl" class="display nowrap compact" style="width:100%">
            <thead></thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables + FixedColumns JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        const ENDPOINT = "https://script.google.com/macros/s/AKfycbxXE6T-yd5VWK1mXE69bCzw0FCqyuri2X7doO2NHQWu6_kgu4yqnMx-nok0mhxXxvmAfg/exec";

        /* 24 kolom kode_satker */
        const ALL_SATKERS = [
            "1100", "1101", "1102", "1103", "1104", "1105", "1106", "1107", "1108", "1109",
            "1110", "1111", "1112", "1113", "1114", "1115", "1116", "1117", "1118",
            "1171", "1172", "1173", "1174", "1175"
        ];
        const SATKER_TARGETS = ALL_SATKERS.map((_, i) => i + 2); // kolom 2..akhir adalah satker
        const TRI_FIELDS = {
            1: 'nilai_1',
            2: 'nilai_2',
            3: 'nilai_3',
            4: 'nilai_4'
        };

        const clean = s => String(s ?? "").trim();

        function romanToInt(r) {
            const m = {
                I: 1,
                V: 5,
                X: 10,
                L: 50,
                C: 100,
                D: 500,
                M: 1000
            };
            let n = 0;
            for (let i = 0; i < r.length; i++) {
                const v = m[r[i]] || 0,
                    vn = m[r[i + 1]] || 0;
                n += v < vn ? -v : v;
            }
            return n || 999;
        }

        function asNumber(x) {
            const n = typeof x === "number" ? x : parseFloat(String(x).replace(",", "."));
            return Number.isFinite(n) ? n : null;
        }

        /* gradasi: 0=merah, 50=kuning, 100=hijau */
        function heatColor(v) {
            const val = Math.max(0, Math.min(100, v));
            if (val <= 50) {
                const t = val / 50;
                const r = 255;
                const g = Math.round(229 + (255 - 229) * t);
                const b = Math.round(229 * (1 - t));
                return `rgb(${r},${g},${b})`;
            } else {
                const t = (val - 50) / 50;
                const r = Math.round(255 * (1 - t));
                const g = 255;
                const b = Math.round(0 + (230 - 0) * t);
                return `rgb(${r},${g},${b})`;
            }
        }

        let dt = null;
        let inds = [],
            pilars = [];
        let idx = new Map(); // key -> {nilai_1..nilai_4}

        async function loadData() {
            const res = await fetch(ENDPOINT);
            if (!res.ok) throw new Error("HTTP " + res.status);
            const raw = await res.json();
            let rows = Array.isArray(raw) ? raw : (Array.isArray(raw.data) ? raw.data : [raw]);

            // mapping (ubah jika nama field berbeda)
            rows = rows.map(r => ({
                kode_satker: clean(r.kode_satker ?? r.kode ?? r.satker ?? r.KODE_SATKER),
                indikator: clean(r.indikator ?? r.ind ?? r.group ?? r.kelompok ?? "A"),
                pilar: clean(r.pilar ?? r.PILAR ?? r.level ?? r.tahap),
                nilai_1: r.nilai_1 ?? r.nilai ?? r.value ?? r.NILAI_1,
                nilai_2: r.nilai_2 ?? r.NILAI_2 ?? null,
                nilai_3: r.nilai_3 ?? r.NILAI_3 ?? null,
                nilai_4: r.nilai_4 ?? r.NILAI_4 ?? null
            }));

            inds = Array.from(new Set(rows.map(r => r.indikator))).sort(); // A, B
            pilars = Array.from(new Set(rows.map(r => r.pilar))).sort((a, b) => romanToInt(a) - romanToInt(b));

            idx = new Map();
            for (const r of rows) {
                const key = `${r.kode_satker}|${r.indikator}|${r.pilar}`;
                idx.set(key, {
                    nilai_1: r.nilai_1,
                    nilai_2: r.nilai_2,
                    nilai_3: r.nilai_3,
                    nilai_4: r.nilai_4
                });
            }

            // Header statis
            const thead = document.querySelector("#tbl thead");
            thead.innerHTML = `<tr>
    <th>Indikator</th>
    <th>Pilar</th>
    ${ALL_SATKERS.map(s=>`<th>${s}</th>`).join("")}
  </tr>`;
        }

        function makeDataForTri(tri) {
            const field = TRI_FIELDS[tri] || 'nilai_1';
            const data = [];
            for (const ind of inds) {
                for (const p of pilars) {
                    const row = [ind, p];
                    for (const s of ALL_SATKERS) {
                        const rec = idx.get(`${s}|${ind}|${p}`);
                        const val = rec ? rec[field] : undefined;
                        if (val === undefined || val === null || val === "" || String(val).toLowerCase().includes("belum")) {
                            row.push("Belum Isi");
                        } else {
                            const n = asNumber(val);
                            row.push(n === null ? "Belum Isi" : Number(n.toFixed(2)));
                        }
                    }
                    data.push(row);
                }
            }
            return data;
        }

        function initOrUpdateTable(data) {
            if (!dt) {
                dt = new DataTable('#tbl', {
                    data,
                    ordering: false,
                    paging: false,
                    searching: false,
                    info: false,
                    scrollX: true,
                    autoWidth: false,
                    fixedColumns: {
                        leftColumns: 2
                    },
                    columnDefs: [{
                            targets: 0,
                            className: 'col-indikator'
                        },
                        {
                            targets: 1,
                            className: 'col-pilar'
                        },
                        {
                            targets: SATKER_TARGETS,
                            className: 'satker-col',
                            width: '84px'
                        }
                    ],
                    createdRow: function(row, rowData) {
                        for (let i = 2; i < rowData.length; i++) {
                            const td = row.children[i];
                            const v = rowData[i];
                            if (typeof v === "number") {
                                td.classList.add('cell-num');
                                td.style.background = heatColor(v);
                            } else {
                                td.classList.add('cell-empty');
                                td.textContent = "Belum Isi";
                            }
                        }
                    }
                });
            } else {
                dt.clear();
                dt.rows.add(data);
                dt.draw(false);
            }
        }

        function setTriTint(tri) {
            const wrap = document.getElementById('triWrap');
            wrap.classList.remove('tri-1', 'tri-2', 'tri-3', 'tri-4');
            wrap.classList.add(`tri-${tri}`);
        }

        $(async function() {
            // Inisialisasi Select2 (simple)
            $('#triSelect').select2({
                width: 'resolve',
                minimumResultsForSearch: Infinity, // sembunyikan kotak pencarian (hanya 4 opsi)
                dropdownAutoWidth: true
            });

            try {
                await loadData();
                const tri = parseInt($('#triSelect').val(), 10) || 1;
                setTriTint(tri);
                const data = makeDataForTri(tri);
                initOrUpdateTable(data);
                $('#info').text(`Triwulan ${tri} · Indikator: ${inds.join(", ")} · Pilar: ${pilars.length} · Kode_satker: ${ALL_SATKERS.length}`);
            } catch (e) {
                $('#info').text('Gagal memuat: ' + e.message);
                console.error(e);
            }

            // Ganti triwulan -> rebuild data
            $('#triSelect').on('change', function() {
                const tri = parseInt(this.value, 10) || 1;
                setTriTint(tri);
                const data = makeDataForTri(tri);
                initOrUpdateTable(data);
                $('#info').text(`Triwulan ${tri} · Indikator: ${inds.join(", ")} · Pilar: ${pilars.length} · Kode_satker: ${ALL_SATKERS.length}`);
            });
        });
    </script>
</body>

</html>