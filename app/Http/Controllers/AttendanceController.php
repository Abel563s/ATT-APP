<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Enums\AttendanceValue;
use App\Models\AttendanceEntry;
use App\Models\Employee;
use App\Models\WeeklyAttendance;
use App\Models\ApprovalLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display the attendance grid for the current week.
     * 
     * Note: Deactivated users (status = DEACTIVATED) cannot log in to the system,
     * but their attendance records can still be filled by department representatives
     * or managers who have access to the attendance grid.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Resolve Department: Allow admins to browse any department, others restricted to their context
        $deptId = $request->get('dept_id');
        $department = null;

        if ($user->isAdmin() && $deptId) {
            $department = \App\Models\Department::find($deptId);
        }

        if (!$department) {
            $department = ($user->isManager() || $user->isAdmin())
                ? ($user->managedDepartment ?? $user->department)
                : $user->department;
        }

        if (!$department) {
            return redirect()->back()->with('error', 'You are not assigned to any department.');
        }

        $weekStart = $request->get('week', Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString());
        $weekStartDate = Carbon::parse($weekStart);

        // Find existing attendance or create new one
        $attendance = WeeklyAttendance::where('department_id', $department->id)
            ->where('week_start_date', $weekStart)
            ->first();

        // Only create new attendance if none exists
        if (!$attendance) {
            // Managers (who are not admins) cannot create new attendance records
            if ($user->isManager() && !$user->isAdmin()) {
                // If it doesn't exist, just create a temporary object for display ensuring it shows as empty/locked
                // Or better yet, redirect them or show a specific message.
                // For now, let's allow finding existing, but prevent creation if it doesn't exist? 
                // Actually, the prompt says "managers should not be able to edit". 
                // If the record doesn't exist, we probably shouldn't create it for them.
                // But to allow them to see "nothing here", let's create a non-saved instance or handle it gracefully.
                // Let's create it but knowing they can't save it.
            }

            $attendance = WeeklyAttendance::firstOrCreate(
                [
                    'department_id' => $department->id,
                    'week_start_date' => $weekStart,
                ],
                [
                    'status' => AttendanceStatus::DRAFT,
                ]
            );
        }

        // Check if the current user is restricted from editing (Manager but not Admin)
        $isManagerReadOnly = ($user->isManager() && !$user->isAdmin());

        $employees = Employee::active()
            ->where('department_id', $department->id)
            ->whereHas('user', function ($q) {
                $q->whereNotIn('role', ['admin', 'manager']);
            })
            ->orderBy('first_name')
            ->get();

        $entries = AttendanceEntry::where('weekly_attendance_id', $attendance->id)
            ->get()
            ->keyBy('employee_id');

        $attendanceValues = \App\Models\AttendanceCode::where('is_active', true)->get();
        $codesMap = $attendanceValues->keyBy('code');

        return view('attendance.index', compact(
            'attendance',
            'department',
            'weekStart',
            'employees',
            'entries',
            'attendanceValues',
            'codesMap',
            'isManagerReadOnly'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'weekly_attendance_id' => 'required|exists:weekly_attendances,id',
            'attendance' => 'required|array',
        ]);

        $attendance = WeeklyAttendance::findOrFail($request->weekly_attendance_id);

        if (!$attendance->isEditable()) {
            return redirect()->back()->with('error', 'This attendance record is locked.');
        }

        // Prevent Managers (who are not Admins) from saving
        $user = Auth::user();
        if ($user->isManager() && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Managers are not authorized to edit attendance records.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $employeeId => $days) {
                AttendanceEntry::updateOrCreate(
                    [
                        'weekly_attendance_id' => $attendance->id,
                        'employee_id' => $employeeId,
                    ],
                    $days
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Attendance saved as draft.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save attendance: ' . $e->getMessage());
        }
    }

    public function submit(Request $request, WeeklyAttendance $attendance)
    {
        if (!$attendance->isEditable()) {
            return redirect()->back()->with('error', 'This attendance record cannot be submitted.');
        }

        // Prevent Managers (who are not Admins) from submitting
        $user = Auth::user();
        if ($user->isManager() && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Managers are not authorized to submit attendance records.');
        }

        // Check if there are any attendance entries
        $entriesCount = AttendanceEntry::where('weekly_attendance_id', $attendance->id)->count();
        $employeeCount = Employee::where('department_id', $attendance->department_id)
            ->whereHas('user', function ($q) {
                $q->whereNotIn('role', ['admin', 'manager']);
            })
            ->count();

        if ($entriesCount === 0) {
            return redirect()->back()->with('error', 'Cannot submit empty attendance. Please fill in attendance data and save first.');
        }

        if ($entriesCount < $employeeCount) {
            return redirect()->back()->with('error', 'Attendance is incomplete. Entries are missing for some employees. Please ensure all employees have attendance recorded.');
        }

        // Check for any incomplete entries (any cell that is null or empty)
        $incompleteEntries = AttendanceEntry::where('weekly_attendance_id', $attendance->id)
            ->where(function ($query) {
                $fields = ['mon_m', 'mon_a', 'tue_m', 'tue_a', 'wed_m', 'wed_a', 'thu_m', 'thu_a', 'fri_m', 'fri_a', 'sat_m', 'sat_a'];
                foreach ($fields as $field) {
                    $query->orWhereNull($field)->orWhere($field, '');
                }
            })
            ->exists();

        if ($incompleteEntries) {
            return redirect()->back()->with('error', 'Attendance is incomplete. All attendance cells must be filled for every employee before submitting.');
        }

        $attendance->update([
            'status' => AttendanceStatus::PENDING,
            'submitted_by' => Auth::id(),
        ]);

        ApprovalLog::create([
            'weekly_attendance_id' => $attendance->id,
            'user_id' => Auth::id(),
            'action' => 'submitted',
        ]);

        // Notify Manager and Admins
        $recipients = collect();

        // Add Department Manager if assigned
        if ($attendance->department && $attendance->department->manager) {
            $recipients->push($attendance->department->manager);
        } else {
            \Log::warning("No manager assigned to Department: " . ($attendance->department->name ?? 'Unknown') . " (ID: {$attendance->department_id})");
        }

        // Add all Admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->isEmpty()) {
            \Log::error("No admin users found in the system to notify about attendance submission.");
        }

        $recipients = $recipients->concat($admins)->unique('id');

        if ($recipients->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\AttendanceSubmitted($attendance));
            \Log::info("Attendance submission notification sent to " . $recipients->count() . " recipients.");
        } else {
            \Log::error("No recipients found for attendance submission notification (Attendance ID: {$attendance->id})");
        }

        return redirect()->back()->with('success', 'Attendance submitted for approval.');
    }
}
