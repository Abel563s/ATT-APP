<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\WeeklyAttendance;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard with department overview.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirect admins to their specific dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $weekStartObj = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekStartDate = $weekStartObj->toDateString();

        // Resolve Department ID from User or Employee record
        $userDeptId = $user->department_id ?? $user->employee?->department_id;

        // For managers, prioritize the department they manage
        $managedDeptId = $user->managedDepartment?->id;

        $effectiveDeptId = $user->isManager() ? ($managedDeptId ?? $userDeptId) : $userDeptId;

        $stats = [
            'total_departments' => Department::active()->count(),
            'total_employees' => Employee::active()->count(),
            'pending_approvals' => ($user->isManager() && $managedDeptId)
                ? WeeklyAttendance::where('department_id', $managedDeptId)->where('status', AttendanceStatus::PENDING)->count()
                : 0,
            'my_department_status' => $effectiveDeptId ? WeeklyAttendance::where('department_id', $effectiveDeptId)
                ->where('week_start_date', $weekStartDate)
                ->first() : null,
        ];

        $departments = Department::active()->with(['manager', 'employees'])->get();

        $attendanceHistory = [];
        if ($effectiveDeptId) {
            $attendanceHistory = WeeklyAttendance::where('department_id', $effectiveDeptId)
                ->where('status', '!=', AttendanceStatus::DRAFT)
                ->latest('updated_at')
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'departments', 'weekStartObj', 'attendanceHistory'));
    }
}
