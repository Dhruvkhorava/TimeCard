<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = ['project_id', 'phase_id', 'assigned_to', 'assigned_by', 'name', 'description', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    public function employee() // Keeping for temporary backward compatibility
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function dailyUpdates()
    {
        return $this->hasMany(DailyUpdate::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }
}
