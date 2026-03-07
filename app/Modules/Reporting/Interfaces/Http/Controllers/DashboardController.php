<?php

namespace App\Modules\Reporting\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = auth()->user();
        $workspace = $user->ownedWorkspaces()->first();

        if (! $workspace) {
            return Inertia::render('Dashboard', [
                'workspace' => null,
                'stats' => null,
            ]);
        }

        $initiativesByStatus = $workspace->initiatives()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentDecisions = $workspace->decisions()
            ->with('author:id,name')
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'status', 'created_at', 'author_id']);

        return Inertia::render('Dashboard', [
            'workspace' => $workspace->only('id', 'name'),
            'stats' => [
                'initiative_count' => $workspace->initiatives()->count(),
                'initiative_by_status' => $initiativesByStatus,
                'decision_count' => $workspace->decisions()->count(),
                'team_count' => $workspace->teams()->count(),
                'recent_decisions' => $recentDecisions,
            ],
        ]);
    }
}
