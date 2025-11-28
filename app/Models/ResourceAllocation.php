<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subplan_id',
        'employee_id',
        'role',
        'allocation_percentage',
        'remarks'
    ];

    public function subplan()
    {
        return $this->belongsTo(ProjectSubplan::class, 'subplan_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function project()
    {
        return $this->hasOneThrough(
            Project::class,          // Final model
            ProjectStep::class,      // Intermediate model
            'id',                    // Foreign key on intermediate (step.id)
            'id',                    // Foreign key on final (project.id)
            'subplan_id',            // Local key on ResourceAllocation (allocation.subplan_id)
            'project_id'             // Local key on ProjectStep (step.project_id)
        )->join('project_subplans', 'project_subplans.project_step_id', '=', 'project_steps.id');
    }
}
