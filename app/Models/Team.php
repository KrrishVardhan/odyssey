<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon_path', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }


    public function followers()
    {
        return $this->belongsToMany(User::class, 'team_followers')->withTimestamps();
    }
}
