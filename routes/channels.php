<?php

use App\Models\Team;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    return Team::findOrFail($teamId)->members()->where('user_id', $user->id)->exists();
});
