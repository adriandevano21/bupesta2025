<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bupesta_Kegiatan extends Model
{
    protected $table = 'bupesta_kegiatan'; // Sesuaikan nama tabel

    // --- TAMBAHKAN 3 BARIS INI JUGA ---
    protected $primaryKey = 'kode_kegiatan';
    public $incrementing = false;
    protected $keyType = 'string';
    // ----------------------------

    protected $fillable = [
        'kode_kegiatan',
        'kode_eselon',
        'nama_kegiatan',
        'tahun_kegiatan',
        'kode_tim_kerja',
        'detail_anggota_tim',
        'detail_informasi_penting',
        'detail_jadwal',
        'timestamp',
        'nip_pegawai',
        'flag_kabkot',
        'pjk_1100',
        'pjk_1101',
        'pjk_1102',
        'pjk_1103',
        'pjk_1104',
        'pjk_1105',
        'pjk_1106',
        'pjk_1107',
        'pjk_1108',
        'pjk_1109',
        'pjk_1110',
        'pjk_1111',
        'pjk_1112',
        'pjk_1113',
        'pjk_1114',
        'pjk_1115',
        'pjk_1116',
        'pjk_1117',
        'pjk_1118',
        'pjk_1171',
        'pjk_1172',
        'pjk_1173',
        'pjk_1174',
        'pjk_1175'
    ];

    public $timestamps = false;
}
