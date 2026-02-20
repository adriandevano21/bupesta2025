<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jazirah2_User extends Model
{
    protected $table = 'jazirah2_users';

    protected $fillable = [
        'name',
        'username',
        'kode_satker',
        'nip_pegawai',
        'email',
        'role',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
