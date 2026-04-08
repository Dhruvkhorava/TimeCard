<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectPhase;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = User::role('employee')->first();
        $admin = User::role('admin')->first();
        $projects = Project::all();

        if (!$employee || !$admin || $projects->isEmpty()) {
            return;
        }

        foreach ($projects as $project) {
            $phases = ProjectPhase::where('project_id', $project->id)->get();

            foreach ($phases as $phase) {
                // Create 2 tasks per phase
                $tasks = [
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'assigned_to' => $employee->id,
                        'assigned_by' => $admin->id,
                        'name' => 'Initial ' . $phase->name . ' Setup',
                        'description' => 'Breaking down requirements and initializing project structures for ' . $phase->name . '.',
                        'status' => 'pending',
                    ],
                    [
                        'project_id' => $project->id,
                        'phase_id' => $phase->id,
                        'assigned_to' => $employee->id,
                        'assigned_by' => $admin->id,
                        'name' => $phase->name . ' Documentation',
                        'description' => 'Creating documentation and preparing reports for the ' . $phase->name . ' phase.',
                        'status' => 'pending',
                    ],
                ];

                foreach ($tasks as $task) {
                    $newTask = Task::create($task);
                    $newTask->employees()->attach($employee->id);
                }
            }
        }
    }
}
