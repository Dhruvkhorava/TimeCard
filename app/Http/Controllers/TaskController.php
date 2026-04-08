<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('employee')) {
            $tasks = Task::whereHas('employees', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->with('project')->get();
        } else {
            // Superadmin, Admin see all tasks
            $tasks = Task::with(['project', 'employees'])->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('client')) {
            abort(403);
        }

        $projects = Project::all();
        $employees = User::role('employee')->get();
        return view('tasks.create', compact('projects', 'employees'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasRole('client')) {
            abort(403);
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|array',
            'assigned_to.*' => 'exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'assigned_by' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'not_started',
        ]);

        $task->employees()->sync($request->assigned_to);

        return redirect()->route('tasks.index')->with('success', 'Task assigned successfully.');
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        $isAssigned = $task->employees()->where('users.id', $user->id)->exists();

        if (!$user->hasAnyRole(['superadmin', 'admin']) && 
            !$isAssigned && 
            ($task->project && $task->project->client_id !== $user->id)) {
            abort(403);
        }
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }
        $projects = Project::all();
        $employees = User::role('employee')->get();
        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        $isAssigned = $task->employees()->where('users.id', $user->id)->exists();

        if (!$user->hasAnyRole(['superadmin', 'admin']) && !$isAssigned) {
            abort(403);
        }

        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
                'assigned_to' => 'required|array',
                'assigned_to.*' => 'exists:users,id',
                'name' => 'required|string|max:255',
                'status' => 'required|in:not_started,in_progress,completed',
            ]);
            $task->update($request->only(['project_id', 'name', 'status']));
            $task->employees()->sync($request->assigned_to);
        } else {
            // Employees can only update status
            $request->validate([
                'status' => 'required|in:not_started,in_progress,completed',
            ]);
            $task->update($request->only('status'));
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }
}
