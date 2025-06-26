<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseApproach extends Model
{
    use HasFactory;

    protected $fillable = [
        'asteroid_id',
        'close_approach_date',
        'close_approach_date_full',
        'epoch_date_close_approach',
        'relative_velocity_ks',
        'relative_velocity_kh',
        'relative_velocity_mh',
        'miss_distance_astronomical',
        'miss_distance_lunar',
        'miss_distance_kilometers',
        'miss_distance_miles',
        'orbiting_body',
    ];

    public function asteroid()
    {
        return $this->belongsTo(Asteroid::class);
    }
}
