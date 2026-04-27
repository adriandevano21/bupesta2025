<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SE2026Controller extends Controller
{
    public function index(Request $request)
    {
        $data["judul"] = "SE2026";
        $data["satker_selected"] = $request->input('satker', '1100');
        $data["lembarkerja"] = DB::table('se2026_indikator')
            ->leftJoin('se2026_hasil', function ($join) use ($data) {
                // Relasi tabel: indikator.kinerja = hasil.id_indikator
                $join->on('se2026_indikator.kinerja', '=', 'se2026_hasil.id_indikator')
                    // Filter spesifik ke satker pada saat proses join
                    ->where('se2026_hasil.satker', '=', $data["satker_selected"]);
            })
            // 3. Pilih kolom yang ingin ditampilkan untuk menghindari tabrakan nama (opsional tapi disarankan)
            ->select(
                'se2026_indikator.*', // Ambil semua data indikator
                'se2026_hasil.satker',
                'se2026_hasil.peng_Feb',
                'se2026_hasil.app_Feb',
                'se2026_hasil.peng_Mar',
                'se2026_hasil.app_Mar',
                'se2026_hasil.peng_Apr',
                'se2026_hasil.app_Apr',
                'se2026_hasil.peng_Mei',
                'se2026_hasil.app_Mei',
                'se2026_hasil.peng_Jun',
                'se2026_hasil.app_Jun',
                'se2026_hasil.peng_Jul',
                'se2026_hasil.app_Jul',
                'se2026_hasil.peng_Ags',
                'se2026_hasil.app_Ags',
                'se2026_hasil.link_buktidukung',
                'se2026_hasil.pj_approval'
            )
            ->get();
        // dd($data);
        return view('SE2026.se2026-lembarkerja', compact('data'));
    }
}
