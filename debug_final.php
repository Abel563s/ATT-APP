<?php
echo "MANAGERS AND THEIR DEPARTMENTS\n";
echo "============================\n";
foreach (App\Models\User::where('role', 'manager')->get() as $u) {
    echo "User: " . $u->name . " (ID: " . $u->id . ")\n";
    echo "  Home Dept: " . ($u->department->name ?? 'None') . " (ID: " . $u->department_id . ")\n";
    echo "  Assigned Manager of: " . $u->managedDepartments->pluck('name')->implode(', ') . "\n";
    echo "  Responsible for Dept IDs: " . implode(', ', $u->getResponsibleDepartmentIds()) . "\n";
    echo "----------------------------\n";
}

echo "\nPENDING ATTENDANCES\n";
echo "===================\n";
foreach (App\Models\WeeklyAttendance::where('status', App\Enums\AttendanceStatus::PENDING)->get() as $w) {
    echo "ID: " . $w->id . " | Dept: " . ($w->department->name ?? 'None') . " (ID: " . $w->department_id . ")\n";
}
