<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ist_Periode extends Model
{
    protected $table = 'ist_periode';
    protected $fillable = [
        'tahun',
        'is_active',
        'informasi_persiapan',
        'mulai_tahap1_1',
        'akhir_tahap1_1',
        'mulai_tahap1_2',
        'akhir_tahap1_2',
        'mulai_tahap2_1',
        'akhir_tahap2_1',
        'mulai_tahap2_2',
        'akhir_tahap2_2',
        'tanggal_pengumuman'
    ];
}
