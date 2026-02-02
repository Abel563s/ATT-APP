<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'EEC-ID',
            'First Name',
            'Last Name',
            'Email',
            'Department',
            'Position',
            'Site',
            'Status',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->first_name,
            $employee->last_name,
            $employee->email,
            $employee->department->name ?? 'N/A',
            $employee->position,
            $employee->site,
            ucfirst($employee->status),
        ];
    }
}
