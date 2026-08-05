<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskAssignmentController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('teams', [TeamController::class, 'index']);
    Route::post('teams', [TeamController::class, 'store']);
    Route::get('teams/{team}', [TeamController::class, 'show']);

    Route::post('teams/{team}/members', [TeamMemberController::class, 'store']);
    Route::patch('teams/{team}/members/{user}', [TeamMemberController::class, 'updateRole']);

    Route::get('teams/{team}/tasks', [TaskController::class, 'index']);
    Route::post('teams/{team}/tasks', [TaskController::class, 'store']);
    Route::get('my-tasks', [TaskController::class, 'myTasks']);

    Route::post('tasks/{task}/assign', [TaskAssignmentController::class, 'store']);
    Route::patch('assignments/{assignment}/respond', [TaskAssignmentController::class, 'respond']);
    Route::patch('assignments/{assignment}/complete', [TaskAssignmentController::class, 'complete']);
});
