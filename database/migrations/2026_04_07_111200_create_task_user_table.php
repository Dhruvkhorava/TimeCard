<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Migrate existing data
        $tasks = DB::table('tasks')->whereNotNull('assigned_to')->get();
        foreach ($tasks as $task) {
            DB::table('task_user')->insert([
                'task_id' => $task->id,
                'user_id' => $task->assigned_to,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Make assigned_to nullable for now to avoid breaking existing queries that might not have been updated yet
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable(false)->change();
        });
    }
};
