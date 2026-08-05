<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $teams;
    public $activeTeamId;

    public function __construct($activeTeamId = null)
    {
        $this->teams = auth()->user()->teams()->orderBy('name')->get();
        $this->activeTeamId = $activeTeamId;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
