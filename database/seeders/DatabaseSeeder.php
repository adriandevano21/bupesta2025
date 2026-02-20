<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BulanSeeder::class,
            JazirahIndikatorSeeder::class,
            RencanaKerjaSeeder::class,
            JazirahUsersSeeder::class,
            SatkerSeeder::class,
            JazirahHasilSeeder::class
        ]);
    }
}
