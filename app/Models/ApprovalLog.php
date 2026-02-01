<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekly_attendance_id',
        'user_id',
        'action',
        'comment',
    ];

    public function weeklyAttendance(): BelongsTo
    {
        return $this->belongsTo(WeeklyAttendance::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
