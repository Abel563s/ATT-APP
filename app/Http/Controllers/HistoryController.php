<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\WeeklyAttendance;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = WeeklyAttendance::with(['department', 'submitter']);

        // Security: Managers and regular users see restricted data
        if (!$user->isAdmin()) {
            if ($user->isManager()) {
                $managedDeptIds = $user->getResponsibleDepartmentIds();
                if (empty($managedDeptIds)) {
                    return redirect()->back()->with('error', 'You do not manage any department.');
                }
                $query->whereIn('department_id', $managedDeptIds);
            } else {
                // Regular users only see history for their own department
                if (!$user->department_id) {
                    return redirect()->back()->with('error', 'You are not assigned to any department.');
                }
                $query->where('department_id', $user->department_id);
            }
        }

        // Apply filters
        if ($request->filled('department_id')) {
            // Admins can filter by any department, managers are already restricted
            if ($user->isAdmin()) {
                $query->where('department_id', $request->department_id);
            }
        }

        if ($request->filled('status')) {
            $statusMap = [
                'draft' => AttendanceStatus::DRAFT,
                'pending' => AttendanceStatus::PENDING,
                'approved' => AttendanceStatus::APPROVED,
                'rejected' => AttendanceStatus::REJECTED,
            ];
            if (isset($statusMap[$request->status])) {
                $query->where('status', $statusMap[$request->status]);
            }
        }

        if ($request->filled('from_date')) {
            $query->where('week_start_date', '>=', $request->from_date);
        }

        $records = $query->latest('updated_at')->paginate(20);

        $departments = collect();
        if ($user->isAdmin()) {
            $departments = Department::active()->orderBy('name')->get();
        }

        return view('attendance.history', compact('records', 'departments'));
    }

    public function show(WeeklyAttendance $attendance)
    {
        $user = Auth::user();

        // Security check
        if (!$user->isAdmin()) {
            if ($user->isManager()) {
                if (!in_array($attendance->department_id, $user->getResponsibleDepartmentIds())) {
                    abort(403, 'Unauthorized. This record does not belong to your managed departments.');
                }
            } else {
                if ($attendance->department_id !== $user->department_id) {
                    abort(403, 'Unauthorized. You can only view details for your own department.');
                }
            }
        }

        $attendance->load(['department', 'entries.employee', 'submitter', 'logs.user']);
        $codesMap = \App\Models\AttendanceCode::all()->keyBy('code');

        return view('attendance.show', compact('attendance', 'codesMap'));
    }
}
