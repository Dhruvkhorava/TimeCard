<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailyUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display the employee reports page.
     */
    public function employeeReports(Request $request)
    {
        // Only allow superadmin and admin
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }

        $query = DailyUpdate::with(['employee', 'task.project']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $updates = $query->orderBy('date', 'desc')->get();
        $employees = User::role('employee')->get();

        return view('reports.employees', compact('updates', 'employees'));
    }

    /**
     * Export the employee reports as CSV.
     */
    public function exportEmployeeReports(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }

        $query = DailyUpdate::with(['employee', 'task.project']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $updates = $query->orderBy('date', 'desc')->get();

        $filename = "employee_reports_" . date('Y-m-d') . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Employee', 'Date', 'Project', 'Task Name', 'Start Time', 'End Time', 'Hours', 'Work Done', 'Notes');

        $callback = function() use($updates, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($updates as $update) {
                $row['Employee']   = $update->employee->name ?? 'N/A';
                $row['Date']       = $update->date;
                $row['Project']    = $update->task->project->name ?? 'N/A';
                $row['Task Name']  = $update->task->name ?? 'N/A';
                $row['Start Time'] = $update->start_time;
                $row['End Time']   = $update->end_time;
                $row['Hours']      = $update->hours_spent;
                $row['Work Done']  = $update->work_done;
                $row['Notes']      = $update->research_notes;

                fputcsv($file, array($row['Employee'], $row['Date'], $row['Project'], $row['Task Name'], $row['Start Time'], $row['End Time'], $row['Hours'], $row['Work Done'], $row['Notes']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display assignments grouped by employee (flat table).
     */
    public function employeeTasks(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['superadmin', 'admin'])) {
            abort(403);
        }

        // Fetch all employees and their assigned tasks with projects
        $employees = User::role('employee')->with(['tasks.project'])->get();

        return view('reports.tasks', compact('employees'));
    }
}
