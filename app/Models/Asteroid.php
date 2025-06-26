<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asteroid extends Model
{
    use HasFactory;

    protected $fillable = [
        'neo_reference_id',
        'name',
        'designation',
        'nasa_jpl_url',
        'absolute_magnitude_h',
        'is_potentially_hazardous_asteroid',
        'estimated_diameter_min_kilometers',
        'estimated_diameter_max_kilometers',
        'estimated_diameter_min_meters',
        'estimated_diameter_max_meters',
        'estimated_diameter_min_miles',
        'estimated_diameter_max_miles',
        'estimated_diameter_min_feet',
        'estimated_diameter_max_feet',
        'is_sentry_object',
    ];

    public function closeApproaches()
    {
        return $this->hasMany(CloseApproach::class);
    }

    public function isHazardous()
    {
        return $this->hazardous ? 'Si' : 'No';
    }
}
