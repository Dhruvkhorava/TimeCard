<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::role('employee')->get();
        return view('users.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('users.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        $userData = $request->only(['name', 'email', 'phone', 'address', 'designation', 'department', 'status']);
        $userData['password'] = bcrypt($request->password);

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $userData['profile_image'] = $imagePath;
        }

        $employee = User::create($userData);
        $employee->assignRole('employee');

        return redirect()->route('employee.index')->with('success', 'Employee created successfully');
    }

    public function edit(User $employee)
    {
        return view('users.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        $userData = $request->only(['name', 'email', 'phone', 'address', 'designation', 'department', 'status']);
        
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $userData['profile_image'] = $imagePath;
        }

        $employee->update($userData);

        return redirect()->route('employee.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(User $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully');
    }
}
