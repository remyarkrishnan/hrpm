<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'location',
        'duration',
        'benefit',
        'supporting_document',
        'manager_assigned',
        'approved_by_manager',
        'manager_remarks',
        'status',
        'created_by',
        'approved_by',
        'accounts_and_hr_remarks',
        'approved_at',
        'remarks',
        'applied_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedManager()
    {
        return $this->belongsTo(User::class, 'manager_assigned');
    }

    public function approvedByManager()
    {
        return $this->belongsTo(User::class, 'approved_by_manager');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
