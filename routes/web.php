<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamViewController;
use App\Http\Controllers\WorkspaceTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // teams
    Route::get('/teams/{team}', [TeamViewController::class, 'mission'])->name('teams.show');
    Route::get('/teams/{team}/workspace', [TeamViewController::class, 'workspace'])->name('teams.workspace');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/tasks', [TaskController::class, 'store'])->name('teams.tasks.store');

    Route::post('/teams/{team}/workspace/tasks', [WorkspaceTaskController::class, 'store'])->name('teams.workspace.tasks.store');

    Route::post('/tasks/{task}/assign', [AssignmentController::class, 'store'])->name('tasks.assign');
    Route::patch('/assignments/{assignment}/respond', [AssignmentController::class, 'respond'])->name('assignments.respond');
    Route::patch('/assignments/{assignment}/complete', [AssignmentController::class, 'complete'])->name('assignments.complete');

    // add member
    Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('teams.members.store');

    // chat
    Route::get('/teams/{team}/chat', [ChatController::class, 'show'])->name('teams.chat');
    Route::get('/teams/{team}/chat/older', [ChatController::class, 'older'])->name('teams.chat.older');
    Route::post('/teams/{team}/chat', [ChatController::class, 'store'])->name('teams.chat.store');

    // notifications
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

require __DIR__ . '/auth.php';
