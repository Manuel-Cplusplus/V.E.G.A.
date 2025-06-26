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

class LearnStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'LLMID', 'learn_prompt_id', 'user_id'
    ];

    public function llm()
    {
        return $this->belongsTo(Llm::class, 'LLMID');
    }

    public function prompt()
    {
        return $this->belongsTo(LearnPrompt::class, 'learn_prompt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contents()
    {
        return $this->hasMany(LearnContent::class);
    }

}
