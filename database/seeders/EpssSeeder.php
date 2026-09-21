<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpssSeeder extends Seeder
{
    public function run()
    {
        // 1. Seeder EPSS Kegiatan
        $kegiatans = [
            ['id' => 1, 'tahun' => 2024, 'status' => 'Ada'],
            ['id' => 2, 'tahun' => 2026, 'status' => 'Ada'],
        ];
        DB::table('epss_kegiatan')->insert($kegiatans);

        // 2. Seeder EPSS Tahapan Kegiatan
        $tahapans = [
            ['id' => 1, 'id_kegiatan' => 1, 'tahapan' => 'Penilaian TPB', 'tanggal_mulai' => '2024-08-11', 'tanggal_selesai' => '2024-08-11', 'status' => 'Tutup'],
            ['id' => 2, 'id_kegiatan' => 1, 'tahapan' => 'Final', 'tanggal_mulai' => '2024-09-01', 'tanggal_selesai' => '2024-09-26', 'status' => 'Tutup'],
            ['id' => 3, 'id_kegiatan' => 2, 'tahapan' => 'Penilaian Mandiri', 'tanggal_mulai' => '2026-04-27', 'tanggal_selesai' => '2026-06-07', 'status' => 'Tutup'],
            ['id' => 4, 'id_kegiatan' => 2, 'tahapan' => 'Penilaian Dokumen', 'tanggal_mulai' => '2026-06-08', 'tanggal_selesai' => '2026-07-08', 'status' => 'Tutup'],
            ['id' => 5, 'id_kegiatan' => 2, 'tahapan' => 'Penilaian Interviu', 'tanggal_mulai' => '2026-07-08', 'tanggal_selesai' => '2026-07-24', 'status' => 'Tutup'],
            ['id' => 6, 'id_kegiatan' => 2, 'tahapan' => 'Harmonisasi Pleno Provinsi', 'tanggal_mulai' => '2026-09-01', 'tanggal_selesai' => '2026-09-07', 'status' => 'Tutup'],
            ['id' => 7, 'id_kegiatan' => 2, 'tahapan' => 'Harmonisasi Pleno Nasional', 'tanggal_mulai' => '2026-09-10', 'tanggal_selesai' => '2026-09-22', 'status' => 'Buka'],
        ];
        DB::table('epss_tahapan_kegiatan')->insert($tahapans);

        // 3. Seeder EPSS Usulan Kegiatan
        // Kolom dipisah eksplisit untuk menghindari error parsing akibat koma (,) di dalam nama Dinas/Instansi
        $usulans = [
            // Baris 1 - 23 (Kegiatan Identik)
            ...array_map(fn($id, $satker, $penilai) => [
                'id' => $id, 'id_kegiatan' => 1, 'kode_satker' => $satker,
                'kegiatan_1' => 'Kegiatan 1', 'nama_dinas_1' => 'Nama Dinas 1', 'tahun_1' => '2022',
                'kegiatan_2' => 'Kegiatan 2', 'nama_dinas_2' => 'Nama Dinas 2', 'tahun_2' => '2022',
                'kode_satker_penilai' => $penilai
            ], 
            range(1, 23), // id
            ['1101','1102','1103','1104','1105','1106','1107','1108','1109','1110','1111','1112','1113','1114','1115','1116','1117','1118','1171','1172','1173','1174','1175'], // satker
            ['1175','1103','1110','1116','1106','1172','1104','1114','1108','1173','1112','1117','1174','1101','1113','1105','1171','1109','1118','1111','1102','1115','1107'] // penilai
            ),
            
            // Baris 24 - 46 (Kegiatan Nyata)
            ['id' => 24, 'id_kegiatan' => 2, 'kode_satker' => '1101', 'kegiatan_1' => 'Kompilasi Data Informasi Pengelolaan Lingkungan Hidup Daerah', 'nama_dinas_1' => 'Dinas Lingkungan Hidup Kabupaten Simeulue', 'tahun_1' => '2024', 'kegiatan_2' => 'Kompilasi Data Informasi Kepegawaian', 'nama_dinas_2' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia Kabupaten Simeulue', 'tahun_2' => '2024', 'kode_satker_penilai' => '1175'],
            ['id' => 25, 'id_kegiatan' => 2, 'kode_satker' => '1102', 'kegiatan_1' => 'Standar Pelayanan Minimal (SPM)', 'nama_dinas_1' => 'Dinas Kesehatan', 'tahun_1' => '2025', 'kegiatan_2' => 'Pengumpulan data penyusunan Indeks Kualitas Lingkungan Hidup', 'nama_dinas_2' => 'Dinas Lingkungan Hidup dan Kehutanan (DLHK)', 'tahun_2' => '2025', 'kode_satker_penilai' => '1103'],
            ['id' => 26, 'id_kegiatan' => 2, 'kode_satker' => '1103', 'kegiatan_1' => 'Pengumpulan Data Produksi Perikanaan Budidaya Kabupaten Aceh Selatan Tahun 2025', 'nama_dinas_1' => 'Dinas Kelautan dan Perikanan Kabupaten Aceh Selatan', 'tahun_1' => '2025', 'kegiatan_2' => 'Pengumpulan Data Penyandang Disabilitas Kabupaten Aceh Selatan Tahun 2025', 'nama_dinas_2' => 'Dinas Sosial Kabupaten Aceh Selatan', 'tahun_2' => '2025', 'kode_satker_penilai' => '1110'],
            ['id' => 27, 'id_kegiatan' => 2, 'kode_satker' => '1104', 'kegiatan_1' => 'Kompromin Data Luas Tanam dan Panen Hortikultura', 'nama_dinas_1' => 'Dinas Pertanian', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompromin Data Penyusunan Peta Ketahanan dan Kerentanan Pangan', 'nama_dinas_2' => 'Dinas Pangan', 'tahun_2' => '2025', 'kode_satker_penilai' => '1116'],
            ['id' => 28, 'id_kegiatan' => 2, 'kode_satker' => '1105', 'kegiatan_1' => 'PROFIL PERKEMBANGAN KEPENDUDUKAN KABUPATEN ACEH TIMUR TAHUN 2023 Tahun 2024', 'nama_dinas_1' => 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Aceh Timur', 'tahun_1' => '2025', 'kegiatan_2' => 'Peta Dan Analisis Ketahanan Dan Kerentanan Pangan Tahun 2026', 'nama_dinas_2' => 'Dinas Ketahanan Pangan dan Penyuluhan Kabupaten Aceh Timur', 'tahun_2' => '2025', 'kode_satker_penilai' => '1106'],
            ['id' => 29, 'id_kegiatan' => 2, 'kode_satker' => '1106', 'kegiatan_1' => 'Profil Kesehatan Kabupaten Aceh Tengah Tahun 2025', 'nama_dinas_1' => 'Dinas Kesehatan Kabupaten Aceh Tengah', 'tahun_1' => '2024', 'kegiatan_2' => 'Kajian Resiko Bencana Kabupaten Aceh Tengah Tahun 2025-2029', 'nama_dinas_2' => 'Badan Penanggulangan Bencana Daerah Kabupaten Aceh Tengah', 'tahun_2' => '2025', 'kode_satker_penilai' => '1172'],
            ['id' => 30, 'id_kegiatan' => 2, 'kode_satker' => '1107', 'kegiatan_1' => 'Kompilasi Profil Perkembangan Kependudukan Kabupaten Aceh Barat', 'nama_dinas_1' => 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Aceh Barat', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompilasi Profil Kesehatan Kabupaten Aceh Barat', 'nama_dinas_2' => 'Dinas Kesehatan Kabupaten Aceh Barat', 'tahun_2' => '2025', 'kode_satker_penilai' => '1104'],
            ['id' => 31, 'id_kegiatan' => 2, 'kode_satker' => '1108', 'kegiatan_1' => 'Kompilasi Data Statistik Pencari Kerja Terdaftar Kabupaten Aceh Besar', 'nama_dinas_1' => 'Dinas Tenaga Kerja dan Transmigrasi Kabupaten Aceh Besar', 'tahun_1' => '2025', 'kegiatan_2' => 'Pendataan Penduduk Fakir dan Miskin Kabupaten Aceh Besar', 'nama_dinas_2' => 'Baitul Mal Kabupaten Aceh Besar', 'tahun_2' => '2024', 'kode_satker_penilai' => '1114'],
            ['id' => 32, 'id_kegiatan' => 2, 'kode_satker' => '1109', 'kegiatan_1' => 'Pendataan Produksi Perikanan Tangkap Kabupaten Pidie', 'nama_dinas_1' => 'Dinas Kelautan dan Perikanan Kabupaten Pidie', 'tahun_1' => '2025', 'kegiatan_2' => 'Pendataan Pembangunan Rumah Layak Huni Kabupaten Pidie', 'nama_dinas_2' => 'Dinas Perumahan dan Kawasan Pemukiman Kabupaten Pidie', 'tahun_2' => '2025', 'kode_satker_penilai' => '1108'],
            ['id' => 33, 'id_kegiatan' => 2, 'kode_satker' => '1110', 'kegiatan_1' => 'Profil Kesehatan Kabupaten Bireuen', 'nama_dinas_1' => 'Dinas Kesehatan Kabupaten Bireuen', 'tahun_1' => '2025', 'kegiatan_2' => 'Pemutakhiran Pendataan Keluarga Tahun 2025', 'nama_dinas_2' => 'Dinas Pemberdayaan Masyarakat Gampong, Perempuan dan Keluarga Berencana (DPMGPKB) Kabupaten Bireuen', 'tahun_2' => '2025', 'kode_satker_penilai' => '1173'],
            ['id' => 34, 'id_kegiatan' => 2, 'kode_satker' => '1111', 'kegiatan_1' => 'Survei Data Barang Kebutuhan Pokok dan Penting di Kabupaten Aceh Utara', 'nama_dinas_1' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM Kabupaten Aceh Utara', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompilasi Data Peta Ketahanan dan Keretanan Pangan/Food Security and Vulnerability Atlas (FSVA) Kabupaten Aceh Utara', 'nama_dinas_2' => 'Dinas Pertanian dan Pangan Kabupaten Aceh Utara', 'tahun_2' => '2025', 'kode_satker_penilai' => '1112'],
            ['id' => 35, 'id_kegiatan' => 2, 'kode_satker' => '1112', 'kegiatan_1' => 'Pengumpulan Data Harga Bahan Pokok Kabupaten Aceh Barat Daya', 'nama_dinas_1' => 'Dinas Koperasi, UKM, Perindustrian, dan Perdagangan (DiskopUKMPerindag) Kabupaten Aceh Barat Daya', 'tahun_1' => '2025', 'kegiatan_2' => 'Pengumpulan Data Indeks Desa Kabupaten Aceh Barat Daya Tahun 2025', 'nama_dinas_2' => 'Dinas Pemberdayaan Masyarakat Pengendalian Penduduk dan Pemberdayaan Perempuan (DPMP4) Kabupaten Aceh Barat Daya', 'tahun_2' => '2025', 'kode_satker_penilai' => '1117'],
            ['id' => 36, 'id_kegiatan' => 2, 'kode_satker' => '1113', 'kegiatan_1' => 'Kompilasi Data Penyusunan Publikasi Profil Kesehatan Kabupaten Gayo Lues, Tahun 2025', 'nama_dinas_1' => 'Ernawati kasra, S.Kep', 'tahun_1' => '2025', 'kegiatan_2' => 'Penyusunan Database Pariwisata Kabupaten Gayo Lues, Tahun 2025', 'nama_dinas_2' => 'Maharami, S.S., M.AP', 'tahun_2' => '2025', 'kode_satker_penilai' => '1174'],
            ['id' => 37, 'id_kegiatan' => 2, 'kode_satker' => '1114', 'kegiatan_1' => 'Kompilasi Data Penyusunan Profil Kependudukan Kabupaten Aceh Tamiang', 'nama_dinas_1' => 'Dinas Kependudukan Dan Kecatatan Sipil Kabupaten Aceh Tamiang', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompilasi Data Pendistribusian dan Pendayagunaan Ziswaf', 'nama_dinas_2' => 'Sekretariat Baitul Mal Kabupaten Aceh Tamiang', 'tahun_2' => '2025', 'kode_satker_penilai' => '1101'],
            ['id' => 38, 'id_kegiatan' => 2, 'kode_satker' => '1115', 'kegiatan_1' => 'Profil Anak Kabupaten Nagan Raya Tahun 2025', 'nama_dinas_1' => 'Dinas Pemberdayaan Masyarakat Gampong Pengendalian Penduduk dan Pemberdayaan Perempuan (DPMGP4) Kabupaten Nagan Raya', 'tahun_1' => '2025', 'kegiatan_2' => 'Pengelolahan Data Persampahan Kabupaten Nagan Raya', 'nama_dinas_2' => 'Dinas Lingkungan Hidup (DLH) Kabupaten Nagan Raya', 'tahun_2' => '2025', 'kode_satker_penilai' => '1113'],
            ['id' => 39, 'id_kegiatan' => 2, 'kode_satker' => '1116', 'kegiatan_1' => 'Kompilasi Produk Administrasi Database Dinas Perhubungan dan Pertanahan Kabupaten Aceh Jaya', 'nama_dinas_1' => 'Dinas Perhubungan dan Pertanahan Kabupaten Aceh Jaya', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompilasi Produk Administrasi Data Statistik Sektoral Daerah Pemerintah Daerah Aceh Jaya Pada E-walidata', 'nama_dinas_2' => 'Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah Kabupaten Aceh Jaya', 'tahun_2' => '2025', 'kode_satker_penilai' => '1105'],
            ['id' => 40, 'id_kegiatan' => 2, 'kode_satker' => '1117', 'kegiatan_1' => 'Pemantauan Harga dan Stok Barang Kebutuhan Pokok dan Barang Penting di Tingkat Kabupaten/Kota', 'nama_dinas_1' => 'Dinas Perdagangan Kabupaten Bener Meriah', 'tahun_1' => '2025', 'kegiatan_2' => 'Pelayanan Kesehatan Gizi Masyarakat', 'nama_dinas_2' => 'Dinas Kesehatan Kabupaten Bener Meriah', 'tahun_2' => '2024', 'kode_satker_penilai' => '1171'],
            ['id' => 41, 'id_kegiatan' => 2, 'kode_satker' => '1118', 'kegiatan_1' => 'Kompilasi Profil Kependudukan Kabupaten Pidie Jaya Tahun 2025', 'nama_dinas_1' => 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten Pidie Jaya', 'tahun_1' => '2025', 'kegiatan_2' => 'Pengumpulan data produksi perikanan tangkap tahun 2025', 'nama_dinas_2' => 'Dinas Kelautan dan Perikanan Kabupaten Pidie Jaya', 'tahun_2' => '2025', 'kode_satker_penilai' => '1109'],
            ['id' => 42, 'id_kegiatan' => 2, 'kode_satker' => '1171', 'kegiatan_1' => 'Pendataan Statistik Perikanan Tangkap', 'nama_dinas_1' => 'Dinas Pangan, Pertanian, Kelautan dan Perikanan Kota Banda Aceh', 'tahun_1' => '2024', 'kegiatan_2' => 'Kompilasi Profil Kesehatan Kota Banda Aceh', 'nama_dinas_2' => 'Dinas Kesehatan Kota Banda Aceh', 'tahun_2' => '2024', 'kode_satker_penilai' => '1118'],
            ['id' => 43, 'id_kegiatan' => 2, 'kode_satker' => '1172', 'kegiatan_1' => 'Kompilasi Produk Administrasi Penyusunan Peta Ketahanan dan Kerentanan Pangan (FSVA)', 'nama_dinas_1' => 'Dinas Pertanian dan Pangan Kota Sabang', 'tahun_1' => '2025', 'kegiatan_2' => 'Survey Indeks Kualitas Lingkungan Hidup (IKLH)', 'nama_dinas_2' => 'Dinas Lingkungan Hidup dan Kebersihan Kota Sabang', 'tahun_2' => '2025', 'kode_satker_penilai' => '1111'],
            ['id' => 44, 'id_kegiatan' => 2, 'kode_satker' => '1173', 'kegiatan_1' => 'kompilasi profil kesehatan kota langsa', 'nama_dinas_1' => 'dinas kesehatan kota langsa', 'tahun_1' => '2024', 'kegiatan_2' => 'Kompilasi Karakteristik Potensi Sektor Unggulan Kota Langsa', 'nama_dinas_2' => 'badan perencanaan pembangunan daerah kota langsa', 'tahun_2' => '2024', 'kode_satker_penilai' => '1102'],
            ['id' => 45, 'id_kegiatan' => 2, 'kode_satker' => '1174', 'kegiatan_1' => 'Kompilasi Data Kesehatan Kota Lhokseumawe', 'nama_dinas_1' => 'Dinas Kesehatan Kota Lhokseumawe', 'tahun_1' => '2025', 'kegiatan_2' => 'Kompilasi Data Kependudukan Kota Lhokseumawe', 'nama_dinas_2' => 'Dinas Kependudukan dan Pencatatan sipil Kota Lhokseumawe', 'tahun_2' => '2025', 'kode_satker_penilai' => '1115'],
            ['id' => 46, 'id_kegiatan' => 2, 'kode_satker' => '1175', 'kegiatan_1' => 'Kompilasi Produk Administrasi Profil Kependudukan Kota Subulussalam', 'nama_dinas_1' => 'Disdukcapil Kota Subulussalam', 'tahun_1' => '2024', 'kegiatan_2' => 'Kompilasi Produk Administrasi Survei Studi Environmental Health Risk Assessment (EHRA)/Studi Penilaian Risiko Kesehatan Kota Subulussalam', 'nama_dinas_2' => 'Bappeda Kota Subulussalam', 'tahun_2' => '2024', 'kode_satker_penilai' => '1107']
        ];
        DB::table('epss_usulan_kegiatan')->insert($usulans);

        // 4. Seeder EPSS Nilai (Menggunakan mapping kolom otomatis agar script tidak kepanjangan)
        $kolom_nilai = [
            'id','id_kegiatan','id_tahapan','id_usulan_kegiatan',
            '10101','10201','10301','10401',
            '20101','20102','20201','20301','20302','20401','20402','20403','20501','20502',
            '30101','30102','30103','30201','30301','30302','30401',
            '40101','40102','40103','40104','40201','40202','40301','40302','40303','40304',
            '50101','50102','50103','50201','50301','50302','50303'
        ];

        // Memanfaatkan string parser untuk data masif Anda
        $raw_nilai = "
        1, 1, 2, 1, 1, 3, 3, 3, 3, 1, 3, 3, 1, 1, 3, 3, 3, 1, 3, 3, 3, 3, 3, 1, 1, 3, 3, 1, 1, 2, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1
        2, 1, 2, 2, 1, 3, 2, 2, 2, 2, 2, 2, 2, 1, 3, 2, 2, 2, 3, 3, 3, 3, 3, 2, 3, 3, 1, 1, 2, 2, 1, 2, 2, 2, 2, 1, 1, 2, 2, 2, 3, 1
        3, 1, 2, 3, 1, 1, 3, 1, 2, 1, 3, 1, 1, 3, 3, 3, 1, 1, 3, 3, 3, 3, 1, 1, 3, 1, 1, 1, 3, 1, 2, 3, 2, 1, 2, 2, 2, 1, 2, 2, 3, 1
        4, 1, 2, 4, 1, 3, 3, 3, 3, 1, 1, 1, 1, 3, 3, 3, 3, 1, 1, 1, 3, 1, 1, 1, 3, 1, 1, 1, 3, 2, 2, 3, 1, 3, 2, 2, 3, 1, 1, 2, 3, 1
        5, 1, 2, 5, 3, 3, 3, 3, 3, 2, 1, 3, 3, 1, 2, 1, 3, 1, 3, 2, 3, 2, 1, 2, 2, 3, 3, 3, 3, 2, 2, 3, 3, 3, 2, 3, 3, 1, 3, 3, 3, 1
        6, 1, 2, 6, 3, 1, 3, 1, 3, 3, 3, 2, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 2, 2, 1, 3, 2, 2, 3, 3, 3, 2, 3, 3, 1, 1, 2, 2, 1
        7, 1, 2, 7, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2
        8, 1, 2, 8, 3, 3, 3, 3, 2, 3, 2, 3, 2, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 1, 1, 1, 3, 3, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 1
        9, 1, 2, 9, 1, 3, 3, 2, 2, 1, 1, 2, 1, 1, 1, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 1, 1, 1, 2, 1, 3, 3, 1, 2, 2, 3, 3, 1, 1, 2, 3, 1
        10, 1, 2, 10, 3, 2, 3, 3, 3, 2, 1, 3, 2, 3, 3, 3, 3, 1, 2, 2, 2, 1, 1, 3, 3, 2, 3, 3, 3, 3, 3, 2, 2, 2, 2, 3, 3, 3, 3, 2, 3, 1
        11, 1, 2, 11, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        12, 1, 2, 12, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3
        13, 1, 2, 13, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3
        14, 1, 2, 14, 1, 1, 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, 1, 1, 1, 1, 3, 2, 1, 1, 1, 3, 1, 3, 1
        15, 1, 2, 15, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 3, 2, 2, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 1
        16, 1, 2, 16, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 1, 3, 3, 3, 4, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 1, 1, 3, 1
        17, 1, 2, 17, 1, 1, 3, 1, 3, 1, 3, 3, 3, 3, 1, 3, 3, 1, 1, 3, 3, 3, 3, 1, 3, 1, 1, 3, 3, 1, 1, 3, 2, 3, 2, 3, 3, 1, 1, 1, 3, 1
        18, 1, 2, 18, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 1
        19, 1, 2, 19, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 2
        20, 1, 2, 20, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 1, 3, 3, 2, 3, 3, 3, 3, 3, 2, 2, 3, 3, 3, 2, 2, 3, 3, 2, 2, 1, 1, 1, 2, 1
        21, 1, 2, 21, 3, 4, 3, 3, 3, 2, 3, 4, 2, 2, 3, 3, 4, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 4
        22, 1, 2, 22, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        23, 1, 2, 23, 1, 3, 3, 3, 3, 1, 1, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 1, 3, 2, 1, 3, 1, 3, 2, 3, 3, 2, 3, 1, 3, 1
        24, 2, 3, 24, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        25, 2, 3, 25, 3, 4, 3, 3, 5, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 3, 3, 3, 3, 3, 3, 4, 2, 3, 4, 4, 4, 4, 2, 3, 4, 3
        26, 2, 3, 26, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 4, 4, 4, 5, 5, 5, 4, 4, 4, 4, 1
        27, 2, 3, 27, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 2, 3, 1, 3, 2, 3, 1
        28, 2, 3, 28, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
        29, 2, 3, 29, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 4, 5, 4, 5, 5, 4, 4, 4, 4
        30, 2, 3, 30, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 4, 4, 3, 3, 4, 3, 2
        31, 2, 3, 31, 3, 3, 5, 5, 3, 3, 3, 3, 3, 5, 5, 5, 5, 3, 3, 3, 3, 3, 3, 3, 5, 5, 5, 5, 5, 3, 3, 3, 3, 5, 5, 3, 3, 3, 3, 3, 5, 5
        32, 2, 3, 32, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 5
        33, 2, 3, 33, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 3, 4, 4, 4, 3, 4, 4, 4, 3, 3, 3, 4, 3, 4, 1
        34, 2, 3, 34, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 1
        35, 2, 3, 35, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3
        36, 2, 3, 36, 4, 4, 4, 4, 3, 3, 3, 3, 3, 4, 4, 4, 4, 3, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 3, 4, 4, 4, 3
        37, 2, 3, 37, 2, 2, 3, 3, 3, 2, 4, 2, 3, 3, 3, 2, 1, 3, 2, 5, 3, 3, 2, 2, 2, 2, 1, 2, 1, 1, 1, 3, 2, 3, 4, 1, 2, 1, 3, 1, 3, 1
        38, 2, 3, 38, 4, 4, 4, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 4, 5, 4, 4, 5, 4, 4, 4, 5, 5, 3
        39, 2, 3, 39, 5, 4, 4, 5, 5, 4, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
        40, 2, 3, 40, 3, 4, 5, 3, 4, 4, 4, 4, 4, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 4, 3, 4, 4, 3, 4, 4, 4, 3, 4, 4, 5, 4, 1, 5, 1
        41, 2, 3, 41, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 4, 3, 3, 3, 3, 4, 3, 3, 4, 4, 3, 3, 4, 4, 3
        42, 2, 3, 42, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 4, 5, 5, 4, 4, 1, 5, 4, 4
        43, 2, 3, 43, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 1, 2, 3, 2
        44, 2, 3, 44, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2
        45, 2, 3, 45, 4, 4, 4, 4, 3, 3, 4, 4, 3, 3, 4, 4, 4, 3, 4, 3, 4, 4, 4, 4, 3, 3, 3, 3, 4, 4, 4, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4
        46, 2, 3, 46, 3, 3, 5, 3, 3, 3, 4, 3, 3, 5, 5, 5, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 4, 5, 3, 5, 5, 5, 5, 3, 4, 1
        47, 2, 5, 24, 3, 3, 3, 3, 3, 2, 3, 3, 1, 1, 3, 3, 3, 1, 3, 3, 3, 3, 3, 1, 1, 3, 3, 1, 1, 2, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1
        48, 2, 5, 25, 1, 3, 2, 2, 2, 2, 2, 2, 2, 1, 3, 2, 2, 2, 3, 3, 3, 3, 3, 2, 3, 3, 1, 1, 2, 2, 1, 2, 2, 1, 2, 1, 1, 2, 2, 2, 3, 1
        49, 2, 5, 26, 1, 1, 3, 1, 2, 3, 3, 3, 1, 3, 3, 3, 1, 1, 1, 1, 3, 3, 1, 1, 3, 1, 1, 1, 1, 1, 1, 3, 2, 1, 2, 2, 2, 1, 2, 2, 3, 1
        50, 2, 5, 27, 1, 1, 3, 3, 1, 1, 3, 1, 1, 3, 3, 3, 3, 1, 1, 1, 3, 1, 1, 1, 3, 1, 1, 1, 3, 2, 2, 3, 1, 3, 2, 3, 3, 1, 1, 2, 3, 1
        51, 2, 5, 28, 3, 3, 2, 1, 1, 1, 1, 2, 3, 1, 2, 1, 2, 1, 2, 1, 2, 2, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 2, 2, 3, 2, 3, 1
        52, 2, 5, 29, 3, 1, 3, 1, 3, 3, 1, 1, 1, 1, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 2, 2, 2, 1, 2, 2, 2, 3, 3, 3, 2, 3, 3, 2, 1, 2, 3, 1
        53, 2, 5, 30, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2
        54, 2, 5, 31, 3, 3, 3, 3, 2, 3, 2, 2, 2, 2, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 2, 3, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 1
        55, 2, 5, 32, 1, 3, 3, 2, 2, 1, 1, 2, 1, 1, 1, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 1, 1, 1, 1, 1, 3, 3, 1, 2, 2, 3, 3, 1, 1, 2, 3, 1
        56, 2, 5, 33, 3, 2, 3, 3, 3, 1, 1, 1, 1, 2, 2, 1, 1, 1, 2, 2, 2, 2, 1, 1, 2, 2, 3, 3, 3, 3, 3, 2, 2, 2, 2, 3, 3, 2, 3, 2, 3, 1
        57, 2, 5, 34, 4, 4, 4, 4, 4, 4, 4, 4, 1, 4, 4, 2, 4, 1, 4, 4, 4, 2, 4, 4, 4, 3, 3, 3, 3, 1, 1, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1
        58, 2, 5, 35, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3
        59, 2, 5, 36, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3
        60, 2, 5, 37, 1, 1, 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 3, 1, 1, 1, 1, 1, 1, 1, 3, 2, 1, 1, 1, 3, 1, 3, 1
        61, 2, 5, 38, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 2, 3, 1, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 3, 2, 2, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 1
        62, 2, 5, 39, 4, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 1, 1, 3, 4, 3, 3, 3, 3, 4, 3, 3, 3, 1
        63, 2, 5, 40, 1, 1, 3, 1, 3, 1, 3, 3, 3, 3, 1, 3, 3, 1, 1, 3, 3, 3, 3, 1, 3, 1, 1, 3, 3, 1, 1, 3, 2, 3, 2, 3, 3, 1, 1, 1, 3, 1
        64, 2, 5, 41, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 3, 3, 3, 3, 3, 3, 3, 3, 2
        65, 2, 5, 42, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 4, 4, 4, 4, 4, 3, 3, 4, 3, 2
        66, 2, 5, 43, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 1, 3, 1, 2, 1, 2, 3, 1, 3, 2, 2, 3, 3, 3, 1, 2, 3, 3, 2, 2, 1, 1, 1, 2, 1
        67, 2, 5, 44, 3, 4, 3, 3, 3, 2, 3, 4, 2, 2, 3, 3, 4, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 4
        68, 2, 5, 45, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 3, 3, 3, 3, 1
        69, 2, 5, 46, 1, 1, 3, 3, 3, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 1, 3, 2, 1, 3, 1, 3, 2, 3, 3, 2, 3, 1, 3, 1
        70, 2, 6, 24, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 1, 1, 2, 2, 3, 1, 3, 3, 3, 2, 1, 3, 2, 2, 3, 2, 3, 3, 3, 3, 3, 3, 1, 3, 1
        71, 2, 6, 25, 1, 3, 3, 1, 1, 1, 1, 1, 1, 2, 3, 2, 1, 2, 3, 1, 3, 3, 1, 1, 3, 1, 1, 1, 2, 2, 1, 2, 1, 2, 2, 2, 2, 2, 3, 1, 3, 1
        72, 2, 6, 26, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        73, 2, 6, 27, 1, 1, 2, 1, 2, 1, 1, 1, 1, 2, 2, 1, 1, 1, 2, 2, 1, 1, 1, 2, 1, 1, 1, 2, 1, 1, 1, 2, 2, 2, 2, 1, 1, 1, 1, 1, 2, 1
        74, 2, 6, 28, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 1, 1, 3, 2, 3, 3, 2, 3, 3, 1, 2, 3, 1
        75, 2, 6, 29, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        76, 2, 6, 30, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 2, 3, 2, 3, 3, 3, 3, 2, 1, 2, 3, 1
        77, 2, 6, 31, 3, 3, 3, 3, 3, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 1, 3, 2, 3, 3, 3, 2, 3, 3, 1
        78, 2, 6, 32, 3, 3, 3, 3, 3, 3, 3, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1
        79, 2, 6, 33, 2, 2, 3, 3, 1, 2, 1, 2, 1, 3, 3, 2, 1, 1, 1, 1, 1, 2, 2, 1, 3, 3, 2, 1, 3, 3, 3, 1, 1, 3, 1, 2, 2, 1, 1, 1, 3, 1
        80, 2, 6, 34, 3, 3, 3, 3, 2, 2, 2, 2, 2, 2, 3, 3, 2, 2, 2, 2, 2, 3, 2, 2, 3, 1, 3, 2, 3, 2, 3, 2, 1, 2, 2, 3, 3, 2, 1, 1, 3, 1
        81, 2, 6, 35, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 2
        82, 2, 6, 36, 3, 3, 3, 3, 3, 2, 3, 2, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 1, 2, 3, 1
        83, 2, 6, 37, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 1, 2, 3, 2, 2, 3, 2, 3, 3, 3, 3, 2, 1, 2, 3, 1
        84, 2, 6, 38, 3, 2, 3, 3, 2, 2, 2, 2, 2, 2, 3, 2, 2, 2, 2, 2, 2, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 1
        85, 2, 6, 39, 3, 2, 3, 3, 3, 3, 3, 2, 3, 3, 3, 3, 3, 2, 2, 2, 3, 3, 3, 2, 3, 2, 2, 3, 3, 2, 3, 3, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1
        86, 2, 6, 40, 3, 3, 3, 3, 3, 2, 1, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 3, 1
        87, 2, 6, 41, 3, 1, 3, 3, 3, 1, 1, 1, 1, 3, 3, 3, 3, 3, 1, 1, 1, 1, 3, 1, 3, 1, 1, 1, 3, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 3, 1
        88, 2, 6, 42, 2, 2, 3, 3, 3, 3, 3, 2, 2, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 3, 3, 3, 1, 3, 3, 3, 1, 4, 3, 3, 2, 2, 2, 1, 3, 3, 1
        89, 2, 6, 43, 3, 2, 3, 3, 1, 1, 1, 3, 2, 3, 3, 2, 2, 1, 1, 2, 2, 3, 3, 3, 3, 3, 3, 1, 1, 3, 3, 1, 1, 1, 3, 2, 2, 1, 1, 1, 3, 1
        90, 2, 6, 44, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 2, 3, 2
        91, 2, 6, 45, 3, 2, 2, 1, 3, 1, 1, 1, 1, 2, 2, 3, 3, 1, 1, 1, 1, 1, 3, 3, 3, 1, 1, 1, 1, 1, 1, 2, 1, 1, 2, 2, 2, 1, 1, 1, 2, 1
        92, 2, 6, 46, 3, 3, 3, 3, 1, 1, 1, 1, 1, 3, 3, 1, 1, 1, 3, 3, 3, 3, 1, 3, 3, 3, 3, 3, 3, 1, 1, 3, 1, 3, 2, 2, 2, 2, 1, 1, 3, 1
        ";

        $lines = explode("\n", trim($raw_nilai));
        $data_nilai = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $values = array_map('trim', explode(',', $line));
            if (count($values) === count($kolom_nilai)) {
                $data_nilai[] = array_combine($kolom_nilai, $values);
            }
        }
        DB::table('epss_nilai')->insert($data_nilai);
    }
}