<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $entries;

    public function __construct($entries)
    {
        $this->entries = $entries;
    }

    public function collection()
    {
        return $this->entries;
    }

    public function headings(): array
    {
        return [
            'Week Start Date',
            'Department',
            'Employee ID',
            'Employee Name',
            'Mon M',
            'Mon A',
            'Tue M',
            'Tue A',
            'Wed M',
            'Wed A',
            'Thu M',
            'Thu A',
            'Fri M',
            'Fri A',
            'Sat M',
            'Sat A',
            'Weekly Status'
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->week_start_date->format('Y-m-d'),
            $entry->department_name,
            $entry->employee->employee_id ?? 'N/A',
            $entry->employee->full_name ?? 'N/A',
            $entry->mon_m,
            $entry->mon_a,
            $entry->tue_m,
            $entry->tue_a,
            $entry->wed_m,
            $entry->wed_a,
            $entry->thu_m,
            $entry->thu_a,
            $entry->fri_m,
            $entry->fri_a,
            $entry->sat_m,
            $entry->sat_a,
            $entry->weekly_status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
