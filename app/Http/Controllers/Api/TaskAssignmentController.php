<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskAssignmentController extends Controller
{
    // Updated store for self assign tasks
    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $isSelfAssign = (int) $validated['user_id'] === $request->user()->id;

        $this->authorize($isSelfAssign ? 'selfAssign' : 'assign', $task);

        $isMember = $task->team->members()->where('user_id', $validated['user_id'])->exists();
        if (! $isMember) {
            throw ValidationException::withMessages([
                'user_id' => ['This user is not a member of the team.'],
            ]);
        }

        $existing = $task->assignments()->where('user_id', $validated['user_id'])->whereIn('status', ['pending', 'accepted', 'in_progress'])->exists();
        if ($existing) {
            return response()->json(['message' => 'This user already has an active assignment on this task.'], 422);
        }

        $assignment = $task->assignments()->create([
            'user_id' => $validated['user_id'],
            'assigned_by' => $request->user()->id,
            'status' => 'pending',
        ]);

        return response()->json($assignment->load('assignee'), 201);
    }

    public function respond(Request $request, TaskAssignment $assignment)
    {
        if ($assignment->user_id !== $request->user()->id) {
            abort(403, 'This assignment does not belong to you.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ]);

        $assignment->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'responded_at' => now(),
        ]);

        return response()->json($assignment);
    }

    public function complete(Request $request, TaskAssignment $assignment)
    {
        if ($assignment->user_id !== $request->user()->id) {
            abort(403, 'This assignment does not belong to you.');
        }

        if (! in_array($assignment->status, ['accepted', 'in_progress'])) {
            return response()->json(['message' => 'Assignment must be accepted before it can be completed.'], 422);
        }

        $assignment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json($assignment);
    }
}
