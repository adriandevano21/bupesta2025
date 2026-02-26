CREATE OR REPLACE VIEW monitoring_jazirah AS
SELECT 
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,
    
    -- [1] PROGRES TW & TAHUNAN
    COALESCE(CAST(ROUND(AVG(CASE WHEN h.progres_tw1 = 'Tidak Ada Target' OR h.progres_tw1 = '' THEN NULL ELSE CAST(h.progres_tw1 AS DECIMAL(10,2)) END), 2) AS CHAR), 'Tidak Ada Target') AS progres_tw1,
    COALESCE(CAST(ROUND(AVG(CASE WHEN h.progres_tw2 = 'Tidak Ada Target' OR h.progres_tw2 = '' THEN NULL ELSE CAST(h.progres_tw2 AS DECIMAL(10,2)) END), 2) AS CHAR), 'Tidak Ada Target') AS progres_tw2,
    COALESCE(CAST(ROUND(AVG(CASE WHEN h.progres_tw3 = 'Tidak Ada Target' OR h.progres_tw3 = '' THEN NULL ELSE CAST(h.progres_tw3 AS DECIMAL(10,2)) END), 2) AS CHAR), 'Tidak Ada Target') AS progres_tw3,
    COALESCE(CAST(ROUND(AVG(CASE WHEN h.progres_tw4 = 'Tidak Ada Target' OR h.progres_tw4 = '' THEN NULL ELSE CAST(h.progres_tw4 AS DECIMAL(10,2)) END), 2) AS CHAR), 'Tidak Ada Target') AS progres_tw4,
    COALESCE(CAST(ROUND(AVG(CASE WHEN h.progres_th = 'Tidak Ada Target' OR h.progres_th = '' THEN NULL ELSE CAST(h.progres_th AS DECIMAL(10,2)) END), 2) AS CHAR), 'Tidak Ada Target') AS progres_th,
    
    -- [2] PERSENTASE STATUS DOKUMEN
    ROUND(AVG(CASE WHEN CAST(h.status_dokumen AS UNSIGNED) > 0 THEN 100.0 ELSE 0.0 END), 2) AS penetapan_target,
    ROUND(AVG(CASE WHEN CAST(h.status_dokumen AS UNSIGNED) > 1 THEN 100.0 ELSE 0.0 END), 2) AS realisasi,
    ROUND(AVG(CASE WHEN CAST(h.status_dokumen AS UNSIGNED) > 2 THEN 100.0 ELSE 0.0 END), 2) AS evaluasi,
    ROUND(AVG(CASE WHEN CAST(h.status_dokumen AS UNSIGNED) > 3 THEN 100.0 ELSE 0.0 END), 2) AS tindak_lanjut,
    ROUND(AVG(CASE WHEN CAST(h.status_dokumen AS UNSIGNED) > 4 THEN 100.0 ELSE 0.0 END), 2) AS selesai

FROM 
    jazirah2_hasil h
JOIN 
    jazirah2_indikator i ON h.id_indikator = i.id
WHERE 
    i.pengisian = 1
GROUP BY 
    h.tahun, 
    h.satker, 
    i.kode_2, 
    i.kode_3;