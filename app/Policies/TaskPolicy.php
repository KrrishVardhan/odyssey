<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function viewTeamTasks(User $user, Team $team): bool
    {
        return $team->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user, Team $team): bool
    {
        return in_array($user->roleInTeam($team), ['leader', 'co_leader']);
    }

    public function assign(User $user, Task $task): bool
    {
        return in_array($user->roleInTeam($task->team), ['leader', 'co_leader']);
    }
}
