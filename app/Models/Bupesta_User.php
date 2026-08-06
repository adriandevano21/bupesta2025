<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bupesta_User extends Model
{
    use HasFactory;

    protected $table = 'bupesta_user';
    protected $guarded = []; // Izinkan semua kolom diisi

    public $timestamps = false;
}
