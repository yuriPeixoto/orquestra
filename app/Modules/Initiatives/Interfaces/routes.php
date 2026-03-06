<?php

use App\Modules\Initiatives\Interfaces\Http\Controllers\InitiativeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'workspace.context'])->group(function (): void {
    Route::get('/workspaces/{workspace}/initiatives', [InitiativeController::class, 'index'])->name('initiatives.index');
    Route::get('/workspaces/{workspace}/initiatives/create', [InitiativeController::class, 'create'])->name('initiatives.create');
    Route::post('/workspaces/{workspace}/initiatives', [InitiativeController::class, 'store'])->name('initiatives.store');
    Route::get('/workspaces/{workspace}/initiatives/{initiative}', [InitiativeController::class, 'show'])->name('initiatives.show');
    Route::get('/workspaces/{workspace}/initiatives/{initiative}/edit', [InitiativeController::class, 'edit'])->name('initiatives.edit');
    Route::put('/workspaces/{workspace}/initiatives/{initiative}', [InitiativeController::class, 'update'])->name('initiatives.update');
});
