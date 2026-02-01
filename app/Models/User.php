<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'employee_id',
        'phone',
        'date_of_birth',
        'date_of_joining',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'date_of_joining' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the department that the user belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the employee record associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the departments managed by this user (for managers).
     */
    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'manager_id');
    }

    /**
     * Get the first department managed by this user (legacy/convenience).
     */
    public function managedDepartment(): HasOne
    {
        return $this->hasOne(Department::class, 'manager_id');
    }

    /**
     * Get all department IDs that this user is responsible for.
     */
    public function getResponsibleDepartmentIds(): array
    {
        $ids = $this->managedDepartments()->pluck('id')->toArray();
        if ($this->isManager() && $this->department_id) {
            $ids[] = $this->department_id;
        }
        return array_unique($ids);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is department attendance user.
     */
    public function isDepartmentAttendanceUser(): bool
    {
        return $this->role === 'department_attendance_user';
    }
}
