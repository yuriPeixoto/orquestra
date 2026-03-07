<?php

use App\Modules\Reporting\Interfaces\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';
require app_path('Modules/Workspaces/Interfaces/routes.php');
require app_path('Modules/Teams/Interfaces/routes.php');
require app_path('Modules/Initiatives/Interfaces/routes.php');
require app_path('Modules/Decisions/Interfaces/routes.php');
