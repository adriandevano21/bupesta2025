<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jazirah_Kritiksaran extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'jazirah_kritiksaran';

    // Kolom-kolom yang diizinkan untuk diisi datanya
    protected $fillable = [
        'nip_pegawai',
        'jenis',
        'pesan',
    ];
}