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
}
