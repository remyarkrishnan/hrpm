<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStep extends Model
{
    protected $fillable = [
        'project_id','step_key','step_name','status','consultant_id','responsible_person_id',
        'due_date','progress_percent','remarks','documents','approved_at'
    ];

    protected $casts = [
        'documents' => 'array',
        'approved_at' => 'datetime',
        'due_date' => 'date',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function consultant() { return $this->belongsTo(User::class,'consultant_id'); }
    public function responsiblePerson() { return $this->belongsTo(User::class,'responsible_person_id'); }
    public function subplans() { return $this->hasMany(ProjectSubplan::class,'project_step_id'); }
    
}
