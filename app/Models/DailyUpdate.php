<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyUpdate extends Model
{
    use SoftDeletes;
    protected $fillable = ['task_id', 'employee_id', 'date', 'start_time', 'end_time', 'hours_spent', 'work_done', 'research_notes'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
