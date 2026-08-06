<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function create(User $user, Team $team): bool
    {
        return in_array($user->roleInTeam($team), ['leader', 'co_leader']);
    }

    public function assign(User $user, Task $task): bool
    {
        return in_array($user->roleInTeam($task->team), ['leader', 'co_leader']);
    }

    // for the self assign tasks
    public function selfAssign(User $user, Task $task): bool
    {
        return $task->team->members()->where('user_id', $user->id)->exists();
    }
    public function createOwn(User $user, Team $team): bool
    {
        return $team->members()->where('user_id', $user->id)->exists();
    }
}
