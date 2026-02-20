<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jazirah_Indikator extends Model
{
    protected $table = 'jazirah_indikator';

    protected $fillable = [
        'kode_1',
        'kode_2',
        'kode_3',
        'kode_4',
        'kode_5',
        'rencana_kerja',
        'level',
        'pengisian'
    ];

    // Indentasi dalam piksel untuk kolom pertama
    public function getIndentPxAttribute(): int
    {
        $lvl = (int) ($this->level ?? 0);
        return max(0, $lvl) * 20; // 20px per level
    }

    // Kode gabungan untuk tampilan (mis: 1.2.3)
    public function getKodePathAttribute(): string
    {
        $parts = array_filter([$this->kode_1, $this->kode_2, $this->kode_3, $this->kode_4, $this->kode_5], fn($v) => $v !== null && $v !== '');
        return implode('.', $parts);
    }
}
