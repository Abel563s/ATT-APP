<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::updateOrCreate(
            ['key' => 'employee_id_prefix'],
            ['value' => 'EEC', 'label' => 'Employee ID Prefix']
        );
    }
}
