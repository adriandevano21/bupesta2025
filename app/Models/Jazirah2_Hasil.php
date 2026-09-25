<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jazirah2_Hasil extends Model
{
    protected $table = 'jazirah2_hasil';
    public $timestamps = false;

    protected $guarded = [];

    public function komentars()
    {
        return $this->hasMany(Jazirah2_Komentar::class, 'id_jazirah2_hasil', 'id');
    }
}
