<?php

namespace App\Http\Controllers;

use App\Models\DailyUpdate;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Default to last 30 days if no dates are provided
        $from_date = $request->query('from_date', now()->subDays(30)->toDateString());
        $to_date = $request->query('to_date', now()->toDateString());

        $query = DailyUpdate::query();

        if ($user->hasRole('employee')) {
            $query->where('employee_id', $user->id);
        } else if ($user->hasRole('client')) {
            $query->whereHas('task.project', function ($q) use ($user) {
                $q->where('client_id', $user->id);
            });
        }

        // Apply date range filter
        if ($from_date && $to_date) {
            $query->whereBetween('date', [$from_date, $to_date]);
        } elseif ($from_date) {
            $query->whereDate('date', '>=', $from_date);
        } elseif ($to_date) {
            $query->whereDate('date', '<=', $to_date);
        }

        // Calculate total hours for the filtered results
        $total_hours = $query->sum('hours_spent');

        $updates = $query->with(['task.project', 'employee'])->orderBy('date', 'desc')->get();

        // Data for the 'Add Task' form (only if employee)
        $projects = [];
        $selected_task = null;
        if ($user->hasRole('employee')) {
            $projects = \App\Models\Project::whereHas('tasks', function ($q) use ($user) {
                $q->whereHas('employees', function ($sq) use ($user) {
                    $sq->where('users.id', $user->id);
                });
            })->get();
            $selected_task = $request->task_id ? Task::find($request->task_id) : null;
        }

        return view('daily_updates.index', compact('updates', 'from_date', 'to_date', 'total_hours', 'projects', 'selected_task'));
    }

    /**
     * AJAX endpoint to fetch tasks for a specific project assigned to the employee.
     */
    public function fetchTasksByProject($projectId)
    {
        $tasks = Task::where('project_id', $projectId)
            ->whereHas('employees', function ($q) {
                $q->where('users.id', Auth::id());
            })
            ->where('status', '!=', 'completed')
            ->get();
            
        return response()->json($tasks);
    }

    /**
     * AJAX endpoint to fetch daily updates for a specific date.
     */
    public function fetchByDate(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        
        $updates = DailyUpdate::with('task')->where('employee_id', Auth::id())
            ->where('date', $request->date)
            ->get();
            
        return response()->json($updates);
    }

    public function create(Request $request)
    {
        // Redirect to index since create form is now merged into index
        return redirect()->route('daily-updates.index', ['task_id' => $request->task_id]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('employee')) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'updates' => 'nullable|array',
            'updates.*.id' => 'nullable|exists:daily_updates,id',
            'updates.*.task_id' => 'required|exists:tasks,id',
            'updates.*.start_time' => 'required|date_format:H:i',
            'updates.*.end_time' => 'required|date_format:H:i|after:updates.*.start_time',
            'updates.*.work_done' => 'required|string',
        ]);

        \DB::transaction(function () use ($request) {
            $submittedIds = [];
            
            if ($request->has('updates')) {
                foreach ($request->updates as $update) {
                    // Verify task is assigned to this employee
                    $task = Task::where('id', $update['task_id'])
                                ->whereHas('employees', function ($q) {
                                    $q->where('users.id', Auth::id());
                                })
                                ->firstOrFail();

                    // Calculate hours spent
                    $start = Carbon::createFromFormat('H:i', $update['start_time']);
                    $end = Carbon::createFromFormat('H:i', $update['end_time']);
                    $hours_spent = $start->diffInMinutes($end) / 60;

                    $record = DailyUpdate::updateOrCreate(
                        ['id' => $update['id'] ?? null],
                        [
                            'task_id' => $update['task_id'],
                            'employee_id' => Auth::id(),
                            'date' => $request->date,
                            'start_time' => $update['start_time'],
                            'end_time' => $update['end_time'],
                            'hours_spent' => $hours_spent,
                            'work_done' => $update['work_done'],
                            'research_notes' => null, // Reset research notes as field is removed
                        ]
                    );
                    $submittedIds[] = $record->id;
                }
            }

            // Sync: Delete any existing records for this date/employee that were not submitted
            DailyUpdate::where('employee_id', Auth::id())
                ->where('date', $request->date)
                ->whereNotIn('id', $submittedIds)
                ->delete();
        });

        return redirect()->route('daily-updates.index')->with('success', 'Daily updates saved successfully.');
    }

    public function show(DailyUpdate $dailyUpdate)
    {
        return view('daily_updates.show', compact('dailyUpdate'));
    }

    public function edit(DailyUpdate $dailyUpdate)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $dailyUpdate->employee_id !== Auth::id()) {
            abort(403);
        }

        $tasks = Task::whereHas('employees', function ($q) {
            $q->where('users.id', Auth::id());
        })->get();
        return view('daily_updates.edit', compact('dailyUpdate', 'tasks'));
    }

    public function update(Request $request, DailyUpdate $dailyUpdate)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $dailyUpdate->employee_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'work_done' => 'required|string',
        ]);

        $start = Carbon::createFromFormat('H:i', $request->start_time);
        $end = Carbon::createFromFormat('H:i', $request->end_time);
        $hours_spent = $start->diffInMinutes($end) / 60;

        $dailyUpdate->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'hours_spent' => $hours_spent,
            'work_done' => $request->work_done,
            'research_notes' => null,
        ]);

        return redirect()->route('daily-updates.index')->with('success', 'Daily update updated successfully.');
    }

    public function destroy(DailyUpdate $dailyUpdate)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin']) && $dailyUpdate->employee_id !== Auth::id()) {
            abort(403);
        }

        $dailyUpdate->delete();
        return redirect()->route('daily-updates.index')->with('success', 'Daily update deleted.');
    }
}
