<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class FavoriteAsteroid extends Model
{
    use HasFactory;

    protected $fillable = [
        'asteroid_id',
        'asteroid_designation',
        'user_id',
        'cad',
        'isSentry',
        'impact_probability',
        'impact_date',
        'torino_scale',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
