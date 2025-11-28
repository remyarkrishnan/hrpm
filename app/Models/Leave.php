<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leaves';

    protected $fillable = [
        'user_id',
        'leave_type',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'supporting_document',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'remarks',
        'manager_assigned',
        'approved_by_manager',
        'manager_remarks',
        'accounts_and_hr_remarks',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'created_at' => 'date',
        'approved_at' => 'datetime'
    ];

    // Leave types
    const TYPE_SICK = 'sick_leave';
    const TYPE_CASUAL = 'casual_leave';
    const TYPE_ANNUAL = 'annual_leave';
    const TYPE_MATERNITY = 'maternity_leave';
    const TYPE_EMERGENCY = 'emergency_leave';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    public static function getTypes()
    {
        return [
            self::TYPE_SICK => 'Sick Leave',
            self::TYPE_CASUAL => 'Casual Leave',
            self::TYPE_ANNUAL => 'Annual Leave',
            self::TYPE_MATERNITY => 'Maternity Leave',
            self::TYPE_EMERGENCY => 'Emergency Leave'
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled'
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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
    // Accessors
    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getDaysCountAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        return $this->start_date->diffInDays($this->end_date) + 1;
    }
    public function getDateRangeAttribute()
    {
        return $this->from_date->format('d M Y') . ' - ' . $this->to_date->format('d M Y');
    }
}
