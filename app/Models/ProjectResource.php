<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectResource extends Model
{
    protected $fillable = ['project_id','user_id','project_step_id','role','performance_rating'];

    public function project() { return $this->belongsTo(Project::class); }
    public function user() { return $this->belongsTo(User::class,'user_id'); }
    public function step() { return $this->belongsTo(ProjectStep::class,'project_step_id'); }
}
