<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jazirah2_Komentar extends Model
{
    use HasFactory;

    protected $table = 'jazirah2_komentar';

    protected $fillable = [
        'id_jazirah2_hasil',
        'nip',
        'komentar',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Bupesta_User::class, 'nip', 'nip_pegawai');
    }

    // Relasi ke dokumen hasil
    public function isianHasil()
    {
        return $this->belongsTo(Jazirah2_Hasil::class, 'id_jazirah2_hasil', 'id');
    }
}