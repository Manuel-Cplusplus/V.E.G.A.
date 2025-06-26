<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAD extends Model
{
    use HasFactory;

    protected $fillable = [
        'des',
        'orbit_id',
        'jd',
        'cd',
        'dist',
        'dist_min',
        'dist_max',
        'v_rel',
        'v_inf',
        't_sigma_f',
        'body',
        'h',
        'diameter',
        'diameter_sigma',
        'fullname',
    ];
}
