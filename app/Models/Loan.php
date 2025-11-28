<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'repayment_duration',
        'status',
        'reason',
        'manager_assigned',
        'approved_by_manager',
        'manager_remarks',
        'approved_by',
        'accounts_and_hr_remarks',
        'created_by',
        'remarks',
        'supporting_document',
    ];

        // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    // Manager assigned
    public function assignedManager()
    {
        return $this->belongsTo(User::class, 'manager_assigned');
    }

    // Manager who approved/rejected
    public function managerApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_manager');
    }

    // Final approver (Admin/HR/Accounts)
    public function finalApprover()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

