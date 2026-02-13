<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Exports\AttendanceHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        $pendingApprovals = \App\Models\WeeklyAttendance::whereIn('status', [
            \App\Enums\AttendanceStatus::PENDING,
            \App\Enums\AttendanceStatus::PENDING_ADMIN
        ])->count();
        $totalEmployees = \App\Models\Employee::active()->count();
        $totalDepartments = \App\Models\Department::active()->count();

        $recentRecords = \App\Models\WeeklyAttendance::with(['department', 'submitter'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'total_employees' => $totalEmployees,
                'total_departments' => $totalDepartments,
                'pending_approvals' => $pendingApprovals,
            ],
            'departmentStats' => [],
            'recentRecords' => $recentRecords,
            'pendingApprovals' => $pendingApprovals,
            'skipDetection' => [],
            'monthlyTrend' => [],
            'startDate' => now(),
            'endDate' => now(),
        ]);
    }

    public function report(Request $request)
    {
        $approvedRecords = \App\Models\WeeklyAttendance::where('status', \App\Enums\AttendanceStatus::APPROVED)
            ->with(['department', 'entries'])
            ->get();

        $codes = \App\Models\AttendanceCode::all();
        $codesMap = $codes->keyBy('code');

        $totalApprovedAttendance = 0;
        $presentCount = 0;

        foreach ($codes as $code) {
            $summary[$code->code] = [
                'label' => $code->label,
                'count' => 0,
                'color' => $code->bg_color,
                'text_color' => $code->text_color
            ];
        }

        $fields = ['mon_m', 'mon_a', 'tue_m', 'tue_a', 'wed_m', 'wed_a', 'thu_m', 'thu_a', 'fri_m', 'fri_a', 'sat_m', 'sat_a'];

        $departmentStats = [];
        $trendData = [];

        foreach ($approvedRecords as $record) {
            $deptName = $record->department->name;
            if (!isset($departmentStats[$deptName])) {
                $departmentStats[$deptName] = ['total' => 0, 'present' => 0];
            }

            $weekKey = $record->week_start_date->format('Y-W');
            if (!isset($trendData[$weekKey])) {
                $trendData[$weekKey] = ['week' => $record->week_start_date->format('M d'), 'present' => 0, 'total' => 0];
            }

            foreach ($record->entries as $entry) {
                foreach ($fields as $field) {
                    $val = $entry->{$field};
                    if ($val) {
                        if (isset($summary[$val])) {
                            $summary[$val]['count']++;
                        }

                        $departmentStats[$deptName]['total']++;
                        $trendData[$weekKey]['total']++;

                        if ($val === 'P') {
                            $departmentStats[$deptName]['present']++;
                            $trendData[$weekKey]['present']++;
                            $presentCount++;
                        }
                        $totalApprovedAttendance++;
                    }
                }
            }
        }

        $avgAttendance = $totalApprovedAttendance > 0 ? ($presentCount / $totalApprovedAttendance) * 100 : 0;
        // Calculate percentages
        foreach ($departmentStats as $name => &$stats) {
            $stats['percentage'] = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100, 1) : 0;
        }

        foreach ($trendData as &$data) {
            $data['percentage'] = $data['total'] > 0 ? round(($data['present'] / $data['total']) * 100, 1) : 0;
        }

        $trendData = array_values(collect($trendData)->sortBy('week')->toArray());

        // Extra Intelligence for report
        $intelligence = [
            'active_workers' => \App\Models\Employee::active()->count(),
            'total_workers' => \App\Models\Employee::count(),
            'compliance_rate' => \App\Models\WeeklyAttendance::count() > 0
                ? round((\App\Models\WeeklyAttendance::where('status', \App\Enums\AttendanceStatus::APPROVED)->count() / \App\Models\WeeklyAttendance::count()) * 100, 1)
                : 100,
            'avg_presence' => round($avgAttendance, 1),
            'total_nodes_scanned' => collect($departmentStats)->sum('total')
        ];

        return view('admin.reports.attendance', [
            'summary' => $summary,
            'departmentStats' => $departmentStats,
            'trendData' => $trendData,
            'codes' => $codes,
            'intelligence' => $intelligence
        ]);
    }

    protected function getHistoryQuery(Request $request)
    {
        $query = \App\Models\WeeklyAttendance::with(['department', 'submitter', 'approver']);

        // Apply filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $statusMap = [
                'pending' => \App\Enums\AttendanceStatus::PENDING,
                'approved' => \App\Enums\AttendanceStatus::APPROVED,
                'rejected' => \App\Enums\AttendanceStatus::REJECTED,
            ];
            if (isset($statusMap[$request->status])) {
                $query->where('status', $statusMap[$request->status]);
            }
        }

        if ($request->filled('from_date')) {
            $query->where('week_start_date', '>=', $request->from_date);
        }

        return $query->latest('updated_at');
    }

    public function history(Request $request)
    {
        $records = $this->getHistoryQuery($request)->paginate(20);
        $departments = \App\Models\Department::active()->orderBy('name')->get();

        return view('admin.attendance.history', compact('records', 'departments'));
    }

    public function exportHistory(Request $request)
    {
        $weeklyRecords = $this->getHistoryQuery($request)->with(['entries.employee', 'department'])->get();
        $entries = collect();

        foreach ($weeklyRecords as $record) {
            foreach ($record->entries as $entry) {
                // Attach relevant parent data for the export
                $entry->week_start_date = $record->week_start_date;
                $entry->department_name = $record->department->name ?? 'Unassigned';
                $entry->weekly_status = ucfirst($record->status->value);
                $entries->push($entry);
            }
        }

        return Excel::download(new AttendanceHistoryExport($entries), 'Attendance_Detailed_History_' . now()->format('YmdHis') . '.xlsx');
    }

    public function exportHistoryPdf(Request $request)
    {
        $weeklyRecords = $this->getHistoryQuery($request)->with(['entries.employee', 'department'])->get();
        $entries = collect();

        foreach ($weeklyRecords as $record) {
            foreach ($record->entries as $entry) {
                $entry->week_start_date = $record->week_start_date;
                $entry->department_name = $record->department->name ?? 'Unassigned';
                $entry->weekly_status = ucfirst($record->status->value);
                $entries->push($entry);
            }
        }

        $date = now()->format('d M Y');
        $pdf = Pdf::loadView('admin.attendance.export_pdf', compact('entries', 'date'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Attendance_History_' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * Delete an attendance record (admin only)
     */
    public function destroyAttendance(\App\Models\WeeklyAttendance $attendance)
    {
        try {
            // Force delete (permanent) to avoid unique constraint issues when recreating
            // The cascading deletes will also permanently remove related entries and logs
            $attendance->forceDelete();

            return redirect()->back()->with('success', 'Attendance record permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete attendance record: ' . $e->getMessage());
        }
    }
}
