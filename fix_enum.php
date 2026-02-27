<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\DB::statement("ALTER TABLE weekly_attendances MODIFY COLUMN status ENUM('draft', 'pending', 'pending_admin', 'approved', 'rejected') NOT NULL DEFAULT 'draft'");
    echo "Successfully updated weekly_attendances status column.\n";
} catch (\Exception $e) {
    echo "Error updating table: " . $e->getMessage() . "\n";
}
