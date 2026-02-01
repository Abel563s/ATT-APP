<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('manager')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.departments.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department created.');
    }

    public function edit(Department $department)
    {
        $managers = User::where('role', 'manager')->get();
        return view('admin.departments.edit', compact('department', 'managers'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department updated.');
    }
}
