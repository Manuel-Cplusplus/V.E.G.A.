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

class LearnContent extends Model
{
    use HasFactory;
    protected $table = 'learn_contents';
    protected $fillable = [
        'content',
        'learn_structure_id',
        'version',
        'original_content_id',
    ];
    public function learnStructures()
    {
        return $this->belongsTo(LearnStructure::class, 'learn_structure_id');
    }

    public function quizzes()
    {
        return $this->hasMany(LearnQuiz::class);
    }

    public function originalContent()
    {
        return $this->belongsTo(self::class, 'original_content_id');
    }

    public function childVersions()
    {
        return $this->hasMany(self::class, 'original_content_id');
    }


    public function feedback()
    {
        return $this->hasOne(LearnFeedback::class, 'learn_content_id');
    }
}
