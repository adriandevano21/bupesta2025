<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JazirahMenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            ['title' => 'New Lembar Kerja', 'url' => url('/jazirah-lembarkerja'), 'bg' => 'linear-gradient(135deg, #3b82f6, #06b6d4)', 'icon' => '<i class="fa-solid fa-file-lines"></i>'],
            ['title' => 'Seulanga', 'url' => url('/seulanga'), 'bg' => 'linear-gradient(135deg, #fbbf24, #eab308)', 'icon' => '<i class="fa-solid fa-star"></i>'],
            ['title' => 'Rangkuman', 'url' => url('/rangkuman'), 'bg' => 'linear-gradient(135deg, #6366f1, #2563eb)', 'icon' => '<i class="fa-solid fa-chart-pie"></i>'],
            ['title' => 'Pengisian Matriks Aksi', 'url' => url('/matriks-aksi'), 'bg' => 'linear-gradient(135deg, #a855f7, #6366f1)', 'icon' => '<i class="fa-solid fa-pen-to-square"></i>'],
            ['title' => 'Pedoman ZI', 'url' => url('/pedoman-zi'), 'bg' => 'linear-gradient(135deg, #34d399, #14b8a6)', 'icon' => '<i class="fa-solid fa-book"></i>'],
            ['title' => 'SOP', 'url' => url('/sop'), 'bg' => 'linear-gradient(135deg, #fb7185, #ef4444)', 'icon' => '<i class="fa-solid fa-file-shield"></i>'],
            ['title' => 'LHE TPP ZI 2024', 'url' => url('/lhe-tpp-2024'), 'bg' => 'linear-gradient(135deg, #d946ef, #9333ea)', 'icon' => '<i class="fa-solid fa-award"></i>'],
            ['title' => 'LKE Satker 2024', 'url' => url('/lke-satker-2024'), 'bg' => 'linear-gradient(135deg, #fb923c, #c2410c)', 'icon' => '<i class="fa-solid fa-clipboard-check"></i>'],
            ['title' => 'Event Jazirah', 'url' => url('/jazirah/event'), 'bg' => 'linear-gradient(135deg, #22d3ee, #3b82f6)', 'icon' => '<i class="fa-solid fa-calendar-days"></i>'],
            ['title' => 'Satker Lolos TPI', 'url' => url('/satker-tpi'), 'bg' => 'linear-gradient(135deg, #4ade80, #059669)', 'icon' => '<i class="fa-solid fa-circle-check"></i>'],
            ['title' => 'QNA', 'url' => url('/qna'), 'bg' => 'linear-gradient(135deg, #38bdf8, #06b6d4)', 'icon' => '<i class="fa-solid fa-circle-question"></i>'],
            ['title' => 'Narahubung', 'url' => url('/narahubung'), 'bg' => 'linear-gradient(135deg, #2dd4bf, #10b981)', 'icon' => '<i class="fa-solid fa-headset"></i>'],
        ];

        foreach ($menus as $index => $menu) {
            $menu['urutan'] = $index + 1; // Urutkan berdasarkan posisi index
            DB::table('jazirah_menus')->insert($menu);
        }
    }
}