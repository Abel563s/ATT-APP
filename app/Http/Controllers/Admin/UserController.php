<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'manager', 'user', 'department_attendance_user'])],
            'department_id' => ['nullable', 'exists:departments,id'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // If user has linked employee, ensure email is unique in employees table too
        if ($user->employee) {
            $rules['email'][] = Rule::unique('employees')->ignore($user->employee->id);
        }

        $validated = $request->validate($rules);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'department_id' => $validated['department_id'],
            'is_active' => $validated['is_active'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Synchronize with Employee record if it exists
        if ($user->employee) {
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $user->employee->update([
                'department_id' => $validated['department_id'],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'is_active' => $validated['is_active'],
            ]);
        }

        if ($user->role === 'manager' && $user->department_id) {
            // Unset this user from any other departments they might have managed
            Department::where('manager_id', $user->id)->update(['manager_id' => null]);
            // Set this user as manager of the selected department
            Department::where('id', $user->department_id)->update(['manager_id' => $user->id]);
        } elseif ($user->role !== 'manager') {
            // If they are no longer a manager, unset them from any departments they managed
            Department::where('manager_id', $user->id)->update(['manager_id' => null]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User profile updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // Synchronize with Employee record if it exists
        if ($user->employee) {
            $user->employee->update(['is_active' => $user->is_active]);
        }

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')->with('success', "User {$status} successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own session.');
        }

        // Delete linked employee if exists
        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User node purged successfully.');
    }
}
