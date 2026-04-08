<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\ProjectPhase;

class ProjectPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            return;
        }

        foreach ($projects as $project) {
            $phases = [
                ['name' => 'Planning', 'description' => 'Requirement gathering and project scoping.'],
                ['name' => 'Design', 'description' => 'UI/UX design and architecture planning.'],
                ['name' => 'Development', 'description' => 'Core development and implementation phase.'],
                ['name' => 'QA & Testing', 'description' => 'Quality assurance, bug fixing, and user testing.'],
            ];

            foreach ($phases as $phase) {
                ProjectPhase::create([
                    'project_id' => $project->id,
                    'name' => $phase['name'],
                    'description' => $phase['description'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
