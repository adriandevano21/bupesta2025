CREATE OR REPLACE VIEW monitoring_jazirah_tw3 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun (Tetap sama, mengambil seluruh target pada tahun berjalan)
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 3 (Bulan 1 s.d 9 / TW <= 3)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 3 THEN 1 ELSE 0 END) AS target_triwulan_tw3,

    -- 2. Realisasi Triwulan 3 (Bulan 1 s.d 9 / TW <= 3)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 3 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw3,

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



CREATE OR REPLACE VIEW monitoring_jazirah_tw2 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun (Tetap sama, mengambil seluruh target pada tahun berjalan)
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 2 (Bulan 1 s.d 6 / TW <= 2)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 2 THEN 1 ELSE 0 END) AS target_triwulan_tw2,

    -- 2. Realisasi Triwulan 2 (Bulan 1 s.d 6 / TW <= 2)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 2 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw2,

    -- 2.5 Persentase Penetapan Target
    -- (Tidak dibatasi TW, karena penetapan target dihitung berdasarkan target setahun penuh)
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
-- Catatan: Filter `AND h.tahun = ...` DIHAPUS agar view ini menampung data dari seluruh tahun yang ada.
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;



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


CREATE OR REPLACE VIEW monitoring_jazirah_tw4 AS
SELECT
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,

    -- Target Setahun (Tetap sama, mengambil seluruh target pada tahun berjalan)
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Triwulan 4 (Bulan 1 s.d 12 / TW <= 4)
    SUM(CASE WHEN CEIL(h.bulan_target / 3) <= 4 THEN 1 ELSE 0 END) AS target_triwulan_tw4,

    -- 2. Realisasi Triwulan 4 (Bulan 1 s.d 12 / TW <= 4)
    SUM(CASE WHEN CEIL(h.bulan_realisasi / 3) <= 4 THEN 1 ELSE 0 END) AS realisasi_triwulan_tw4,

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

    -- Target Setahun (Tetap semua target di tahun tersebut)
    SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END) AS target_setahun,

    -- 1. Target Bulan Ini (Kumulatif dari bulan 1 sampai dengan bulan berjalan saat ini)
    SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END) AS target_bulan_ini,

    -- 2. Realisasi Bulan Ini (Kumulatif s.d bulan berjalan saat ini)
    SUM(CASE WHEN h.bulan_realisasi <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END) AS realisasi_bulan_ini,

    -- 2.5 Persentase Penetapan Target
    ROUND(
        (SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 AND h.created_by_1 IS NOT NULL AND h.created_by_1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target IS NOT NULL AND h.bulan_target > 0 THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_penetapan_target,

    -- 3. Persentase Realisasi Bulan Ini
    ROUND(
        (SUM(CASE WHEN h.bulan_realisasi <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_realisasi_bulan_ini,

    -- 4. Persentase Evaluasi Bulan Ini
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.komentar_evaluator1 IS NOT NULL AND h.komentar_evaluator1 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_evaluasi_bulan_ini,

    -- 3. Persentase Tindak Lanjut Bulan Ini
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.created_by_4 IS NOT NULL AND h.created_by_4 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_tindaklanjut_bulan_ini,

    -- 5. Persentase Dokumen Selesai Bulan Ini
    ROUND(
        (SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) AND h.created_by_5 IS NOT NULL AND h.created_by_5 != '' THEN 1 ELSE 0 END) * 100.0) /
        NULLIF(SUM(CASE WHEN h.bulan_target <= MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END), 0)
    , 2) AS persentase_dokumen_selesai_bulan_ini

FROM jazirah2_hasil h
JOIN jazirah2_indikator i ON h.id_indikator = i.id
WHERE i.pengisian = 1
GROUP BY h.tahun, h.satker, i.kode_2, i.kode_3;
