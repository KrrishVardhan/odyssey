<?php

namespace App\View\Components;

use App\Models\Team;
use Illuminate\View\Component;

class TeamNav extends Component
{
    public $team;
    public $active;
    public $hasUnreadSubmissions;

    public function __construct(Team $team, $active = null)
    {
        $this->team = $team;
        $this->active = $active;
        $this->hasUnreadSubmissions = in_array(auth()->user()->roleInTeam($team), ['leader', 'co_leader'])
            && $team->submissions()->where('status', 'submitted')->exists();
    }

    public function render()
    {
        return view('components.team-nav');
    }
}
