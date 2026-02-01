<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Information Technology', 'code' => 'IT', 'description' => 'Handles all IT infrastructure and software development'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Manages employee relations and organizational development'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Manages financial operations and accounting'],
            ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Handles marketing campaigns and brand management'],
            ['name' => 'Sales', 'code' => 'SAL', 'description' => 'Manages sales operations and customer relationships'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Oversees daily operations and process management'],
            ['name' => 'Customer Service', 'code' => 'CS', 'description' => 'Provides customer support and service excellence'],
            ['name' => 'Research & Development', 'code' => 'RND', 'description' => 'Conducts research and product development'],
            ['name' => 'Quality Assurance', 'code' => 'QA', 'description' => 'Ensures product and service quality standards'],
            ['name' => 'Supply Chain', 'code' => 'SC', 'description' => 'Manages procurement and supply chain operations'],
            ['name' => 'Legal', 'code' => 'LEG', 'description' => 'Handles legal matters and compliance'],
            ['name' => 'Procurement', 'code' => 'PROC', 'description' => 'Manages purchasing and vendor relationships'],
            ['name' => 'Facilities Management', 'code' => 'FM', 'description' => 'Maintains physical facilities and infrastructure'],
            ['name' => 'Security', 'code' => 'SEC', 'description' => 'Ensures workplace security and safety'],
            ['name' => 'Training & Development', 'code' => 'TD', 'description' => 'Provides employee training and development programs'],
            ['name' => 'Business Development', 'code' => 'BD', 'description' => 'Drives business growth and strategic partnerships'],
            ['name' => 'Compliance', 'code' => 'COMP', 'description' => 'Ensures regulatory compliance and risk management'],
            ['name' => 'Administration', 'code' => 'ADM', 'description' => 'Handles administrative and clerical functions'],
            ['name' => 'Project Management', 'code' => 'PM', 'description' => 'Manages projects and program coordination'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
