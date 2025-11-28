<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSubplan extends Model
{
    protected $fillable = ['project_step_id','activity_name','description','start_date','end_date','progress_percentage','dependencies'];

    protected $casts = [
        'dependencies'=>'array',
        'start_date'=>'date',
        'end_date'=>'date',
    ];

    public function step() { return $this->belongsTo(ProjectStep::class,'project_step_id'); }
    public function allocations(){return $this->hasMany(ResourceAllocation::class, 'subplan_id');}
}
