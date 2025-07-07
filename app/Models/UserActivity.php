<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'user_agent'
    ];

    public static function log($activity)
    {
        self::create([
            'user_id'    => "340060473",
            'activity'   => $activity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
