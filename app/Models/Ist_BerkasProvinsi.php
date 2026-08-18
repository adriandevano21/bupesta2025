<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ist_BerkasProvinsi extends Model
{
    protected $table = 'ist_berkas_provinsi';
    protected $fillable = [
        'periode_id', 'urutan', 'nama_dokumen', 'keterangan', 'link_template'
    ];
    
    public function pengumpulan()
    {
        return $this->hasMany(Ist_PengumpulanBerkas::class, 'berkas_id');
    }
}
