<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionReviewController extends Controller
{
    public function index(Request $request, Team $team)
    {
        $this->authorize('reviewSubmissions', $team);

        $submissions = $team->submissions()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with('tasks')
            ->latest()
            ->get();

        return view('teams.submissions', [
            'team' => $team,
            'submissions' => $submissions,
            'type' => $request->type ?? '',
            'status' => $request->status ?? '',
        ]);
    }

    public function reject(Request $request, Submission $submission)
    {
        $this->authorize('reviewSubmissions', $submission->team);

        $submission->update(['status' => 'rejected', 'reviewed_by' => $request->user()->id]);

        return back()->with('status', 'Submission rejected.');
    }

    public function createTasks(Request $request, Submission $submission)
    {
        $this->authorize('reviewSubmissions', $submission->team);

        $validated = $request->validate([
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.priority' => 'in:low,medium,high',
            'tasks.*.due_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $submission, $request) {
            foreach ($validated['tasks'] as $taskData) {
                $submission->team->tasks()->create([
                    'submission_id' => $submission->id,
                    'created_by' => $request->user()->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'priority' => $taskData['priority'] ?? 'medium',
                    'due_date' => $taskData['due_date'] ?? null,
                ]);
            }

            $submission->update([
                'status' => 'approved',
                'reviewed_by' => $request->user()->id,
            ]);
        });

        return back()->with('status', 'Tasks created and added to the board.');
    }
}
