<?php

namespace App\Http\Controllers;

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
            return back()->withErrors(['email' => 'This user is already a member of the team.']);
        }

        $team->members()->attach($user->id, [
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return back()->with('status', "{$user->name} added to the team.");
    }
}
