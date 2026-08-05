<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->teams()->withPivot('role')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = DB::transaction(function () use ($validated, $request) {
            $team = Team::create([
                'owner_id' => $request->user()->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
                'description' => $validated['description'] ?? null,
            ]);

            $team->members()->attach($request->user()->id, [
                'role' => 'leader',
                'joined_at' => now(),
            ]);

            return $team;
        });

        return response()->json($team->load('members'), 201);
    }

    public function show(Request $request, Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        return $team->load('members');
    }
}
