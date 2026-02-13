<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PENDING_ADMIN = 'pending_admin';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Pending Manager Approval',
            self::PENDING_ADMIN => 'Pending Admin Approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-100 text-slate-600 ring-slate-200',
            self::PENDING => 'bg-amber-50 text-amber-700 ring-amber-200',
            self::PENDING_ADMIN => 'bg-blue-50 text-blue-700 ring-blue-200',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            self::REJECTED => 'bg-rose-50 text-rose-700 ring-rose-200',
        };
    }
}
