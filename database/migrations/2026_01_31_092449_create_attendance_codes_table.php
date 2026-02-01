<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendance_codes', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('code')->unique();
            $blueprint->string('label');
            $blueprint->string('description')->nullable();
            $blueprint->string('bg_color')->default('bg-slate-50');
            $blueprint->string('text_color')->default('text-slate-700');
            $blueprint->string('ring_color')->default('ring-slate-200');
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_codes');
    }
};
