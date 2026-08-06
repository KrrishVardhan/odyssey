<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamViewController extends Controller
{
    public function show(Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        return view('teams.show', ['team' => $team]);
    }
    public function mission(Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        $tasks = $team->tasks()->with(['assignments.assignee', 'creator'])->latest()->get();

        $columns = [
            'todo' => collect(),
            'in_progress' => collect(),
            'completed' => collect(),
        ];

        foreach ($tasks as $task) {
            $latest = $task->assignments->sortByDesc('created_at')->first();

            match (true) {
                $latest === null, $latest->status === 'pending', $latest->status === 'rejected' => $columns['todo']->push($task),
                in_array($latest->status, ['accepted', 'in_progress']) => $columns['in_progress']->push($task),
                $latest->status === 'completed' => $columns['completed']->push($task),
                default => $columns['todo']->push($task),
            };
        }

        return view('teams.mission', [
            'team' => $team,
            'columns' => $columns,
        ]);
    }

    public function workspace(Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        $assignments = auth()->user()->taskAssignments()
            ->whereHas('task', fn($q) => $q->where('team_id', $team->id))
            ->with('task')
            ->latest()
            ->get();

        $columns = [
            'pending' => collect(),
            'in_progress' => collect(),
            'completed' => collect(),
        ];

        foreach ($assignments as $assignment) {
            match (true) {
                $assignment->status === 'pending' => $columns['pending']->push($assignment),
                in_array($assignment->status, ['accepted', 'in_progress']) => $columns['in_progress']->push($assignment),
                $assignment->status === 'completed' => $columns['completed']->push($assignment),
                default => null, // rejected assignments drop off the board entirely
            };
        }

        return view('teams.workspace', ['team' => $team, 'columns' => $columns]);
    }
}
