<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpssReferensiSeeder extends Seeder
{
    public function run()
    {
        $data_referensi = [];

        // 1. Data Domain
        $rawDomain = "1=Prinsip SDI|2=Kualitas Data|3=Proses Bisnis Statistik|4=Kelembagaan|5=Statistik Nasional|";
        
        // 2. Data Aspek
        $rawAspek = "101=Standar Data Statistik|102=Metadata Statistik|103=Interoperabilitas Data|104=Kode Referensi dan/atau Data Induk|201=Relevansi|202=Akurasi|203=Aktualitas & Ketepatan Waktu|204=Aksesibilitas|205=Keterbandingan & Konsistensi|301=Perencanaan Data|302=Pengumpulan Data|303=Pemeriksaan Data|304=Penyebarluasan Data|401=Profesionalitas|402=SDM yang Memadai dan Kapabel|403=Pengorganisasian Statistik|501=Pemanfaatan Data Statistik|502=Pengelolaan Kegiatan Statistik|503=Penguatan SSN Berkelanjutan|";

        // 3. Data Indikator
        $rawIndikator = "10101=Tingkat Kematangan Penerapan Standar Data Statistik (SDS)|10201=Tingkat Kematangan Penerapan Metadata Statistik|10301=Tingkat Kematangan Penerapan Interoperabilitas Data|10401=Tingkat Kematangan Penerapan Kode Referensi|20101=Tingkat Kematangan Relevansi Data Terhadap Pengguna|20102=Tingkat Kematangan Proses Identifikasi Kebutuhan Data|20201=Tingkat Kematangan Penilaian Akurasi Data|20301=Tingkat Kematangan Penjaminan Aktualitas Data|20302=Tingkat Kematangan Pemantauan Ketepatan Waktu Diseminasi|20401=Tingkat Kematangan Ketersediaan Data untuk Pengguna Data|20402=Tingkat Kematangan Akses Media Penyebarluasan Data|20403=Tingkat Kematangan Penyediaan Format Data|20501=Tingkat Kematangan Keterbandingan Data|20502=Tingkat Kematangan Konsistensi Statistik|30101=Tingkat Kematangan Pendefinisian Kebutuhan Statistik|30102=Tingkat Kematangan Desain Statistik|30103=Tingkat Kematangan Penyiapan Instrumen|30201=Tingkat Kematangan Proses Pengumpulan Data / Akuisisi Data|30301=Tingkat Kematangan Pengolahan Data|30302=Tingkat Kematangan Analisis Data|30401=Tingkat Kematangan Diseminasi Data|40101=Tingkat Kematangan Penjaminan Transparansi Informasi Statistik|40102=Tingkat Kematangan Penjaminan Netralitas dan Obyektivitas terhadap penggunaan Sumber Data Metodologi|40103=Tingkat Kematangan Penjaminan Kualitas Data|40104=Tingkat Kematangan Penjaminan Konfidensialitas Data|40201=Tingkat Kematangan Penerapan Kompetensi Sumber Daya Manusia Bidang Statistik|40202=Tingkat Kematangan Penerapan Kompetensi Sumber Daya Manusia Bidang Manajemen Data|40301=Tingkat Kematangan Kolaborasi Penyelenggaraan Kegiatan Statistik|40302=Tingkat Kematangan Penyelenggaraan Forum Satu Data|40303=Tingkat Kematangan Kolaborasi dengan Pembina Data Statistik|40304=Tingkat Kematangan Penyelenggaraan Pelaksanaan Tugas Sebagai Wali Data|50101=Tingkat Kematangan Penggunaan Data Statistik Dasar untuk Perencanaan, Monitoring, dan Evaluasi, dan / atau Penyusunan Kebijakan|50102=Tingkat Kematangan Penggunaan Data Statistik Sektoral untuk Perencanaan, Monitoring, dan Evaluasi, dan / atau Penyusunan Kebijakan|50103=Tingkat Kematangan Sosialisasi dan Literasi Data Statistik|50201=Tingkat Kematangan Pelaksanaan Rekomendasi Kegiatan Statistik|50301=Tingkat Kematangan Perencanaan Pembangunan Statistik|50302=Tingkat Kematangan Penyebarluasan Data|50303=Tingkat Kematangan Pemanfaatan Big Data|";

        // Logic Parser untuk menyusun Array secara otomatis
        $kategori = [
            'domain' => $rawDomain,
            'aspek' => $rawAspek,
            'indikator' => $rawIndikator
        ];

        foreach ($kategori as $tipe => $rawString) {
            $items = explode('|', $rawString);
            foreach ($items as $item) {
                if (trim($item) === '') continue; // Lewati jika kosong (akibat | di akhir text)
                
                $parts = explode('=', $item);
                if (count($parts) == 2) {
                    $data_referensi[] = [
                        'tipe' => $tipe,
                        'kode' => trim($parts[0]),
                        'penjelasan' => trim($parts[1]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert ke database
        DB::table('epss_referensi')->insert($data_referensi);
    }
}