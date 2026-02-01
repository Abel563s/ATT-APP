<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'date_of_joining',
        'position',
        'salary',
        'employment_type',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with this employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that this employee belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }



    /**
     * Get the full name of the employee.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Scope to get only active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by department.
     */
    public function scopeInDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Get the attendance entries for this employee.
     */
    public function attendanceEntries(): HasMany
    {
        return $this->hasMany(AttendanceEntry::class);
    }
}
