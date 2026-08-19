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

        return view('public.products.show', ['team' => $team]);
    }
}
