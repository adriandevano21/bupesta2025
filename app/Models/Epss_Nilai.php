<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epss_Nilai extends Model
{
    protected $table = 'epss_nilai';
    protected $guarded = ['id'];

    public function kegiatan() {
        return $this->belongsTo(Epss_Kegiatan::class, 'id_kegiatan');
    }

    public function tahapan() {
        return $this->belongsTo(Epss_TahapanKegiatan::class, 'id_tahapan');
    }

    public function usulanKegiatan() {
        return $this->belongsTo(Epss_UsulanKegiatan::class, 'id_usulan_kegiatan');
    }
}