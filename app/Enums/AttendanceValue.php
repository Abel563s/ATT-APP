<?php

namespace App\Enums;

enum AttendanceValue: string
{
    case PRESENT = 'P';
    case ABSENT = 'A';
    case SICK_LEAVE = 'SL';
    case ANNUAL_LEAVE = 'AL';
    case MATERNITY_LEAVE = 'MaL';
    case MOURNING_LEAVE = 'MoL';
    case ON_DUTY = 'OD';
    case HOLIDAY = 'H';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::SICK_LEAVE => 'Sick Leave',
            self::ANNUAL_LEAVE => 'Annual Leave',
            self::MATERNITY_LEAVE => 'Maternity Leave',
            self::MOURNING_LEAVE => 'Mourning Leave',
            self::ON_DUTY => 'On Duty',
            self::HOLIDAY => 'Holiday',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PRESENT => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            self::ABSENT => 'bg-rose-50 text-rose-700 ring-rose-200',
            self::SICK_LEAVE => 'bg-amber-50 text-amber-700 ring-amber-200',
            self::ANNUAL_LEAVE => 'bg-sky-50 text-sky-700 ring-sky-200',
            self::MATERNITY_LEAVE => 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-200',
            self::MOURNING_LEAVE => 'bg-slate-100 text-slate-600 ring-slate-200',
            self::ON_DUTY => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            self::HOLIDAY => 'bg-orange-50 text-orange-700 ring-orange-200',
        };
    }
}
