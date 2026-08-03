<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bupesta_Datasimpeg extends Model
{
    use HasFactory;

    protected $table = 'bupesta_datasimpeg';
    protected $guarded = []; // Izinkan semua kolom diisi
}
