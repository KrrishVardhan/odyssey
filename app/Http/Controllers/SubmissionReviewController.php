<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Team;
use Illuminate\Http\Request;

class SubmissionReviewController extends Controller
{
    public function index(Team $team)
    {
        $this->authorize('reviewSubmissions', $team);

        $submissions = $team->submissions()->latest()->get()->groupBy('status');

        return view('teams.submissions', ['team' => $team, 'submissions' => $submissions]);
    }

    public function updateStatus(Request $request, Submission $submission)
    {
        $this->authorize('reviewSubmissions', $submission->team);

        $validated = $request->validate(['status' => 'required|in:under_review,approved,rejected,resolved']);

        $submission->update([
            'status' => $validated['status'],
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Submission updated.');
    }
}
