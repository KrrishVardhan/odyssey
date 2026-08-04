<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = ['task_id', 'user_id', 'assigned_by', 'status', 'rejection_reason', 'responded_at', 'completed_at'];
    protected $casts = ['responded_at' => 'datetime', 'completed_at' => 'datetime'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
