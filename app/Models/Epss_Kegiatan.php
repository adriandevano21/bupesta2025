<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epss_Kegiatan extends Model
{
    protected $table = 'epss_kegiatan';
    protected $guarded = ['id'];

    public function usulanKegiatan() {
        return $this->hasMany(Epss_UsulanKegiatan::class, 'id_kegiatan');
    }

    public function tahapanKegiatan() {
        return $this->hasMany(Epss_TahapanKegiatan::class, 'id_kegiatan');
    }

    public function nilai() {
        return $this->hasMany(Epss_Nilai::class, 'id_kegiatan');
    }
}