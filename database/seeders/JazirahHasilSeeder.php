<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JazirahHasilSeeder extends Seeder
{
    public function run(): void
    {
        // (opsional) naikin limit memori sedikit
        ini_set('memory_limit', '10240M');

        // ====== SESUAIKAN DI SINI ======
        $table = 'jazirah2_hasil';                // nama tabel di database
        $path  = database_path('seeders/data/jazirah2_hasil.csv'); // lokasi CSV
        // =================================

        if (! file_exists($path)) {
            throw new \RuntimeException("File CSV tidak ditemukan: {$path}");
        }

        // Kalau mau mulai dari tabel kosong:
        DB::table($table)->truncate();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Gagal membuka file: {$path}");
        }

        // ====== BACA HEADER (BARIS PERTAMA) ======
        $rawHeader = fgetcsv($handle);
        if ($rawHeader === false) {
            fclose($handle);
            throw new \RuntimeException("Header CSV kosong");
        }

        // Rapikan header: trim & buang BOM di kolom pertama
        $header = [];
        foreach ($rawHeader as $col) {
            $col = trim($col);
            // hilangkan BOM kalau ada di awal string
            $col = preg_replace('/^\xEF\xBB\xBF/', '', $col);
            $header[] = $col;
        }

        // Kalau mau cek:
        // dd($header);

        $batch     = [];
        $batchSize = 200; // hanya simpan 200 baris sekali, supaya irit memori

        // ====== BACA BARIS DATA SATU-SATU ======
        while (($row = fgetcsv($handle)) !== false) {

            // Skip baris benar-benar kosong
            if ($row === [null] || $row === [] || $row === false) {
                continue;
            }

            // Samakan panjang $row dengan $header (biar array indexnya aman)
            $headerCount = count($header);
            $rowCount    = count($row);

            if ($rowCount < $headerCount) {
                // kalau kurang kolom, tambal dengan null
                $row = array_pad($row, $headerCount, null);
            } elseif ($rowCount > $headerCount) {
                // kalau lebih kolom, potong sisanya
                $row = array_slice($row, 0, $headerCount);
            }

            // Buat array asosiatif: kolom => nilai
            $assoc = [];
            foreach ($header as $i => $colName) {
                $value = isset($row[$i]) ? (string) $row[$i] : null;
                $value = trim($value);
                if ($value === '') {
                    $value = null;
                }
                $assoc[$colName] = $value;
            }

            $batch[] = $assoc;

            // Insert per batch untuk hemat memori
            if (count($batch) >= $batchSize) {
                DB::table($table)->insert($batch);
                $batch = []; // kosongkan lagi supaya memori lepas
            }
        }

        // Sisa batch terakhir
        if (! empty($batch)) {
            DB::table($table)->insert($batch);
        }

        fclose($handle);
    }
}
