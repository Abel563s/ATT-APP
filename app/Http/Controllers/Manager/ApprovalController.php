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
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }
        $status = $request->get('status');
        $departmentId = $request->get('department_id');

        // Managers see PENDING attendances for departments they manage
        // Admin sees PENDING_ADMIN (manager-approved) attendances
        $query = WeeklyAttendance::with(['department', 'submitter']);

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            // Admin and superadmin see both records pending manager approval and records pending admin approval
            $query->whereIn('status', [AttendanceStatus::PENDING, AttendanceStatus::PENDING_ADMIN]);

            if ($status) {
                $query->where('status', $status);
            }
            if ($departmentId) {
                $query->where('department_id', $departmentId);
            }
        } else {
            // Managers see records pending manager approval
            $managedDeptIds = $user->getResponsibleDepartmentIds();
            $query->where('status', AttendanceStatus::PENDING)
                ->whereIn('department_id', $managedDeptIds);

            if ($status) {
                $query->where('status', $status);
            }
        }

        $pendingAttendances = $query->orderBy('week_start_date', 'desc')->paginate(15);

        $awaitingManagerCount = 0;
        $awaitingAdminCount = 0;

        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $awaitingManagerCount = WeeklyAttendance::where('status', AttendanceStatus::PENDING)->count();
            $awaitingAdminCount = WeeklyAttendance::where('status', AttendanceStatus::PENDING_ADMIN)->count();
        }

        $departments = \App\Models\Department::all();

        // Debug: Log what we're getting
        \Log::info('Approval Index - User: ' . $user->id . ', Role: ' . $user->role . ', Pending Count: ' . $pendingAttendances->total());

        return view('manager.approvals.index', compact('pendingAttendances', 'awaitingManagerCount', 'awaitingAdminCount', 'departments'));
    }

    public function show(WeeklyAttendance $attendance)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }

        $attendance->load(['department', 'entries.employee', 'submitter', 'logs.user']);
        $codesMap = \App\Models\AttendanceCode::all()->keyBy('code');

        return view('manager.approvals.show', compact('attendance', 'codesMap'));
    }

    public function approve(Request $request, WeeklyAttendance $attendance)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }

        if ($user->isAdmin() && !$user->isSuperAdmin() && $attendance->status === AttendanceStatus::PENDING) {
            return redirect()->back()->with('error', 'This record requires manager approval before admin approval.');
        }

        // Safeguard: Prevent double approval or acting on rejected records
        if ($attendance->status === AttendanceStatus::APPROVED) {
            return redirect()->back()->with('error', 'This attendance record is already approved.');
        }

        if ($attendance->status === AttendanceStatus::REJECTED) {
            return redirect()->back()->with('error', 'Cannot approve a rejected record. It must be resubmitted.');
        }

        // Determine the new status based on user role
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $newStatus = AttendanceStatus::APPROVED;
            $message = 'Attendance approved successfully.';
        } else {
            // Manager approves, moves to pending admin approval
            if ($attendance->status !== AttendanceStatus::PENDING) {
                return redirect()->back()->with('error', 'Only records with "Pending Manager" status can be approved by a manager.');
            }
            $newStatus = AttendanceStatus::PENDING_ADMIN;
            $message = 'Attendance approved and forwarded to admin for final approval.';
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($attendance, $newStatus, $request, $user) {
            $attendance->update([
                'status' => $newStatus,
                'approved_by' => Auth::id(),
            ]);

            ApprovalLog::create([
                'weekly_attendance_id' => $attendance->id,
                'user_id' => Auth::id(),
                'action' => $user->isAdmin() || $user->isSuperAdmin() ? 'approved' : 'manager_approved',
                'comment' => $request->comment,
            ]);
        });

        // Notify Submitter
        try {
            if ($attendance->submitter) {
                $attendance->submitter->notify(new \App\Notifications\AttendanceStatusUpdated($attendance));
            }

            // Notify Admins (if manager approved or if admin approved)
            $admins = \App\Models\User::where('role', 'admin')
                ->where('id', '!=', Auth::id())
                ->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AttendanceStatusUpdated($attendance));
        } catch (\Exception $e) {
            \Log::error('Attendance approval notification failed: ' . $e->getMessage());
        }

        return redirect()->route('manager.approvals.index')->with('success', $message);
    }

    public function reject(Request $request, WeeklyAttendance $attendance)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isSuperAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized.');
        }
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // Safeguard
        if ($attendance->status === AttendanceStatus::APPROVED) {
            return redirect()->back()->with('error', 'Cannot reject an already approved record.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($attendance, $request) {
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
        });

        // Notify Submitter
        try {
            if ($attendance->submitter) {
                $attendance->submitter->notify(new \App\Notifications\AttendanceStatusUpdated($attendance));
            }
        } catch (\Exception $e) {
            \Log::error('Attendance rejection notification failed: ' . $e->getMessage());
        }

        return redirect()->route('manager.approvals.index')->with('success', 'Attendance rejected.');
    }
}
