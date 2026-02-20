<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\NarahubungJazirah
 *
 * @property int $id
 * @property string|null $gmail
 * @property string|null $email_bps
 * @property string|null $nama
 * @property string|null $satker
 * @property string $role
 * @property string|null $no_hp
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class NarahubungJazirah extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     */
    protected $table = 'narahubung_jazirah';

    /**
     * Kolom yang diizinkan mass assignment.
     */
    protected $fillable = [
        'gmail',
        'email_bps',
        'nama',
        'satker',
        'role',
        'no_hp',
        'status',
    ];

    /**
     * Casting tipe kolom.
     */
    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Scope: hanya record aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
