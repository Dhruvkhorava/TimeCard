<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::role('admin')->get();
        return view('users.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('users.admins.create');
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

        $admin = User::create($userData);
        $admin->assignRole('admin');

        return redirect()->route('admin.index')->with('success', 'Admin created successfully');
    }

    public function edit(User $admin)
    {
        return view('users.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
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

        $admin->update($userData);

        return redirect()->route('admin.index')->with('success', 'Admin updated successfully');
    }

    public function destroy(User $admin)
    {
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully');
    }
}
