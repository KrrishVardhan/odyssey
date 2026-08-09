<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        $messages = $team->messages()
            ->with('user')
            ->latest('id')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return view('teams.chat', [
            'team' => $team,
            'messages' => $messages,
            'oldestId' => $messages->first()?->id,
        ]);
    }

    public function older(Request $request, Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        $request->validate(['before' => 'required|integer']);

        $messages = $team->messages()
            ->with('user')
            ->where('id', '<', $request->before)
            ->latest('id')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'body' => $m->body,
                'user_name' => $m->user->name,
                'is_me' => $m->user_id === $request->user()->id,
                'created_at' => $m->created_at->format('g:i A'),
            ]),
            'has_more' => $messages->isNotEmpty(),
        ]);
    }

    public function store(Request $request, Team $team)
    {
        $this->authorize('viewTeamTasks', $team);

        $validated = $request->validate(['body' => 'required|string|max:2000']);

        $message = $team->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'user_name' => $request->user()->name,
            'is_me' => true,
            'created_at' => $message->created_at->format('g:i A'),
        ]);
    }
}
