<?php

use App\Modules\Workspaces\Interfaces\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show'])->name('workspaces.show');
});
