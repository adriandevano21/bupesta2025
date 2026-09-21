<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epss_UsulanKegiatan extends Model
{
    protected $table = 'epss_usulan_kegiatan';
    protected $guarded = ['id'];

    public function kegiatan() {
        return $this->belongsTo(Epss_Kegiatan::class, 'id_kegiatan');
    }

    public function nilai() {
        return $this->hasMany(Epss_Nilai::class, 'id_usulan_kegiatan');
    }
}