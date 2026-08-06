<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $this->authorize('create', [Task::class, $team]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $team->tasks()->create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('teams.show', $team)->with('status', 'Task created.');
    }
}
