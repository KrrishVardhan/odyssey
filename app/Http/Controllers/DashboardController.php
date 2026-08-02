<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $tasks = Task::where('user_id', Auth::id())
            ->latest()
            ->get();
        $doneCount = $tasks->where('status', 'done')->count();
        $pendingCount = $tasks->where('status', 'todo')->count();

        return Inertia::render('dashboard', [
            'tasks' => $tasks,
            'doneCount' => $doneCount,
            'pendingCount' => $pendingCount,
        ]);
    }
}
