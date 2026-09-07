<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\FollowController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Public\SubmissionController as PublicSubmissionController;
use App\Http\Controllers\SubmissionReviewController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamSettingsController;
use App\Http\Controllers\TeamViewController;
use App\Http\Controllers\WorkspaceTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/submissions', [PublicSubmissionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('products.submissions.store');

// google
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/username', [ProfileController::class, 'updateUsername'])->name('profile.username');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // submissions by customers/users
    Route::get('/teams/{team}/submissions', [SubmissionReviewController::class, 'index'])->name('teams.submissions');
    Route::patch('/submissions/{submission}/reject', [SubmissionReviewController::class, 'reject'])->name('submissions.reject');
    Route::post('/submissions/{submission}/tasks', [SubmissionReviewController::class, 'createTasks'])->name('submissions.tasks.store');

    // teams
    Route::get('/teams/{team}', [TeamViewController::class, 'mission'])->name('teams.show');
    Route::get('/teams/{team}/workspace', [TeamViewController::class, 'workspace'])->name('teams.workspace');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/tasks', [TaskController::class, 'store'])->name('teams.tasks.store');
    // team settings
    Route::get('/teams/{team}/settings', [TeamSettingsController::class, 'show'])->name('teams.settings');
    Route::patch('/teams/{team}/settings', [TeamSettingsController::class, 'update'])->name('teams.settings.update');
    Route::post('/teams/{team}/settings/icon', [TeamSettingsController::class, 'updateIcon'])->name('teams.settings.icon');
    Route::patch('/teams/{team}/members/{user}/role', [TeamSettingsController::class, 'updateRole'])->name('teams.members.role');
    Route::delete('/teams/{team}/members/{user}', [TeamSettingsController::class, 'kick'])->name('teams.members.kick');

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


    // follow
    Route::post('/products/{product}/follow', [FollowController::class, 'toggle'])->name('products.follow');
    Route::post('/teams/{team}/products', [ProductController::class, 'store'])->name('teams.products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});

require __DIR__ . '/auth.php';
