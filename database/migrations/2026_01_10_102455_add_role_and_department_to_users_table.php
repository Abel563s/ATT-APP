<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'user'])->default('user');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('employee_id')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'employee_id', 'phone', 'date_of_birth', 'date_of_joining', 'is_active']);
        });
    }
};
