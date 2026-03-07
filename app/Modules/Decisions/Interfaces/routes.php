<?php

use App\Modules\Decisions\Interfaces\Http\Controllers\DecisionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'workspace.context'])->group(function (): void {
    Route::get('/workspaces/{workspace}/decisions', [DecisionController::class, 'index'])->name('decisions.index');
    Route::get('/workspaces/{workspace}/decisions/create', [DecisionController::class, 'create'])->name('decisions.create');
    Route::post('/workspaces/{workspace}/decisions', [DecisionController::class, 'store'])->name('decisions.store');
    Route::get('/workspaces/{workspace}/decisions/{decision}', [DecisionController::class, 'show'])->name('decisions.show');
    Route::get('/workspaces/{workspace}/decisions/{decision}/edit', [DecisionController::class, 'edit'])->name('decisions.edit');
    Route::put('/workspaces/{workspace}/decisions/{decision}', [DecisionController::class, 'update'])->name('decisions.update');
});
