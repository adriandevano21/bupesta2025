<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bupesta_Hukdis extends Model
{
    use HasFactory;

    protected $table = 'bupesta_hukdis';
    protected $guarded = []; // Izinkan semua kolom diisi

}
