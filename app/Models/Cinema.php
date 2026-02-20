<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul_kegiatan',
        'tanggal_kegiatan',
        'pukul',
        'peserta',
        'pemateri',
        'tempat_kegiatan',
        'presensi_kegiatan',
        'zoom_kegiatan',
        'rekaman_kegiatan',
        'materi_kegiatan',
        'sertifikat_kegiatan',
        'created_by',
        'lok_flyer',
        'name_flyer',
        'lok_backdrop',
        'name_backdrop'
    ];
}
