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
        Schema::create('weekly_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->date('week_start_date');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['department_id', 'week_start_date'], 'dept_week_unique');
            $table->index('week_start_date');
            $table->index('status');
        });

        Schema::create('attendance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_attendance_id')->constrained('weekly_attendances')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            // Attendance cells
            $table->string('mon_m', 5)->nullable();
            $table->string('mon_a', 5)->nullable();
            $table->string('tue_m', 5)->nullable();
            $table->string('tue_a', 5)->nullable();
            $table->string('wed_m', 5)->nullable();
            $table->string('wed_a', 5)->nullable();
            $table->string('thu_m', 5)->nullable();
            $table->string('thu_a', 5)->nullable();
            $table->string('fri_m', 5)->nullable();
            $table->string('fri_a', 5)->nullable();
            $table->string('sat_m', 5)->nullable();
            $table->string('sat_a', 5)->nullable();

            $table->timestamps();

            $table->unique(['weekly_attendance_id', 'employee_id'], 'week_emp_unique');
        });

        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_attendance_id')->constrained('weekly_attendances')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // submitted, approved, rejected
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
        Schema::dropIfExists('attendance_entries');
        Schema::dropIfExists('weekly_attendances');
    }
};
