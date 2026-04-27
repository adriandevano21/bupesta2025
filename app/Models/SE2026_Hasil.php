<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SE2026_Hasil extends Model
{
    protected $table = 'se2026_hasil';

    protected $fillable = [
        'satker',
        'id_indikator',
        'peng_Feb',
        'peng_Mar',
        'peng_Apr',
        'peng_Mei',
        'peng_Jun',
        'peng_Jul',
        'peng_Ags',
        'app_Feb',
        'app_Mar',
        'app_Apr',
        'app_Mei',
        'app_Jun',
        'app_Jul',
        'app_Ags'
    ];
}
