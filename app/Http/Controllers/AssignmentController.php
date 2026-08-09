<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('assign', $task);

        $validated = $request->validate(['user_id' => 'required|exists:users,id']);

        abort_unless(
            $task->team->members()->where('user_id', $validated['user_id'])->exists(),
            422,
            'That user is not a member of this team.'
        );

        abort_if(
            $task->assignments()->whereIn('status', ['pending', 'accepted', 'in_progress'])->exists(),
            422,
            'This task already has an active assignment.'
        );

        $task->assignments()->create([
            'user_id' => $validated['user_id'],
            'assigned_by' => $request->user()->id,
            'status' => 'pending',
        ]);
        AppNotification::create([
            'user_id' => $validated['user_id'],
            'team_id' => $task->team_id,
            'task_id' => $task->id,
            'type' => 'task_assigned',
            'message' => "{$request->user()->name} assigned you to \"{$task->title}\".",
        ]);

        return back()->with('status', 'Task assigned — awaiting their response.');
    }

    public function respond(Request $request, TaskAssignment $assignment)
    {
        abort_unless($assignment->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        $assignment->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'responded_at' => now(),
        ]);

        // notification
        if ($assignment->assigned_by !== $request->user()->id) {
            $message = $validated['status'] === 'accepted'
                ? "{$request->user()->name} accepted \"{$assignment->task->title}\"."
                : "{$request->user()->name} declined \"{$assignment->task->title}\"" . (($validated['rejection_reason'] ?? null) ? ": {$validated['rejection_reason']}" : '.');

            AppNotification::create([
                'user_id' => $assignment->assigned_by,
                'team_id' => $assignment->task->team_id,
                'task_id' => $assignment->task_id,
                'type' => $validated['status'] === 'accepted' ? 'task_accepted' : 'task_rejected',
                'message' => $message,
            ]);
        }

        return back()->with('status', $validated['status'] === 'accepted' ? 'Task accepted.' : 'Task declined.');
    }

    public function complete(Request $request, TaskAssignment $assignment)
    {
        abort_unless($assignment->user_id === $request->user()->id, 403);
        abort_unless(in_array($assignment->status, ['accepted', 'in_progress']), 422);

        $assignment->update(['status' => 'completed', 'completed_at' => now()]);

        // notify
        if ($assignment->assigned_by !== $request->user()->id) {
            AppNotification::create([
                'user_id' => $assignment->assigned_by,
                'team_id' => $assignment->task->team_id,
                'task_id' => $assignment->task_id,
                'type' => 'task_completed',
                'message' => "{$request->user()->name} completed \"{$assignment->task->title}\".",
            ]);
        }

        return back()->with('status', 'Task completed.');
    }
}
