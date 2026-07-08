<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->call([
                SuperAdminSeeder::class,
            ]);
        } else {
            $this->call([
                AttendanceSystemSeeder::class,
                SuperAdminSeeder::class,
            ]);
        }
    }
}
