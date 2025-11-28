<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'check_in_location_image',
        'check_out_location_image',
        'check_out_coords',
        'working_hours',
        'break_duration',
        'overtime_hours',
        'status',
        'location',
        'project_id',
        'project_location_id', 
        'approved_by',
        'created_by',
        'approved_at',
        'notes',
        'late_reason'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'approved_at' => 'datetime',
        //'check_in_coords' => 'array',
       // 'check_out_coords' => 'array',
        'work_hours' => 'decimal:2',
        'break_duration' => 'integer',
        'overtime_hours' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_HALF_DAY = 'half_day';
    const STATUS_WORK_FROM_HOME = 'work_from_home';

    public static function getStatuses()
    {
        return [
            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',
            self::STATUS_LATE => 'Late',
            self::STATUS_HALF_DAY => 'Half Day',
            self::STATUS_WORK_FROM_HOME => 'Work From Home'
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function projectLocation()
    {
        return $this->belongsTo(ProjectLocation::class, 'project_location_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getWorkingHoursFormattedAttribute()
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return '0h 0m';
        }

        $diff = $this->check_out_time->diff($this->check_in_time);
        return $diff->h . 'h ' . $diff->i . 'm';
    }

    public function getIsLateAttribute()
    {
        return $this->status === self::STATUS_LATE;
    }
}
