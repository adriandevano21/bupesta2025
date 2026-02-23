CREATE OR REPLACE VIEW monitoring_jazirah AS
SELECT 
    h.tahun,
    h.satker,
    i.kode_2,
    i.kode_3,
    -- TW 1
    COALESCE(
        CAST(ROUND(AVG(CASE WHEN h.progres_tw1 = 'Tidak Ada Target' OR h.progres_tw1 = '' THEN NULL ELSE CAST(h.progres_tw1 AS DECIMAL(10,2)) END), 2) AS CHAR), 
        'Tidak Ada Target'
    ) AS progres_tw1,
    -- TW 2
    COALESCE(
        CAST(ROUND(AVG(CASE WHEN h.progres_tw2 = 'Tidak Ada Target' OR h.progres_tw2 = '' THEN NULL ELSE CAST(h.progres_tw2 AS DECIMAL(10,2)) END), 2) AS CHAR), 
        'Tidak Ada Target'
    ) AS progres_tw2,
    -- TW 3
    COALESCE(
        CAST(ROUND(AVG(CASE WHEN h.progres_tw3 = 'Tidak Ada Target' OR h.progres_tw3 = '' THEN NULL ELSE CAST(h.progres_tw3 AS DECIMAL(10,2)) END), 2) AS CHAR), 
        'Tidak Ada Target'
    ) AS progres_tw3,
    -- TW 4
    COALESCE(
        CAST(ROUND(AVG(CASE WHEN h.progres_tw4 = 'Tidak Ada Target' OR h.progres_tw4 = '' THEN NULL ELSE CAST(h.progres_tw4 AS DECIMAL(10,2)) END), 2) AS CHAR), 
        'Tidak Ada Target'
    ) AS progres_tw4,
    -- Tahunan
    COALESCE(
        CAST(ROUND(AVG(CASE WHEN h.progres_th = 'Tidak Ada Target' OR h.progres_th = '' THEN NULL ELSE CAST(h.progres_th AS DECIMAL(10,2)) END), 2) AS CHAR), 
        'Tidak Ada Target'
    ) AS progres_th
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