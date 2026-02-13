<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'pending_admin' to the enum
        DB::statement("ALTER TABLE weekly_attendances MODIFY COLUMN status ENUM('draft', 'pending', 'pending_admin', 'approved', 'rejected') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (note: records with 'pending_admin' might be lost or become invalid)
        DB::statement("ALTER TABLE weekly_attendances MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected') NOT NULL DEFAULT 'draft'");
    }
};
