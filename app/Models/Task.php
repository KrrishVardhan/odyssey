<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['team_id', 'created_by', 'title', 'description', 'priority', 'due_date'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }
}
