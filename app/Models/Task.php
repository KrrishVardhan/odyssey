<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['team_id', 'created_by', 'title', 'description', 'priority', 'due_date'];
    protected $casts = ['due_date' => 'date'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
