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

        // Managers see PENDING attendances for departments they manage
        // Admin sees PENDING_ADMIN (manager-approved) attendances
        $query = WeeklyAttendance::with(['department', 'submitter']);

        if ($user->isAdmin()) {
            // Admin sees both records pending manager approval and records pending admin approval
            $query->whereIn('status', [AttendanceStatus::PENDING, AttendanceStatus::PENDING_ADMIN]);
        } else {
            // Managers see records pending manager approval
            $managedDeptIds = $user->getResponsibleDepartmentIds();
            $query->where('status', AttendanceStatus::PENDING)
                ->whereIn('department_id', $managedDeptIds);
        }

        $pendingAttendances = $query->orderBy('week_start_date', 'desc')->get();

        $awaitingManagerCount = 0;
        $awaitingAdminCount = 0;

        if ($user->isAdmin()) {
            $awaitingManagerCount = WeeklyAttendance::where('status', AttendanceStatus::PENDING)->count();
            $awaitingAdminCount = WeeklyAttendance::where('status', AttendanceStatus::PENDING_ADMIN)->count();
        }

        // Debug: Log what we're getting
        \Log::info('Approval Index - User: ' . $user->id . ', Role: ' . $user->role . ', Pending Count: ' . $pendingAttendances->count());

        return view('manager.approvals.index', compact('pendingAttendances', 'awaitingManagerCount', 'awaitingAdminCount'));
    }

    public function show(WeeklyAttendance $attendance)
    {
        $attendance->load(['department', 'entries.employee', 'submitter', 'logs.user']);
        $codesMap = \App\Models\AttendanceCode::all()->keyBy('code');

        return view('manager.approvals.show', compact('attendance', 'codesMap'));
    }

    public function approve(Request $request, WeeklyAttendance $attendance)
    {
        $user = Auth::user();

        if ($user->isAdmin() && $attendance->status === AttendanceStatus::PENDING) {
            return redirect()->back()->with('error', 'This record requires manager approval before admin approval.');
        }

        // Determine the new status based on user role
        if ($user->isAdmin()) {
            // Admin gives final approval
            $newStatus = AttendanceStatus::APPROVED;
            $message = 'Attendance approved successfully.';
        } else {
            // Manager approves, moves to pending admin approval
            $newStatus = AttendanceStatus::PENDING_ADMIN;
            $message = 'Attendance approved and forwarded to admin for final approval.';
        }

        $attendance->update([
            'status' => $newStatus,
            'approved_by' => Auth::id(),
        ]);

        ApprovalLog::create([
            'weekly_attendance_id' => $attendance->id,
            'user_id' => Auth::id(),
            'action' => $user->isAdmin() ? 'approved' : 'manager_approved',
            'comment' => $request->comment,
        ]);

        // Notify Submitter
        if ($attendance->submitter) {
            $attendance->submitter->notify(new \App\Notifications\AttendanceStatusUpdated($attendance));
        }

        // Notify Admins (if manager approved or if admin approved)
        $admins = \App\Models\User::where('role', 'admin')
            ->where('id', '!=', Auth::id())
            ->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AttendanceStatusUpdated($attendance));

        return redirect()->route('manager.approvals.index')->with('success', $message);
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
