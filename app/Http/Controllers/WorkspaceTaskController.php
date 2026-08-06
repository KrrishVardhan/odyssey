<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkspaceTaskController extends Controller
{
    public function store(Request $request, Team $team)
    {
        $this->authorize('createOwn', [Task::class, $team]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $team, $request) {
            $task = $team->tasks()->create([
                ...$validated,
                'created_by' => $request->user()->id,
            ]);

            $task->assignments()->create([
                'user_id' => $request->user()->id,
                'assigned_by' => $request->user()->id,
                'status' => 'accepted',
                'responded_at' => now(),
            ]);
        });

        return redirect()->route('teams.workspace', $team)->with('status', 'Task created.');
    }
}
