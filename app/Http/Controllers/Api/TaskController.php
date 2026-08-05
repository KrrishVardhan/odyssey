<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        return $team->tasks()->with(['assignments.assignee', 'creator'])->latest()->get();
    }

    public function store(Request $request, Team $team)
    {
        $this->authorize('create', [Task::class, $team]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = $team->tasks()->create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return response()->json($task, 201);
    }

    public function myTasks(Request $request)
    {
        $assignments = $request->user()->taskAssignments()
            ->with('task.team')
            ->when($request->team_id, fn($q) => $q->whereHas('task', fn($q2) => $q2->where('team_id', $request->team_id)))
            ->latest()
            ->get();

        return response()->json($assignments);
    }
}
