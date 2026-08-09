<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $fillable = ['user_id', 'team_id', 'task_id', 'type', 'message', 'read_at'];
    protected $casts = ['read_at' => 'datetime'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
