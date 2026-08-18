<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ist_PengumpulanBerkas extends Model
{
    protected $table = 'ist_pengumpulan_berkas';

    protected $fillable = [
        'periode_id',
        'berkas_id',
        'nip_kandidat',
        'link_berkas',
        'catatan',
    ];

    /**
     * Relasi ke definisi jenis berkas.
     */
    public function berkas()
    {
        return $this->belongsTo(Ist_BerkasProvinsi::class, 'berkas_id');
    }
}
