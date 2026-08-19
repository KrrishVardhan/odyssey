<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Team;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request, Team $team)
    {
        abort_unless($team->is_public, 404);

        $validated = $request->validate([
            'type' => 'required|in:bug,feature,feedback',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'email' => 'nullable|email',
        ]);

        Submission::create([
            'team_id' => $team->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'raw_input' => $validated['description'],
            'submitted_by' => auth()->id(),
            'submitter_email' => auth()->id() ? null : ($validated['email'] ?? null),
        ]);

        return back()->with('status', 'Thanks — we\'ve received it.');
    }
}
