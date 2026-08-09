<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $teams;
    public $activeTeamId;
    public $unreadCount;

    public function __construct($activeTeamId = null)
    {
        $this->teams = auth()->user()->teams()->orderBy('name')->get();
        $this->activeTeamId = $activeTeamId;
        $this->unreadCount = auth()->user()->appNotifications()->whereNull('read_at')->count();
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
