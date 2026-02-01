<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'weekly_attendances';

    protected $fillable = [
        'department_id',
        'week_start_date',
        'status',
        'submitted_by',
        'approved_by',
        'rejection_reason',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'status' => AttendanceStatus::class,
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(AttendanceEntry::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApprovalLog::class);
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [AttendanceStatus::DRAFT, AttendanceStatus::REJECTED]);
    }
}
