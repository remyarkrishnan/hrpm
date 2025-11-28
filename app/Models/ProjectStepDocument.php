<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStepDocument extends Model
{
    protected $fillable = ['project_step_id','file_path','file_name','uploaded_by'];
    public function step() { return $this->belongsTo(ProjectStep::class,'project_step_id'); }
}
