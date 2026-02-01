<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_attendance_id',
        'employee_id',
        'mon_m',
        'mon_a',
        'tue_m',
        'tue_a',
        'wed_m',
        'wed_a',
        'thu_m',
        'thu_a',
        'fri_m',
        'fri_a',
        'sat_m',
        'sat_a',
    ];

    // Removed Enum casts to support database-driven attendance codes
    protected $casts = [
        // Fields are now treated as strings and matched against AttendanceCode database entries
    ];

    public function weeklyAttendance(): BelongsTo
    {
        return $this->belongsTo(WeeklyAttendance::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
