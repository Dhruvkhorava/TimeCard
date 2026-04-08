<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::role('client')->get();
        
        if ($clients->isEmpty()) {
            return;
        }

        $projects = [
            [
                'name' => 'TaskFlow Pro',
                'description' => 'A comprehensive project management system for creative teams.',
                'status' => 'active',
            ],
            [
                'name' => 'Elite HR Portal',
                'description' => 'Human resource management system with employee engagement features.',
                'status' => 'active',
            ],
            [
                'name' => 'Swift Logistics App',
                'description' => 'Mobile application for tracking and managing logistics in real-time.',
                'status' => 'pending',
            ],
            [
                'name' => 'Nexus CRM',
                'description' => 'Customer relationship management tool tailored for sales teams.',
                'status' => 'active',
            ],
            [
                'name' => 'Quantum Analytics',
                'description' => 'Data analytics and visualization dashboard for enterprise clients.',
                'status' => 'completed',
            ]
        ];

        foreach ($projects as $project) {
            $project['client_id'] = $clients->random()->id;
            Project::create($project);
        }
    }
}
