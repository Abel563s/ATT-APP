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

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
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

        Log::info('Employee Index Request', $request->all());
        Log::info('Employee Query SQL', [$query->toSql(), $query->getBindings()]);
        Log::info('Employee Query Count', [$query->count()]);

        $employees = $query->orderBy('created_at', 'desc')->paginate(20);

        $departments = Department::active()->get();

        return view('admin.employees.index', compact('employees', 'departments'));
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
                'is_active' => false,
            ]);

            // Create employee record
            $employee = Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'employee_id' => $generatedId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'date_of_joining' => now(),
                'is_active' => false,
            ]);

            DB::commit();

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewEmployeeCreated($employee));

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Employee creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create employee. Please try again.');
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
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update user account
            $employee->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
                'department_id' => $request->department_id,
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Update employee record
            $employee->update([
                'department_id' => $request->department_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'is_active' => $request->boolean('is_active', true),
            ]);

            DB::commit();

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Employee update failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update employee. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            // Soft delete or deactivate instead of hard delete
            $employee->update(['is_active' => false]);
            $employee->user->update(['is_active' => false]);

            return redirect()->route('admin.employees.index')
                ->with('success', 'Employee deactivated successfully.');

        } catch (\Exception $e) {
            Log::error('Employee deactivation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to deactivate employee. Please try again.');
        }
    }

    /**
     * Activate/reactivate an employee.
     */
    public function activate(Employee $employee)
    {
        try {
            $employee->update(['is_active' => true]);
            $employee->user->update(['is_active' => true]);

            return redirect()->back()
                ->with('success', 'Employee activated successfully.');

        } catch (\Exception $e) {
            Log::error('Employee activation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to activate employee. Please try again.');
        }
    }
}
