<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['superadmin', 'admin', 'employee', 'client'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create default superadmin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'designation' => 'Super Administrator',
            'department' => 'Management',
            'address' => '123 Main St, Tech City, 10001',
            'status' => 1,
        ]);

        $superAdmin->assignRole('superadmin');

        // Optional: Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'designation' => 'Project Manager',
            'department' => 'Operations',
            'address' => '456 Elm St, Business Park, 10002',
            'status' => 1,
        ]);
        $admin->assignRole('admin');

        // Optional: Create an employee user
        $employee = User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'phone' => '1122334455',
            'designation' => 'Software Engineer',
            'department' => 'Engineering',
            'address' => '789 Oak Ave, Innovation Hub, 10003',
            'status' => 1,
        ]);
        $employee->assignRole('employee');

        // Optional: Create a client user
        $client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'phone' => '5544332211',
            'designation' => 'Client Contact',
            'department' => 'External',
            'address' => '101 Pine Blvd, Corporate Center, 10004',
            'status' => 1,
        ]);
        $client->assignRole('client');
    }
}
