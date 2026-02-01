<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all departments
        $departments = Department::all();

        // Step 1: Create Admin User
        $adminUser = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'phone' => '+1-555-0100',
            'date_of_joining' => now()->subDays(365)->toDateString(),
            'is_active' => true,
        ]);

                Employee::create([
                    'user_id' => $adminUser->id,
                    'department_id' => $departments->where('code', 'ADM')->first()->id ?? 18, // Administration department
                    'employee_id' => 'ADM001',
                    'first_name' => 'System',
                    'last_name' => 'Administrator',
                    'email' => 'admin@company.com',
                    'phone' => '+1-555-0100',
                    'date_of_birth' => now()->subYears(35)->subDays(rand(1, 365))->toDateString(),
                    'date_of_joining' => $adminUser->date_of_joining,
                    'position' => 'System Administrator',
                    'salary' => 95000,
                    'employment_type' => 'full_time',
                    'is_active' => true,
                ]);

        // Step 2: Create Department Managers and Designated Attendance Users
        $departmentData = [
            // IT Department
            [
                'dept_code' => 'IT',
                'manager' => [
                    'name' => 'Sarah Johnson',
                    'email' => 'manager.it@company.com',
                    'employee_id' => 'MGR001',
                    'phone' => '+1-555-0101',
                    'position' => 'IT Manager',
                ],
                'attendance_user' => [
                    'name' => 'Alex Thompson',
                    'email' => 'attendance.it@company.com',
                    'employee_id' => 'ATT001',
                    'phone' => '+1-555-0102',
                    'position' => 'IT Attendance Coordinator',
                ],
                'employees' => [
                    ['name' => 'Michael Chen', 'email' => 'michael.chen@company.com', 'id' => 'EMP001', 'position' => 'Senior Developer'],
                    ['name' => 'Emily Rodriguez', 'email' => 'emily.rodriguez@company.com', 'id' => 'EMP002', 'position' => 'Frontend Developer'],
                    ['name' => 'David Kim', 'email' => 'david.kim@company.com', 'id' => 'EMP003', 'position' => 'DevOps Engineer'],
                    ['name' => 'Lisa Park', 'email' => 'lisa.park@company.com', 'id' => 'EMP004', 'position' => 'QA Engineer'],
                    ['name' => 'James Wilson', 'email' => 'james.wilson@company.com', 'id' => 'EMP005', 'position' => 'System Analyst'],
                ]
            ],

            // HR Department
            [
                'dept_code' => 'HR',
                'manager' => [
                    'name' => 'Jennifer Martinez',
                    'email' => 'manager.hr@company.com',
                    'employee_id' => 'MGR002',
                    'phone' => '+1-555-0201',
                    'position' => 'HR Manager',
                ],
                'attendance_user' => [
                    'name' => 'Maria Sanchez',
                    'email' => 'attendance.hr@company.com',
                    'employee_id' => 'ATT002',
                    'phone' => '+1-555-0202',
                    'position' => 'HR Attendance Coordinator',
                ],
                'employees' => [
                    ['name' => 'Robert Taylor', 'email' => 'robert.taylor@company.com', 'id' => 'EMP006', 'position' => 'HR Specialist'],
                    ['name' => 'Anna Davis', 'email' => 'anna.davis@company.com', 'id' => 'EMP007', 'position' => 'Recruitment Specialist'],
                    ['name' => 'Mark Johnson', 'email' => 'mark.johnson@company.com', 'id' => 'EMP008', 'position' => 'Training Coordinator'],
                ]
            ],

            // Finance Department
            [
                'dept_code' => 'FIN',
                'manager' => [
                    'name' => 'Lisa Anderson',
                    'email' => 'manager.finance@company.com',
                    'employee_id' => 'MGR003',
                    'phone' => '+1-555-0301',
                    'position' => 'Finance Manager',
                ],
                'attendance_user' => [
                    'name' => 'Kevin Brown',
                    'email' => 'attendance.finance@company.com',
                    'employee_id' => 'ATT003',
                    'phone' => '+1-555-0302',
                    'position' => 'Finance Attendance Coordinator',
                ],
                'employees' => [
                    ['name' => 'Sarah Miller', 'email' => 'sarah.miller@company.com', 'id' => 'EMP009', 'position' => 'Senior Accountant'],
                    ['name' => 'Tom Garcia', 'email' => 'tom.garcia@company.com', 'id' => 'EMP010', 'position' => 'Financial Analyst'],
                    ['name' => 'Linda Chen', 'email' => 'linda.chen@company.com', 'id' => 'EMP011', 'position' => 'Accounts Payable Clerk'],
                ]
            ],

            // Sales Department
            [
                'dept_code' => 'SAL',
                'manager' => [
                    'name' => 'Carlos Rodriguez',
                    'email' => 'manager.sales@company.com',
                    'employee_id' => 'MGR004',
                    'phone' => '+1-555-0401',
                    'position' => 'Sales Manager',
                ],
                'attendance_user' => [
                    'name' => 'Jessica Garcia',
                    'email' => 'attendance.sales@company.com',
                    'employee_id' => 'ATT004',
                    'phone' => '+1-555-0402',
                    'position' => 'Sales Attendance Coordinator',
                ],
                'employees' => [
                    ['name' => 'Mike Johnson', 'email' => 'mike.johnson@company.com', 'id' => 'EMP012', 'position' => 'Senior Sales Rep'],
                    ['name' => 'Rachel White', 'email' => 'rachel.white@company.com', 'id' => 'EMP013', 'position' => 'Sales Associate'],
                    ['name' => 'David Lee', 'email' => 'david.lee@company.com', 'id' => 'EMP014', 'position' => 'Business Development Rep'],
                ]
            ],

            // Operations Department
            [
                'dept_code' => 'OPS',
                'manager' => [
                    'name' => 'Steven Clark',
                    'email' => 'manager.ops@company.com',
                    'employee_id' => 'MGR005',
                    'phone' => '+1-555-0501',
                    'position' => 'Operations Manager',
                ],
                'attendance_user' => [
                    'name' => 'Amanda Foster',
                    'email' => 'attendance.ops@company.com',
                    'employee_id' => 'ATT005',
                    'phone' => '+1-555-0502',
                    'position' => 'Operations Attendance Coordinator',
                ],
                'employees' => [
                    ['name' => 'Brian Taylor', 'email' => 'brian.taylor@company.com', 'id' => 'EMP015', 'position' => 'Operations Supervisor'],
                    ['name' => 'Nancy Wright', 'email' => 'nancy.wright@company.com', 'id' => 'EMP016', 'position' => 'Process Coordinator'],
                    ['name' => 'Chris Evans', 'email' => 'chris.evans@company.com', 'id' => 'EMP017', 'position' => 'Quality Control Specialist'],
                ]
            ],
        ];

        foreach ($departmentData as $deptData) {
            $department = $departments->where('code', $deptData['dept_code'])->first();

            if ($department) {
                // Create Department Manager
                $managerUser = User::create([
                    'name' => $deptData['manager']['name'],
                    'email' => $deptData['manager']['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'manager',
                    'department_id' => $department->id,
                    'employee_id' => $deptData['manager']['employee_id'],
                    'phone' => $deptData['manager']['phone'],
                    'date_of_joining' => now()->subDays(rand(180, 730))->toDateString(),
                    'is_active' => true,
                ]);

                Employee::create([
                    'user_id' => $managerUser->id,
                    'department_id' => $department->id,
                    'employee_id' => $deptData['manager']['employee_id'],
                    'first_name' => explode(' ', $deptData['manager']['name'])[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $deptData['manager']['name']), 1)),
                    'email' => $deptData['manager']['email'],
                    'phone' => $deptData['manager']['phone'],
                    'date_of_birth' => now()->subYears(rand(30, 50))->subDays(rand(1, 365))->toDateString(),
                    'date_of_joining' => $managerUser->date_of_joining,
                    'position' => $deptData['manager']['position'],
                    'salary' => rand(70000, 100000),
                    'employment_type' => 'full_time',
                    'is_active' => true,
                ]);

                // Update department with manager
                $department->update(['manager_id' => $managerUser->id]);

                // Create Designated Attendance User (Department_Attendance_User role)
                $attendanceUser = User::create([
                    'name' => $deptData['attendance_user']['name'],
                    'email' => $deptData['attendance_user']['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'department_attendance_user', // New role for designated attendance users
                    'department_id' => $department->id,
                    'employee_id' => $deptData['attendance_user']['employee_id'],
                    'phone' => $deptData['attendance_user']['phone'],
                    'date_of_joining' => now()->subDays(rand(90, 365))->toDateString(),
                    'is_active' => true,
                ]);

                Employee::create([
                    'user_id' => $attendanceUser->id,
                    'department_id' => $department->id,
                    'employee_id' => $deptData['attendance_user']['employee_id'],
                    'first_name' => explode(' ', $deptData['attendance_user']['name'])[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $deptData['attendance_user']['name']), 1)),
                    'email' => $deptData['attendance_user']['email'],
                    'phone' => $deptData['attendance_user']['phone'],
                    'date_of_birth' => now()->subYears(rand(25, 45))->subDays(rand(1, 365))->toDateString(),
                    'date_of_joining' => $attendanceUser->date_of_joining,
                    'position' => $deptData['attendance_user']['position'],
                    'salary' => rand(45000, 60000),
                    'employment_type' => 'full_time',
                    'is_active' => true,
                ]);

                // Create Regular Employees
                foreach ($deptData['employees'] as $empData) {
                    $user = User::create([
                        'name' => $empData['name'],
                        'email' => $empData['email'],
                        'password' => Hash::make('password123'),
                        'role' => 'user',
                        'department_id' => $department->id,
                        'employee_id' => $empData['id'],
                        'phone' => '+1-555-' . rand(1000, 9999),
                        'date_of_joining' => now()->subDays(rand(30, 365))->toDateString(),
                        'is_active' => true,
                    ]);

                    Employee::create([
                        'user_id' => $user->id,
                        'department_id' => $department->id,
                        'employee_id' => $empData['id'],
                        'first_name' => explode(' ', $empData['name'])[0],
                        'last_name' => implode(' ', array_slice(explode(' ', $empData['name']), 1)),
                        'email' => $empData['email'],
                        'phone' => $user->phone,
                        'date_of_birth' => now()->subYears(rand(22, 55))->subDays(rand(1, 365))->toDateString(),
                        'date_of_joining' => $user->date_of_joining,
                        'position' => $empData['position'],
                        'salary' => rand(40000, 85000),
                        'employment_type' => 'full_time',
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Step 3: Create some additional employees in other departments
        $additionalEmployees = [
            ['dept' => 'MKT', 'name' => 'Sophie Turner', 'email' => 'sophie.turner@company.com', 'id' => 'EMP018', 'position' => 'Marketing Specialist'],
            ['dept' => 'CS', 'name' => 'Daniel Brown', 'email' => 'daniel.brown@company.com', 'id' => 'EMP019', 'position' => 'Customer Service Rep'],
            ['dept' => 'QA', 'name' => 'Olivia Wilson', 'email' => 'olivia.wilson@company.com', 'id' => 'EMP020', 'position' => 'QA Tester'],
        ];

        foreach ($additionalEmployees as $emp) {
            $department = $departments->where('code', $emp['dept'])->first();
            if ($department) {
                $user = User::create([
                    'name' => $emp['name'],
                    'email' => $emp['email'],
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'department_id' => $department->id,
                    'employee_id' => $emp['id'],
                    'phone' => '+1-555-' . rand(1000, 9999),
                    'date_of_joining' => now()->subDays(rand(30, 365))->toDateString(),
                    'is_active' => true,
                ]);

                Employee::create([
                    'user_id' => $user->id,
                    'department_id' => $department->id,
                    'employee_id' => $emp['id'],
                    'first_name' => explode(' ', $emp['name'])[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $emp['name']), 1)),
                    'email' => $emp['email'],
                    'phone' => $user->phone,
                    'date_of_birth' => now()->subYears(rand(22, 55))->subDays(rand(1, 365))->toDateString(),
                    'date_of_joining' => $user->date_of_joining,
                    'position' => $emp['position'],
                    'salary' => rand(35000, 65000),
                    'employment_type' => 'full_time',
                    'is_active' => true,
                ]);
            }
        }
    }
}
