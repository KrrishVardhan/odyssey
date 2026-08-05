<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $this->authorize('manageMembers', $team);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User is already a member of this team.'], 422);
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return response()->json($team->load('members'), 201);
    }

    public function updateRole(Request $request, Team $team, User $user)
    {
        $this->authorize('manageMembers', $team);

        $validated = $request->validate([
            'role' => 'required|in:leader,co_leader,member',
        ]);

        $team->members()->updateExistingPivot($user->id, ['role' => $validated['role']]);

        return response()->json($team->load('members'));
    }
}
