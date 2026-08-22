<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Team;

class ProductController extends Controller
{
    public function index()
    {
        $teams = Team::where('is_public', true)->orderBy('name')->get();

        return view('public.products.index', ['teams' => $teams]);
    }

    public function show(Team $team)
    {
        abort_unless($team->is_public, 404);

        $isFollowing = auth()->check() && $team->followers()->where('user_id', auth()->id())->exists();

        $stats = [
            'requests' => $team->submissions()->count(),
            'planned' => $team->submissions()->where('status', 'approved')->count(),
            'shipped' => $team->submissions()->where('status', 'resolved')->count(),
        ];

        return view('public.products.show', [
            'team' => $team,
            'isFollowing' => $isFollowing,
            'followerCount' => $team->followers()->count(),
            'stats' => $stats,
        ]);
    }
}
