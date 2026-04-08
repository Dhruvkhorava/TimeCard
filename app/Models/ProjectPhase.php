<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPhase extends Model
{
    use SoftDeletes;
    protected $fillable = ['project_id', 'name', 'description', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'phase_id');
    }
}
