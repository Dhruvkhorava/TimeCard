<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\DailyUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = 'employee';
        
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            $role = 'admin';
        }

        $stats = [
            'total_projects' => 0,
            'total_tasks' => 0,
            'total_users' => 0,
            'total_clients' => 0,
            'hours_this_month' => 0
        ];
        
        $recentProjects = collect();
        $timeline = collect();
        
        // Setup dates for charts (Last 7 days)
        $dates = [];
        $hoursData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subDays($i)->format('M d');
        }

        if ($role === 'admin') {
            $stats['total_projects'] = Project::count();
            $stats['total_tasks'] = Task::count();
            $stats['total_users'] = User::role('employee')->count();
            $stats['total_clients'] = User::role('client')->count();
            
            $recentProjects = Project::with(['client', 'tasks'])->latest()->take(5)->get();
            $timeline = DailyUpdate::with(['task', 'employee'])->latest()->take(6)->get();
            
            // Chart Data
            foreach ($dates as $date) {
                $dayStr = Carbon::parse($date)->format('Y-m-d');
                $hoursData[] = (float) DailyUpdate::whereDate('date', $dayStr)->sum('hours_spent');
            }
            

        } else { // Employee
            $stats['total_projects'] = Project::whereHas('tasks', function($q) use($user) {
                $q->whereHas('employees', function($sq) use($user) {
                    $sq->where('users.id', $user->id);
                })->orWhere('assigned_to', $user->id);
            })->count();
            
            $stats['total_tasks'] = Task::whereHas('employees', function($q) use($user) {
                $q->where('users.id', $user->id);
            })->orWhere('assigned_to', $user->id)->count();
            
            $stats['hours_this_month'] = (float) DailyUpdate::where('employee_id', $user->id)
                                    ->whereMonth('date', Carbon::now()->month)
                                    ->sum('hours_spent');
            
            $recentProjects = Project::whereHas('tasks', function($q) use($user) {
                $q->whereHas('employees', function($sq) use($user) {
                    $sq->where('users.id', $user->id);
                })->orWhere('assigned_to', $user->id);
            })->with('tasks')->latest()->take(5)->get();
            
            $timeline = DailyUpdate::where('employee_id', $user->id)->with(['task'])->latest()->take(6)->get();
            
            // Chart Data
            foreach ($dates as $date) {
                $dayStr = Carbon::parse($date)->format('Y-m-d');
                $hoursData[] = (float) DailyUpdate::where('employee_id', $user->id)->whereDate('date', $dayStr)->sum('hours_spent');
            }
        }

        $chartData = [
            'labels' => $dates,
            'hours' => $hoursData
        ];

        return view('dashboard.dashboard', compact('stats', 'recentProjects', 'timeline', 'chartData', 'role'));
    }
}
