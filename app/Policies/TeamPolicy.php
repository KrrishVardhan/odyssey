<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function manageMembers(User $user, Team $team): bool
    {
        return in_array($user->roleInTeam($team), ['leader', 'co_leader']);
    }

    // Fix for viewing mission tab
    public function viewTeamTasks(User $user, Team $team): bool
    {
        return $team->members()->where('user_id', $user->id)->exists();
    }

    // Reviewing submissions
    public function reviewSubmissions(User $user, Team $team): bool
    {
        return in_array($user->roleInTeam($team), ['leader', 'co_leader']);
    }

    // update the setting
    public function updateSettings(User $user, Team $team): bool
    {
        return $user->roleInTeam($team) === 'leader';
    }
    // kick a member
    public function kickMember(User $user, Team $team): bool
    {
        return in_array($user->roleInTeam($team), ['leader', 'co_leader']);
    }
    // manage products
    public function manageProducts(User $user, Team $team): bool
    {
        return $user->roleInTeam($team) === 'leader';
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return false;
    }
}
