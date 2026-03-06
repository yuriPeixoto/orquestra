<?php

namespace App\Modules\Teams\Interfaces\Http\Controllers;

use App\Modules\Teams\Application\Actions\CreateTeam;
use App\Modules\Teams\Infrastructure\Team;
use App\Modules\Teams\Interfaces\Http\Requests\CreateTeamRequest;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function index(Workspace $workspace): Response
    {
        $teams = $workspace->teams()
            ->with('owner')
            ->withCount('members')
            ->get();

        return Inertia::render('Teams/Index', [
            'workspace' => $workspace,
            'teams' => $teams,
        ]);
    }

    public function store(CreateTeamRequest $request, Workspace $workspace, CreateTeam $action): RedirectResponse
    {
        $team = $action->execute(
            workspace: $workspace,
            owner: $request->user(),
            name: $request->validated('name'),
        );

        return redirect()->route('teams.show', [$workspace, $team])
            ->with('success', 'Time criado com sucesso.');
    }

    public function show(Workspace $workspace, Team $team): Response
    {
        abort_unless($team->workspace_id === $workspace->id, 404);

        return Inertia::render('Teams/Show', [
            'workspace' => $workspace,
            'team' => $team->load('members', 'owner'),
        ]);
    }
}
