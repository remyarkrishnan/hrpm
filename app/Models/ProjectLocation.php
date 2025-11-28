<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'location_name',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'geofence_coordinates',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
