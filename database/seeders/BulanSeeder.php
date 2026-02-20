<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BulanSeeder extends Seeder
{
    public function run()
    {
        DB::table('bulan')->insert([
            ['kode_bulan' => '1', 'nama_bulan' => 'Januari', 'singkatan' => 'Jan',],
            ['kode_bulan' => '2', 'nama_bulan' => 'Februari', 'singkatan' => 'Feb',],
            ['kode_bulan' => '3', 'nama_bulan' => 'Maret', 'singkatan' => 'Mar',],
            ['kode_bulan' => '4', 'nama_bulan' => 'April', 'singkatan' => 'Apr',],
            ['kode_bulan' => '5', 'nama_bulan' => 'Mei', 'singkatan' => 'Mei',],
            ['kode_bulan' => '6', 'nama_bulan' => 'Juni', 'singkatan' => 'Jun',],
            ['kode_bulan' => '7', 'nama_bulan' => 'Juli', 'singkatan' => 'Jul',],
            ['kode_bulan' => '8', 'nama_bulan' => 'Agustus', 'singkatan' => 'Agt',],
            ['kode_bulan' => '9', 'nama_bulan' => 'September', 'singkatan' => 'Sep',],
            ['kode_bulan' => '10', 'nama_bulan' => 'Oktober', 'singkatan' => 'Okt',],
            ['kode_bulan' => '11', 'nama_bulan' => 'November', 'singkatan' => 'Nov',],
            ['kode_bulan' => '12', 'nama_bulan' => 'Desember', 'singkatan' => 'Des',],
        ]);
    }
}
