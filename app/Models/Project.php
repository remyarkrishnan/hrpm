<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','project_code','description','client_name','client_contact',
        'manager_id','start_date','end_date','budget','status','priority',
        'completion_percentage','location','address',
        'approval_status','created_by','approved_by'
    ];

   // protected $dates = ['start_date','end_date'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
       
    ];

    public function manager() { return $this->belongsTo(User::class,'manager_id'); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function steps() { return $this->hasMany(ProjectStep::class); }
    public function resources() { return $this->hasMany(ProjectResource::class); }
    public function locations(){ return $this->hasMany(ProjectLocation::class);}
}
