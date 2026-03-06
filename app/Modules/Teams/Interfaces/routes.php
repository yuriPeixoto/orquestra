<?php

use App\Modules\Teams\Interfaces\Http\Controllers\TeamController;
use App\Modules\Teams\Interfaces\Http\Controllers\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'workspace.context'])->group(function (): void {
    Route::get('/workspaces/{workspace}/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/workspaces/{workspace}/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/workspaces/{workspace}/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

    Route::post('/workspaces/{workspace}/teams/{team}/members', [TeamMemberController::class, 'store'])->name('teams.members.store');
    Route::delete('/workspaces/{workspace}/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
});
