<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ist_Pertanyaan extends Model
{
    protected $table = 'ist_pertanyaan';
    protected $fillable = ['pertanyaan', 'is_active'];
}
