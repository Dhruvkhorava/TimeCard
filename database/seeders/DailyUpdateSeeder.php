<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\DailyUpdate;
use App\Models\User;
use Carbon\Carbon;

class DailyUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = User::role('employee')->first();
        $tasks = Task::where('assigned_to', $employee->id)->get();

        if (!$employee || $tasks->isEmpty()) {
            return;
        }

        foreach ($tasks as $task) {
            // Seed 3 days of work for each task
            for ($i = 0; $i < 3; $i++) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                
                $hours = rand(2, 6) + (rand(0, 1) * 0.5);
                $startHour = rand(8, 10);
                $startMinutes = rand(0, 1) == 1 ? 30 : 0;
                
                $startTime = Carbon::createFromTime($startHour, $startMinutes);
                $endTime = (clone $startTime)->addMinutes($hours * 60);
                
                DailyUpdate::create([
                    'task_id' => $task->id,
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                    'hours_spent' => $hours,
                    'work_done' => 'Completed the core requirements for ' . $task->name . ' on ' . $date . '. Added several features and fixed bugs related to this phase.',
                    'research_notes' => 'Researched best practices for ' . $task->name . '. Looked into documentation and community forums for standard implementations.',
                ]);
            }
        }
    }
}
