<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bupesta_TimKerja extends Model
{
    use HasFactory;

    protected $table = 'bupesta_timkerja';

    // --- TAMBAHKAN 3 BARIS INI ---
    protected $primaryKey = 'kode_tim_kerja'; // Beritahu Laravel PK-nya bukan 'id'
    public $incrementing = false;            // Beritahu PK-nya bukan auto-increment
    protected $keyType = 'string';           // Beritahu tipe datanya string
    // ----------------------------

    protected $fillable = [
        'kode_tim_kerja',
        'nama_tim_kerja',
        'icon_tim_kerja',
        'tahun',
        'nip_ketua_tim',
        'status'
    ];

    public $timestamps = false;

    public function kegiatan()
    {
        return $this->hasMany(Bupesta_Kegiatan::class, 'kode_tim_kerja', 'kode_tim_kerja');
    }
}
