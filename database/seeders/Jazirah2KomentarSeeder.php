<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jazirah2_Komentar;
use Carbon\Carbon;

class Jazirah2KomentarSeeder extends Seeder
{
    public function run(): void
    {
        Jazirah2_Komentar::insert([
            [
                'id_jazirah2_hasil' => 4706, 
                'nip' => '197904222005022001', // Sesuaikan NIP Ketua Tim / Penanggung Jawab
                'komentar' => 'Tolong lengkapi bukti dukung untuk triwulan ini.',
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'id_jazirah2_hasil' => 4706,
                'nip' => '198406272011012022', // Sesuaikan NIP Anda atau anggota lain
                'komentar' => 'Baik, file PDF sudah saya unggah.',
                'created_at' => Carbon::now()->subMinutes(5),
                'updated_at' => Carbon::now()->subMinutes(5),
            ],
        ]);
    }
}