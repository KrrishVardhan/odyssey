<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'team_id', 'type', 'status', 'title', 'description', 'raw_input',
        'submitted_by', 'submitter_email', 'reviewed_by',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isAnonymous(): bool
    {
        return is_null($this->submitted_by);
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
