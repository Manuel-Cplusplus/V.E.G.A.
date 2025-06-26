<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class LearnQuiz extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'learn_content_id'];

    public function content()
    {
        return $this->belongsTo(LearnContent::class, 'learn_content_id');
    }

    public function answers()
    {
        return $this->hasMany(LearnAnswer::class);
    }
}
