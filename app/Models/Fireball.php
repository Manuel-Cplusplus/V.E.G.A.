<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fireball extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'lat',
        'lon',
        'lat_dir',
        'lon_dir',
        'alt',
        'energy',
        'impact_e',
        'vx',
        'vy',
        'vz'
    ];
}
