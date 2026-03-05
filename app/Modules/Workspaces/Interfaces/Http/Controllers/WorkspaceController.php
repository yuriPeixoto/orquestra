<?php

namespace App\Modules\Workspaces\Interfaces\Http\Controllers;

use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use App\Modules\Workspaces\Infrastructure\Workspace;
use App\Modules\Workspaces\Interfaces\Http\Requests\CreateWorkspaceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    public function store(CreateWorkspaceRequest $request, CreateWorkspace $action): RedirectResponse
    {
        $workspace = $action->execute(
            owner: $request->user(),
            name: $request->validated('name'),
        );

        return redirect()->route('workspaces.show', $workspace)
            ->with('success', 'Workspace criado com sucesso.');
    }

    public function show(Workspace $workspace): Response
    {
        return Inertia::render('Workspaces/Show', [
            'workspace' => $workspace,
        ]);
    }
}
