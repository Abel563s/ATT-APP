<?php

namespace Database\Seeders;

use App\Models\AttendanceCode;
use Illuminate\Database\Seeder;

class AttendanceCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            [
                'code' => 'P',
                'label' => 'Present',
                'description' => 'Employee is present at work',
                'bg_color' => 'bg-emerald-50',
                'text_color' => 'text-emerald-700',
                'ring_color' => 'ring-emerald-200',
            ],
            [
                'code' => 'A',
                'label' => 'Absent',
                'description' => 'Employee is absent without leave',
                'bg_color' => 'bg-rose-50',
                'text_color' => 'text-rose-700',
                'ring_color' => 'ring-rose-200',
            ],
            [
                'code' => 'SL',
                'label' => 'Sick Leave',
                'description' => 'Employee is on medical leave',
                'bg_color' => 'bg-amber-50',
                'text_color' => 'text-amber-700',
                'ring_color' => 'ring-amber-200',
            ],
            [
                'code' => 'AL',
                'label' => 'Annual Leave',
                'description' => 'Employee is on planned vacation',
                'bg_color' => 'bg-sky-50',
                'text_color' => 'text-sky-700',
                'ring_color' => 'ring-sky-200',
            ],
            [
                'code' => 'MaL',
                'label' => 'Maternity Leave',
                'description' => 'Employee is on maternity leave',
                'bg_color' => 'bg-fuchsia-50',
                'text_color' => 'text-fuchsia-700',
                'ring_color' => 'ring-fuchsia-200',
            ],
            [
                'code' => 'MoL',
                'label' => 'Mourning Leave',
                'description' => 'Employee is on compassionate leave',
                'bg_color' => 'bg-slate-100',
                'text_color' => 'text-slate-600',
                'ring_color' => 'ring-slate-200',
            ],
            [
                'code' => 'OD',
                'label' => 'On Duty',
                'description' => 'Employee is performing work outside office',
                'bg_color' => 'bg-indigo-50',
                'text_color' => 'text-indigo-700',
                'ring_color' => 'ring-indigo-200',
            ],
            [
                'code' => 'H',
                'label' => 'Holiday',
                'description' => 'Public or company holiday',
                'bg_color' => 'bg-orange-50',
                'text_color' => 'text-orange-700',
                'ring_color' => 'ring-orange-200',
            ],
        ];

        foreach ($codes as $code) {
            AttendanceCode::updateOrCreate(['code' => $code['code']], $code);
        }
    }
}
