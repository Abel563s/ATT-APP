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
        Schema::table('attendance_entries', function (Blueprint $table) {
            // Remove Saturday afternoon column as Saturday only has morning sessions
            $table->dropColumn('sat_a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_entries', function (Blueprint $table) {
            // Restore Saturday afternoon column for rollback
            $table->string('sat_a')->nullable()->after('sat_m');
        });
    }
};
