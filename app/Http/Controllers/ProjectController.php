<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('client')) {
            $projects = Project::where('client_id', $user->id)->get();
        } else {
            // Superadmin, Admin, Employee see all projects
            $projects = Project::with('client')->get();
        }

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin', 'client'])) {
            abort(403);
        }

        $clients = [];
        if (Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            $clients = User::role('client')->get();
        }

        return view('projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin', 'client'])) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phases' => 'nullable|array',
            'phases.*' => 'required|string|max:255',
        ];

        if (Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            $rules['client_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        \DB::transaction(function () use ($request) {
            $clientId = Auth::user()->hasRole('client') ? Auth::id() : $request->client_id;
            $project = Project::create([
                'client_id' => $clientId,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            if ($request->has('phases')) {
                foreach ($request->phases as $phaseName) {
                    $project->phases()->create([
                        'name' => $phaseName,
                        'status' => 'pending',
                    ]);
                }
            }
        });

        return redirect()->route('projects.index')->with('success', 'Project and phases created successfully.');
    }

    public function show(Project $project)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin', 'employee']) && $project->client_id !== Auth::id()) {
            abort(403);
        }

        $project->load(['phases.tasks.employee', 'client']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $project->client_id !== Auth::id()) {
            abort(403);
        }
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $project->client_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,active,completed',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $project->client_id !== Auth::id()) {
            abort(403);
        }

        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
