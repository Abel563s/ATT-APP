<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use App\Exports\EmployeeExport;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'department']);

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status (new field)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } elseif ($request->filled('is_active')) {
            // Fallback for old toggle if still used
            $query->where('status', $request->boolean('is_active') ? 'active' : 'inactive');
        }

        // Search by name or employee ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Clone query for stats before pagination
        $statsQuery = clone $query;
        $totalFound = $statsQuery->count();
        $activeCount = (clone $statsQuery)->where('status', 'active')->count();
        $inactiveCount = (clone $statsQuery)->where('status', 'inactive')->count();
        $terminatedCount = (clone $statsQuery)->where('status', 'terminated')->count();

        $employees = $query->orderBy('created_at', 'desc')->paginate(20);

        $departments = Department::active()->get();

        return view('admin.employees.index', compact(
            'employees',
            'departments',
            'totalFound',
            'activeCount',
            'inactiveCount',
            'terminatedCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::active()->get();
        return view('admin.employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:admin,manager,user,department_attendance_user',
            'password' => 'required|string|min:8|confirmed',
            'site' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Generate EEC ID
            $lastEmployee = Employee::orderBy('id', 'desc')->first();
            $nextId = 1;
            $prefix = \App\Models\SystemSetting::where('key', 'employee_id_prefix')->first()?->value ?? 'EEC';

            if ($lastEmployee && preg_match('/' . $prefix . '-(\d+)/', $lastEmployee->employee_id, $matches)) {
                $nextId = (int) $matches[1] + 1;
            }
            $generatedId = $prefix . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            // Create user account
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'department_id' => $request->department_id,
                'employee_id' => $generatedId,
                'is_active' => true,
            ]);

            // Create employee record
            $employee = Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'employee_id' => $generatedId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'site' => $request->site,
                'position' => $request->position,
                'date_of_joining' => now(),
                'status' => 'active',
                'is_active' => true,
            ]);

            DB::commit();

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            try {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewEmployeeCreated($employee));
            } catch (\Exception $e) {
                Log::warning('Could not send notification: ' . $e->getMessage());
            }

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Employee creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'department']);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::active()->get();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id . '|unique:users,email,' . $employee->user_id,
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:admin,manager,user,department_attendance_user',
            'status' => 'required|in:active,inactive,terminated',
            'site' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update user account
            $employee->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
                'department_id' => $request->department_id,
                'is_active' => $request->status === 'active',
            ]);

            // Update employee record
            $employee->update([
                'department_id' => $request->department_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'site' => $request->site,
                'position' => $request->position,
                'status' => $request->status,
                'is_active' => $request->status === 'active',
            ]);

            DB::commit();

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Employee update failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    /**
     * Handle termination.
     */
    public function terminate(Request $request, Employee $employee)
    {
        $request->validate([
            'termination_reason' => 'required|string',
            'termination_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $employee->update([
                'status' => 'terminated',
                'is_active' => false,
                'termination_reason' => $request->termination_reason,
                'termination_date' => $request->termination_date,
            ]);

            $employee->user->update(['is_active' => false]);

            DB::commit();

            return redirect()->back()->with('success', 'Employee terminated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to terminate employee.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            // Logical decommissioning instead of hard delete
            $employee->update(['status' => 'inactive', 'is_active' => false]);
            $employee->user->update(['is_active' => false]);

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee deactivated successfully.');

        } catch (\Exception $e) {
            Log::error('Employee deactivation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to deactivate employee.');
        }
    }

    /**
     * Activate/reactivate an employee.
     */
    public function activate(Employee $employee)
    {
        try {
            $employee->update(['status' => 'active', 'is_active' => true]);
            $employee->user->update(['is_active' => true]);

            return redirect()->back()
                ->with('success', 'Employee activated successfully.');

        } catch (\Exception $e) {
            Log::error('Employee activation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to activate employee.');
        }
    }

    /**
     * Import employees preview.
     */
    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $data = Excel::toCollection(new EmployeeImport, $request->file('file'))->first();

            // Validate row-level (basic)
            $rows = $data->map(function ($row) {
                $errors = [];
                if (empty($row['first_name']))
                    $errors[] = "Missing First Name";
                if (empty($row['last_name']))
                    $errors[] = "Missing Last Name";
                if (empty($row['email']))
                    $errors[] = "Missing Email";
                if (empty($row['employee_id']))
                    $errors[] = "Missing Employee ID";
                if (empty($row['department']))
                    $errors[] = "Missing Department";

                // Check if exists
                if (!empty($row['employee_id']) && Employee::where('employee_id', $row['employee_id'])->exists()) {
                    $errors[] = "Employee ID already exists";
                }

                $row['errors'] = $errors;
                $row['is_valid'] = count($errors) === 0;
                return $row;
            });

            // Store file temporarily for actual import
            $path = $request->file('file')->store('temp');

            return view('admin.employees.import_preview', compact('rows', 'path'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to read file: ' . $e->getMessage());
        }
    }

    /**
     * Process import.
     */
    public function importProcess(Request $request)
    {
        $request->validate(['path' => 'required']);

        try {
            Excel::import(new EmployeeImport, storage_path('app/private/' . $request->path));
            return redirect()->route('admin.employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.employees.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export employees to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Employee::with(['user', 'department']);

        // Handle selected employees
        if ($request->filled('selected_ids')) {
            $ids = explode(',', $request->selected_ids);
            $query->whereIn('id', $ids);
        } else {
            // Apply current filters if any
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            }
        }

        $employees = $query->get();
        $date = now()->format('d M Y');

        $pdf = Pdf::loadView('admin.employees.export_pdf', compact('employees', 'date'));

        return $pdf->download('Employee_Registry_' . now()->format('YmdHis') . '.pdf');
    }
}
