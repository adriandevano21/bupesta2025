<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW epss_view_nilai_perdomain AS
            SELECT 
                id, id_kegiatan, id_tahapan, id_usulan_kegiatan,
                (`101` * 0.25) + (`102` * 0.25) + (`103` * 0.25) + (`104` * 0.25) AS `1`,
                (`201` * 0.21) + (`202` * 0.16) + (`203` * 0.21) + (`204` * 0.21) + (`205` * 0.21) AS `2`,
                (`301` * 0.32) + (`302` * 0.26) + (`303` * 0.21) + (`304` * 0.21) AS `3`,
                (`401` * 0.35) + (`402` * 0.30) + (`403` * 0.35) AS `4`,
                (`501` * 0.34) + (`502` * 0.33) + (`503` * 0.33) AS `5`
            FROM epss_view_nilai_peraspek
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS epss_view_nilai_perdomain");
    }
};