<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\ImageKitService;
use Illuminate\Http\Request;

class TeamSettingsController extends Controller
{
    public function show(Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        return view('teams.settings', ['team' => $team]);
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('updateSettings', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $team->update($validated);

        return back()->with('status', 'Team updated.');
    }

    public function updateIcon(Request $request, Team $team, ImageKitService $imageKit)
    {
        $this->authorize('updateSettings', $team);

        $request->validate(['icon' => 'required|image|max:2048']);

        $team->update(['icon_path' => $imageKit->upload($request->file('icon'), '/team-icons')]);

        return back()->with('status', 'Team icon updated.');
    }

    public function updateRole(Request $request, Team $team, \App\Models\User $user)
    {
        $this->authorize('kickMember', $team);

        abort_if($user->id === $team->owner_id, 403, 'The team owner\'s role cannot be changed.');

        $validated = $request->validate(['role' => 'required|in:co_leader,member']);

        $team->members()->updateExistingPivot($user->id, ['role' => $validated['role']]);

        return back()->with('status', 'Role updated.');
    }

    public function kick(Request $request, Team $team, \App\Models\User $user)
    {
        $this->authorize('kickMember', $team);

        abort_if($user->id === $team->owner_id, 403, 'The team owner cannot be removed.');

        $team->members()->detach($user->id);

        return back()->with('status', 'Member removed.');
    }
}
