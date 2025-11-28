<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'employee_id',
        'department',
        'designation',
        'joining_date',
        'status',
        'profile_image',
        'address',
        'reporting_manager',
        'emergency_phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'basic_salary',
        'bank_name',
        'iban_no',
        'employee_type',
        'photo',
        'bank_proof_document',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'joining_date' => 'date',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'basic_salary' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get user's full profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/profile-images/' . $this->profile_image);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole(['super-admin', 'admin']);
    }

    /**
     * Get user's primary role
     */
    public function getPrimaryRoleAttribute()
    {
        return $this->roles->first()?->name ?? 'user';
    }

    /**
     * Attendance relationship
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Projects relationship (as manager)
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    /**
     * Projects relationship (as team member)
     */
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_users');
    }

    /**
     * Leave requests relationship
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function allowances()
    {
        return $this->hasMany(Allowance::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}