<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'abeltadesse17@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('shamballa'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );
    }
}
