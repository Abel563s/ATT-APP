<?php

namespace App\Console\Commands;

use App\Models\WeeklyAttendance;
use App\Models\User;
use Illuminate\Console\Command;

class DebugAttendance extends Command
{
    protected $signature = 'attendance:debug';
    protected $description = 'Debug attendance display issues';

    public function handle()
    {
        $this->info('=== Attendance Debug Info ===');

        // Check total records
        $total = WeeklyAttendance::count();
        $this->info("Total WeeklyAttendance records: {$total}");

        // Check by status
        $draft = WeeklyAttendance::where('status', 'draft')->count();
        $pending = WeeklyAttendance::where('status', 'pending')->count();
        $approved = WeeklyAttendance::where('status', 'approved')->count();
        $rejected = WeeklyAttendance::where('status', 'rejected')->count();

        $this->info("Draft: {$draft}");
        $this->info("Pending: {$pending}");
        $this->info("Approved: {$approved}");
        $this->info("Rejected: {$rejected}");

        // Check pending records in detail
        if ($pending > 0) {
            $this->info("\n=== Pending Records ===");
            $pendingRecords = WeeklyAttendance::where('status', 'pending')
                ->with(['department', 'submitter'])
                ->get();

            foreach ($pendingRecords as $record) {
                $this->info("ID: {$record->id}");
                $this->info("  Department: " . ($record->department ? $record->department->name : 'NULL'));
                $this->info("  Submitter: " . ($record->submitter ? $record->submitter->name : 'NULL (ID: ' . $record->submitted_by . ')'));
                $this->info("  Week: {$record->week_start_date}");
                $this->info("  Status: {$record->status->value}");
                $this->info("---");
            }
        }

        // Check approved records
        if ($approved > 0) {
            $this->info("\n=== Approved Records ===");
            $approvedRecords = WeeklyAttendance::where('status', 'approved')
                ->with(['department', 'submitter'])
                ->get();

            foreach ($approvedRecords as $record) {
                $this->info("ID: {$record->id}");
                $this->info("  Department: " . ($record->department ? $record->department->name : 'NULL'));
                $this->info("  Submitter: " . ($record->submitter ? $record->submitter->name : 'NULL (ID: ' . $record->submitted_by . ')'));
                $this->info("  Week: {$record->week_start_date}");
                $this->info("---");
            }
        }

        // Check admin users
        $this->info("\n=== Admin Users ===");
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->info("ID: {$admin->id}, Name: {$admin->name}, Email: {$admin->email}");
        }

        return 0;
    }
}
