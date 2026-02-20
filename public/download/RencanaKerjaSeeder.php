<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RencanaKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/rencana_kerjas.csv');
        if (!file_exists($path)) {
            $this->command->error("CSV not found at: {$path}");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error('Unable to open CSV file.');
            return;
        }

        $header = fgetcsv($handle); // read header
        $rows = [];
        $now = now();

        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($header, $data);

            $rows[] = [
                'id'             => (int) ($row['id'] ?? null),
                'kode_1'         => $row['kode_1'] !== '' ? $row['kode_1'] : null,
                'kode_2'         => $row['kode_2'] !== '' ? $row['kode_2'] : null,
                'kode_3'         => $row['kode_3'] !== '' ? $row['kode_3'] : null,
                'kode_4'         => $row['kode_4'] !== '' ? $row['kode_4'] : null,
                'kode_5'         => $row['kode_5'] !== '' ? $row['kode_5'] : null,
                'rencana_kerja'  => $row['rencana_kerja'],
                'level'          => (int) $row['level'],
                'pengisian'      => (int) $row['pengisian'] ? 1 : 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }
        fclose($handle);

        // Optional: clear table first
        DB::table('rencana_kerjas')->truncate();

        // Insert in chunks
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('rencana_kerjas')->insert($chunk);
        }
    }
}
