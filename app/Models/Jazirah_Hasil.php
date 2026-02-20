<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JazirahHasil extends Model
{
    use HasFactory;

    protected $table = 'jazirah_hasil';
    protected $primaryKey = 'id';

    protected $fillable = [
        'satker',
        'tahun',
        'id_indikator',
        'link_buktidukung',
        'status_approval',
        'status_tindaklanjut',
        'keterangan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Relasi ke tabel indikator_kinerjas (id_indikator -> kode_indikator) */
    public function indikator(): BelongsTo
    {
        // Pastikan ada model App\Models\IndikatorKinerja dengan $table = 'indikator_kinerjas'
        return $this->belongsTo(Jazirah_Indikator::class, 'id_indikator', 'kode_indikator');
    }

    /** Scope cepat filter */
    public function scopeTahun($query, string $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeIndikator($query, string $kodeIndikator)
    {
        return $query->where('id_indikator', $kodeIndikator);
    }
}
