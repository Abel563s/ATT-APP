<?php

namespace App\Console\Commands;

use App\Models\WeeklyAttendance;
use Illuminate\Console\Command;

class CleanSoftDeletedAttendance extends Command
{
    protected $signature = 'attendance:clean-deleted';
    protected $description = 'Permanently delete soft-deleted attendance records to avoid unique constraint issues';

    public function handle()
    {
        $count = WeeklyAttendance::onlyTrashed()->count();

        if ($count === 0) {
            $this->info('No soft-deleted attendance records found.');
            return 0;
        }

        $this->info("Found {$count} soft-deleted attendance record(s).");

        if ($this->confirm('Do you want to permanently delete these records?')) {
            WeeklyAttendance::onlyTrashed()->forceDelete();
            $this->info('All soft-deleted attendance records have been permanently removed.');
        } else {
            $this->info('Operation cancelled.');
        }

        return 0;
    }
}
