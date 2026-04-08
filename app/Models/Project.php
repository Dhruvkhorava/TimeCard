<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $fillable = ['client_id', 'name', 'description', 'status'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function phases()
    {
        return $this->hasMany(ProjectPhase::class);
    }

    public function getProgressAttribute()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        return round(($completedTasks / $totalTasks) * 100);
    }
}
