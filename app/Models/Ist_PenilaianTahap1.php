<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ist_PenilaianTahap1 extends Model
{
    protected $table = 'ist_penilaian_tahap1';
    protected $fillable = ['periode_id', 'nip_pemilih', 'nip_kandidat', 'skor_kuesioner', 'detail_jawaban'];

    protected $casts = [
        'detail_jawaban' => 'array',
    ];
}
