<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename 'Building Structural' to 'Infrastructure'
        DB::table('departments')
            ->where('name', 'Building Structural')
            ->update(['name' => 'Infrastructure', 'code' => 'INF']);

        // 2. Add 'Store' department
        if (!DB::table('departments')->where('name', 'Store')->exists()) {
            DB::table('departments')->insert([
                'name' => 'Store',
                'code' => 'STR',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Add 'Planning' department
        if (!DB::table('departments')->where('name', 'Planning')->exists()) {
            DB::table('departments')->insert([
                'name' => 'Planning',
                'code' => 'PLN',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Reverse Rename
        DB::table('departments')
            ->where('name', 'Infrastructure')
            ->update(['name' => 'Building Structural', 'code' => 'BST']); // Assuming original code was BST or derived

        // Remove added departments
        DB::table('departments')->whereIn('name', ['Store', 'Planning'])->delete();
    }
};
