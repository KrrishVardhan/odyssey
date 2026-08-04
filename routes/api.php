<?php

use App\Http\Controllers\Api\TaskAssignmentController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TeamMemberController;
use Illuminate\Routing\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('teams', TeamController::class)->only(['index', 'store', 'show']);

    Route::post('teams/{team}/members', [TeamMemberController::class, 'store']);
    Route::patch('teams/{team}/members/{user}', [TeamMemberController::class, 'updateRole']);

    Route::get('teams/{team}/tasks', [TaskController::class, 'index']);       // Mission tab
    Route::post('teams/{team}/tasks', [TaskController::class, 'store']);
    Route::get('my-tasks', [TaskController::class, 'myTasks']);               // My Workspace tab

    Route::post('tasks/{task}/assign', [TaskAssignmentController::class, 'store']);
    Route::patch('assignments/{assignment}/respond', [TaskAssignmentController::class, 'respond']);
    Route::patch('assignments/{assignment}/complete', [TaskAssignmentController::class, 'complete']);
});
