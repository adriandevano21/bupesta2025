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
        'status',
        'ketuatim_1100',
        'ketuatim_1101',
        'ketuatim_1102',
        'ketuatim_1103',
        'ketuatim_1104',
        'ketuatim_1105',
        'ketuatim_1106',
        'ketuatim_1107',
        'ketuatim_1108',
        'ketuatim_1109',
        'ketuatim_1110',
        'ketuatim_1111',
        'ketuatim_1112',
        'ketuatim_1113',
        'ketuatim_1114',
        'ketuatim_1115',
        'ketuatim_1116',
        'ketuatim_1117',
        'ketuatim_1118',
        'ketuatim_1171',
        'ketuatim_1172',
        'ketuatim_1173',
        'ketuatim_1174',
        'ketuatim_1175'
    ];

    public $timestamps = false;

    public function kegiatan()
    {
        return $this->hasMany(Bupesta_Kegiatan::class, 'kode_tim_kerja', 'kode_tim_kerja');
    }
}
