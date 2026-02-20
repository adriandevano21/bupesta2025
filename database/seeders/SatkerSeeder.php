<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SatkerSeeder extends Seeder
{
    public function run()
    {
        DB::table('satkers')->insert([
            ['kode_satker' => '1101', 'nama_satker' => 'BPS Kabupaten Simeulue',],
            ['kode_satker' => '1102', 'nama_satker' => 'BPS Kabupaten Aceh Singkil',],
            ['kode_satker' => '1103', 'nama_satker' => 'BPS Kabupaten Aceh Selatan',],
            ['kode_satker' => '1104', 'nama_satker' => 'BPS Kabupaten Aceh Tenggara',],
            ['kode_satker' => '1105', 'nama_satker' => 'BPS Kabupaten Aceh Timur',],
            ['kode_satker' => '1106', 'nama_satker' => 'BPS Kabupaten Aceh Tengah',],
            ['kode_satker' => '1107', 'nama_satker' => 'BPS Kabupaten Aceh Barat',],
            ['kode_satker' => '1108', 'nama_satker' => 'BPS Kabupaten Aceh Besar',],
            ['kode_satker' => '1109', 'nama_satker' => 'BPS Kabupaten Pidie',],
            ['kode_satker' => '1110', 'nama_satker' => 'BPS Kabupaten Bireuen',],
            ['kode_satker' => '1111', 'nama_satker' => 'BPS Kabupaten Aceh Utara',],
            ['kode_satker' => '1112', 'nama_satker' => 'BPS Kabupaten Aceh Barat Daya',],
            ['kode_satker' => '1113', 'nama_satker' => 'BPS Kabupaten Gayo Lues',],
            ['kode_satker' => '1114', 'nama_satker' => 'BPS Kabupaten Aceh Tamiang',],
            ['kode_satker' => '1115', 'nama_satker' => 'BPS Kabupaten Nagan Raya',],
            ['kode_satker' => '1116', 'nama_satker' => 'BPS Kabupaten Aceh Jaya',],
            ['kode_satker' => '1117', 'nama_satker' => 'BPS Kabupaten Bener Meriah',],
            ['kode_satker' => '1118', 'nama_satker' => 'BPS Kabupaten Pidie Jaya',],
            ['kode_satker' => '1171', 'nama_satker' => 'BPS Kota Banda Aceh',],
            ['kode_satker' => '1172', 'nama_satker' => 'BPS Kota Sabang',],
            ['kode_satker' => '1173', 'nama_satker' => 'BPS Kota Langsa',],
            ['kode_satker' => '1174', 'nama_satker' => 'BPS Kota Lhokseumawe',],
            ['kode_satker' => '1175', 'nama_satker' => 'BPS Kota Subulussalam',],
            ['kode_satker' => '1100', 'nama_satker' => 'BPS Provinsi Aceh',],
        ]);
    }
}
