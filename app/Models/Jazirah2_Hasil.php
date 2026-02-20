<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jazirah2_Hasil extends Model
{
    protected $table = 'jazirah2_hasil';
    public $timestamps = false;

    protected $fillable = [
        'satker',
        'tahun',
        'id_indikator',
        'penanggungjawab',
        'bulan_target',
        'bulan_realisasi',
        'link_buktidukung',
        'status_approval',
        'status_tindaklanjut',
        'komentar_evaluator1',
        'komentar_operator1',
        'rencanaaksi',
        'output',
        'created_by_1',
        'created_at_1',
        'created_by_2',
        'created_at_2',
        'created_by_3',
        'created_at_3',
        'created_by_4',
        'created_at_4',
        'created_by_5s',
        'created_at_5s',
    ];
}
