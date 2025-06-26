<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentry extends Model
{
    use HasFactory;

    protected $fillable = [
        'des',
        'diameter',
        'fullname',
        'h',
        'id',
        'ip',
        'ps_cum',
        'ps_max',
        'range',
        'ts_max',
        'v_inf',
        'v_imp',
        'energy',
        'mass',
        'method',
        'pdate',
        'cdate',
        'first_obs',
        'last_obs',
        'darc',
        'nobs',
        'ndel',
        'nsat',
        'dist',
        'width',
        'sigma_imp',
        'sigma_lov',
        'stretch',
        'sigma_mc',
        'sigma_vi'
    ];
}
