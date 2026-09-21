<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW epss_view_nilai_peraspek AS
            SELECT 
                id, id_kegiatan, id_tahapan, id_usulan_kegiatan,
                (`10101` * 1.00) AS `101`,
                (`10201` * 1.00) AS `102`,
                (`10301` * 1.00) AS `103`,
                (`10401` * 1.00) AS `104`,
                (`20101` * 0.60) + (`20102` * 0.40) AS `201`,
                (`20201` * 1.00) AS `202`,
                (`20301` * 0.50) + (`20302` * 0.50) AS `203`,
                (`20401` * 0.34) + (`20402` * 0.33) + (`20403` * 0.33) AS `204`,
                (`20501` * 0.50) + (`20502` * 0.50) AS `205`,
                (`30101` * 0.33) + (`30102` * 0.33) + (`30103` * 0.34) AS `301`,
                (`30201` * 1.00) AS `302`,
                (`30301` * 0.50) + (`30302` * 0.50) AS `303`,
                (`30401` * 1.00) AS `304`,
                (`40101` * 0.25) + (`40102` * 0.25) + (`40103` * 0.25) + (`40104` * 0.25) AS `401`,
                (`40201` * 0.50) + (`40202` * 0.50) AS `402`,
                (`40301` * 0.25) + (`40302` * 0.25) + (`40303` * 0.25) + (`40304` * 0.25) AS `403`,
                (`50101` * 0.34) + (`50102` * 0.33) + (`50103` * 0.33) AS `501`,
                (`50201` * 1.00) AS `502`,
                (`50301` * 0.33) + (`50302` * 0.33) + (`50303` * 0.34) AS `503`
            FROM epss_nilai
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS epss_view_nilai_peraspek");
    }
};
