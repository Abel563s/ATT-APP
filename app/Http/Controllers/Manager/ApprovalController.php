<?php

namespace App\Http\Controllers\Manager;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Models\WeeklyAttendance;
use App\Models\ApprovalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Managers see attendances for departments they manage
        // Admin sees everything
        $query = WeeklyAttendance::with(['department', 'submitter'])
            ->where('status', AttendanceStatus::PENDING);

        if (!$user->isAdmin()) {
            $managedDeptIds = $user->getResponsibleDepartmentIds();
            $query->whereIn('department_id', $managedDeptIds);
        }

        $pendingAttendances = $query->orderBy('week_start_date', 'desc')->get();

        // Debug: Log what we're getting
        \Log::info('Approval Index - User: ' . $user->id . ', Role: ' . $user->role . ', Pending Count: ' . $pendingAttendances->count());

        return view('manager.approvals.index', compact('pendingAttendances'));
    }

    public function show(WeeklyAttendance $attendance)
    {
        $attendance->load(['department', 'entries.employee', 'submitter', 'logs.user']);
        $codesMap = \App\Models\AttendanceCode::all()->keyBy('code');

        return view('manager.approvals.show', compact('attendance', 'codesMap'));
    }

    public function approve(Request $request, WeeklyAttendance $attendance)
    {
        $attendance->update([
            'status' => AttendanceStatus::APPROVED,
            'approved_by' => Auth::id(),
        ]);

        ApprovalLog::create([
            'weekly_attendance_id' => $attendance->id,
            'user_id' => Auth::id(),
            'action' => 'approved',
            'comment' => $request->comment,
        ]);

        // Notify Submitter
        if ($attendance->submitter) {
            $attendance->submitter->notify(new \App\Notifications\AttendanceStatusUpdated($attendance));
        }

        return redirect()->route('manager.approvals.index')->with('success', 'Attendance approved successfully.');
    }

    public function reject(Request $request, WeeklyAttendance $attendance)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $attendance->update([
            'status' => AttendanceStatus::REJECTED,
            'rejection_reason' => $request->comment,
        ]);

        ApprovalLog::create([
            'weekly_attendance_id' => $attendance->id,
            'user_id' => Auth::id(),
            'action' => 'rejected',
            'comment' => $request->comment,
        ]);

        // Notify Submitter
        if ($attendance->submitter) {
            $attendance->submitter->notify(new \App\Notifications\AttendanceStatusUpdated($attendance));
        }

        return redirect()->route('manager.approvals.index')->with('success', 'Attendance rejected.');
    }
}
