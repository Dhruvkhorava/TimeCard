<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TaskAttachment;

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
            'attachments.*' => 'nullable|file|max:10240', // 10MB limit
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'assigned_by' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'not_started',
        ]);

        $task->employees()->sync($request->assigned_to);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $task->attachments()->create([
                    'file_path' => $path,
                    'file_name' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

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
                'description' => 'nullable|string',
                'status' => 'required|in:not_started,in_progress,completed',
                'attachments.*' => 'nullable|file|max:10240',
            ]);
            $task->update($request->only(['project_id', 'name', 'description', 'status']));
            $task->employees()->sync($request->assigned_to);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    $task->attachments()->create([
                        'file_path' => $path,
                        'file_name' => basename($path),
                        'original_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
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

    public function downloadAttachment(TaskAttachment $attachment)
    {
        $user = Auth::user();
        $task = $attachment->task;
        $isAssigned = $task->employees()->where('users.id', $user->id)->exists();

        if (!$user->hasAnyRole(['superadmin', 'admin']) && 
            !$isAssigned && 
            ($task->project && $task->project->client_id !== $user->id)) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }

    public function viewAttachment(TaskAttachment $attachment)
    {
        $user = Auth::user();
        $task = $attachment->task;
        $isAssigned = $task->employees()->where('users.id', $user->id)->exists();

        if (!$user->hasAnyRole(['superadmin', 'admin']) && 
            !$isAssigned && 
            ($task->project && $task->project->client_id !== $user->id)) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($attachment->file_path);
        
        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"'
        ]);
    }

    public function deleteAttachment(TaskAttachment $attachment)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}
