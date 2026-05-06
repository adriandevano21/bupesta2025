<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bupesta_User extends Model
{
    use HasFactory;

    protected $table = 'bupesta_user';

    protected $fillable = [
        'username',
        'tahun',
        'nip_pegawai',
        'tahun',
        'no_hp',
        'kode_satker',
        'golongan',
        'jabatan',
        'urlfoto',
        'bupesta',
        'jazirah',
        'cinema',
        'kinerja'
    ];

    public $timestamps = false;
}
