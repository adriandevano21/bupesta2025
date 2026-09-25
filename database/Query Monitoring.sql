CREATE OR REPLACE VIEW monitoring_jazirah_tw1 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun (Tetap sama, mengambil seluruh target pada tahun berjalan)
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 1 (Bulan 1 s.d 3 / TW <= 1)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN 1 ELSE 0 END) AS target_triwulan_tw1,

    -- 2. Realisasi Triwulan 1 (Bulan 1 s.d 3 / TW <= 1)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 1 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw1,

    -- [TAMBAHAN] Perlu di Periksa TW 1
    SUM(
        CASE WHEN CEIL(h.bulan_realisasi / 3) <= 1 THEN
            1 -- Dasar: Jumlah Realisasi TW 1
            - (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) -- Kurangi jika ada komentar
            - (CASE WHEN h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) -- Kurangi jika divalidasi
            + (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) -- Tambah jika ditanggapi
        ELSE 0 END
    ) AS perlu_di_periksa_tw1,

    -- [TAMBAHAN] Perlu Tindak Lanjut TW 1
    SUM(
        CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN
            (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) -- Dasar: Ada komentar
            - (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) -- Kurangi jika sudah ditanggapi operator
        ELSE 0 END
    ) AS perlu_tindak_lanjut_tw1,

    -- [TAMBAHAN] Sudah Validasi TW 1
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) AS sudah_validasi_tw1,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi TW 1
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 1 THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_tw1,

    -- 4. Persentase Evaluasi TW 1
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_tw1,

    -- 5. Persentase Tindak Lanjut TW 1
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_tw1,

    -- 6. Persentase Dokumen Selesai TW 1
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 1 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_tw1

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;

CREATE OR REPLACE VIEW monitoring_jazirah_tw2 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 2 (Kumulatif s.d TW 2)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END) AS target_triwulan_tw2,

    -- 2. Realisasi Triwulan 2 (Kumulatif s.d TW 2)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 2 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw2,

    -- Perlu di Periksa TW 2
    SUM(
        CASE WHEN CEIL(h.bulan_realisasi / 3) <= 2 THEN
            1 
            - (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) 
            + (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_di_periksa_tw2,

    -- Perlu Tindak Lanjut TW 2
    SUM(
        CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN
            (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_tindak_lanjut_tw2,

    -- Sudah Validasi TW 2
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) AS sudah_validasi_tw2,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi TW 2
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 2 THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_tw2,

    -- 4. Persentase Evaluasi TW 2
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_tw2,

    -- 5. Persentase Tindak Lanjut TW 2
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_tw2,

    -- 6. Persentase Dokumen Selesai TW 2
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_tw2

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;

CREATE OR REPLACE VIEW monitoring_jazirah_tw3 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 3 (Kumulatif s.d TW 3)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END) AS target_triwulan_tw3,

    -- 2. Realisasi Triwulan 3 (Kumulatif s.d TW 3)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 3 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw3,

    -- Perlu di Periksa TW 3
    SUM(
        CASE WHEN CEIL(h.bulan_realisasi / 3) <= 3 THEN
            1 
            - (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) 
            + (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_di_periksa_tw3,

    -- Perlu Tindak Lanjut TW 3
    SUM(
        CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN
            (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_tindak_lanjut_tw3,

    -- Sudah Validasi TW 3
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) AS sudah_validasi_tw3,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi TW 3
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 3 THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_tw3,

    -- 4. Persentase Evaluasi TW 3
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_tw3,

    -- 5. Persentase Tindak Lanjut TW 3
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_tw3,

    -- 6. Persentase Dokumen Selesai TW 3
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_tw3

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;

CREATE OR REPLACE VIEW monitoring_jazirah_tw4 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 4 (Kumulatif s.d TW 4)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END) AS target_triwulan_tw4,

    -- 2. Realisasi Triwulan 4 (Kumulatif s.d TW 4)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 4 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw4,

    -- Perlu di Periksa TW 4
    SUM(
        CASE WHEN CEIL(h.bulan_realisasi / 3) <= 4 THEN
            1 
            - (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) 
            + (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_di_periksa_tw4,

    -- Perlu Tindak Lanjut TW 4
    SUM(
        CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN
            (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_tindak_lanjut_tw4,

    -- Sudah Validasi TW 4
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) AS sudah_validasi_tw4,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi TW 4
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 4 THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_tw4,

    -- 4. Persentase Evaluasi TW 4
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_tw4,

    -- 5. Persentase Tindak Lanjut TW 4
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_tw4,

    -- 6. Persentase Dokumen Selesai TW 4
    ROUND(
        (SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_tw4

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;


CREATE OR REPLACE VIEW monitoring_jazirah_bulan_berjalan AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Bulan Berjalan (Kumulatif)
    SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_bulan_berjalan,

    -- 2. Realisasi Bulan Berjalan (Kumulatif)
    SUM(CASE WHEN h.bulan_realisasi <= MONTH(CURRENT_DATE()) AND h.bulan_realisasi > 0 THEN 1 ELSE 0 END) AS realisasi_bulan_berjalan,

    -- Perlu di Periksa Bulan Berjalan
    SUM(
        CASE WHEN h.bulan_realisasi <= MONTH(CURRENT_DATE()) AND h.bulan_realisasi > 0 THEN
            1 
            - (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) 
            + (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_di_periksa_bb,

    -- Perlu Tindak Lanjut Bulan Berjalan
    SUM(
        CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN
            (CASE WHEN h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) 
            - (CASE WHEN h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) 
        ELSE 0 END
    ) AS perlu_tindak_lanjut_bb,

    -- Sudah Validasi Bulan Berjalan
    SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) AS sudah_validasi_bb,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi Bulan Berjalan
    ROUND(
        (SUM(CASE WHEN h.bulan_realisasi <= MONTH(CURRENT_DATE()) AND h.bulan_realisasi > 0 THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_bb,

    -- 4. Persentase Evaluasi Bulan Berjalan
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_bb,

    -- 5. Persentase Tindak Lanjut Bulan Berjalan
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_bb,

    -- 6. Persentase Dokumen Selesai Bulan Berjalan
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_bb

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;