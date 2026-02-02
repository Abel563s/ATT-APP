<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = \App\Models\SystemSetting::where('key', 'employee_id_prefix')->first()?->value ?? 'EEC';
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if employee already exists by employee_id or email
        $existingEmployee = Employee::where('employee_id', $row['employee_id'])
            ->orWhere('email', $row['email'])
            ->first();

        if ($existingEmployee) {
            return null;
        }

        // Find or create department
        $department = Department::where('name', 'like', '%' . $row['department'] . '%')->first();
        if (!$department) {
            // If department doesn't exist, we skip or handle error. 
            // For now, let's assume valid departments are required.
            return null;
        }

        // Create user account
        $user = User::create([
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'email' => $row['email'],
            'password' => Hash::make(Str::random(12)), // Random password for imported employees
            'role' => strtolower($row['role'] ?? 'user'),
            'department_id' => $department->id,
            'employee_id' => $row['employee_id'],
            'is_active' => true,
        ]);

        return new Employee([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'employee_id' => $row['employee_id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'position' => $row['position'] ?? $row['role'],
            'site' => $row['site'] ?? null,
            'date_of_joining' => now(),
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'department' => 'required|string',
            'role' => 'required|string|in:admin,manager,user,department_attendance_user',
        ];
    }
}
