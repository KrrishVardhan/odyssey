<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, Team $team)
    {
        abort_unless($team->is_public, 404);

        $user = $request->user();

        if ($team->followers()->where('user_id', $user->id)->exists()) {
            $team->followers()->detach($user->id);
        } else {
            $team->followers()->attach($user->id);
        }

        return back();
    }
}
