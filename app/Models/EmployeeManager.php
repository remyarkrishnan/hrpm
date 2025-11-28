<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeManager extends Model
{
    use HasFactory;

    protected $table = 'employee_manager';

    protected $fillable = [
        'employee_id',
        'manager_id',
        'assigned_by',
        'active',
        'notes',
        'assigned_date',
        'end_date',
    ];

    protected $casts = [
        'active' => 'boolean',
        'assigned_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Employee relationship
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Manager relationship
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Assigned by relationship
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope for active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for specific manager
     */
    public function scopeForManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }
}