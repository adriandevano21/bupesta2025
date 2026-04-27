<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SE2026_Indikator extends Model
{
    protected $table = 'se2026_indikator';

    protected $fillable = [
        'kode_1',
        'kode_2',
        'kinerja',
        'kegiatan',
        'uraian_kegaitan',
        'target',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Ags'
    ];
    public function isian()
    {
        // ganti Jazirah2_Isian dengan model transaksi kamu
        return $this->hasOne(SE2026_Hasil::class, 'id_indikator', 'kinerja');
    }
}
