<?php

namespace App\Modules\Initiatives\Interfaces\Http\Controllers;

use App\Modules\Initiatives\Application\Actions\CreateInitiative;
use App\Modules\Initiatives\Application\Actions\UpdateInitiative;
use App\Modules\Initiatives\Application\Actions\UpdateInitiativeStatus;
use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Initiatives\Interfaces\Http\Requests\InitiativeRequest;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InitiativeController extends Controller
{
    public function index(Workspace $workspace): Response
    {
        $initiatives = $workspace->initiatives()
            ->with('owner')
            ->orderBy('due_date')
            ->get();

        return Inertia::render('Initiatives/Index', [
            'workspace' => $workspace,
            'initiatives' => $initiatives,
        ]);
    }

    public function create(Workspace $workspace): Response
    {
        return Inertia::render('Initiatives/Create', [
            'workspace' => $workspace,
            'statuses' => InitiativeStatus::cases(),
        ]);
    }

    public function store(InitiativeRequest $request, Workspace $workspace, CreateInitiative $action): RedirectResponse
    {
        abort_unless($request->user()->can('create_initiative'), 403);

        $data = $request->validated();

        $initiative = $action->execute(
            workspace: $workspace,
            owner: $request->user(),
            title: $data['title'],
            description: $data['description'] ?? null,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            status: isset($data['status']) ? InitiativeStatus::from($data['status']) : InitiativeStatus::Draft,
        );

        return redirect()->route('initiatives.show', [$workspace, $initiative])
            ->with('success', 'Iniciativa criada com sucesso.');
    }

    public function show(Workspace $workspace, Initiative $initiative): Response
    {
        abort_unless($initiative->workspace_id === $workspace->id, 404);

        $decisions = $initiative->decisions()->with('author')->latest()->get();

        return Inertia::render('Initiatives/Show', [
            'workspace' => $workspace,
            'initiative' => $initiative->load('owner'),
            'decisions' => $decisions,
        ]);
    }

    public function edit(Workspace $workspace, Initiative $initiative): Response
    {
        abort_unless($initiative->workspace_id === $workspace->id, 404);
        abort_unless(request()->user()->can('edit_initiative'), 403);

        return Inertia::render('Initiatives/Edit', [
            'workspace' => $workspace,
            'initiative' => $initiative,
            'statuses' => InitiativeStatus::cases(),
        ]);
    }

    public function update(InitiativeRequest $request, Workspace $workspace, Initiative $initiative, UpdateInitiative $action): RedirectResponse
    {
        abort_unless($initiative->workspace_id === $workspace->id, 404);
        abort_unless($request->user()->can('edit_initiative'), 403);

        $data = $request->validated();

        $action->execute(
            initiative: $initiative,
            title: $data['title'],
            description: $data['description'] ?? null,
            dueDate: isset($data['due_date']) ? Carbon::parse($data['due_date']) : null,
            status: isset($data['status']) ? InitiativeStatus::from($data['status']) : null,
        );

        return redirect()->route('initiatives.show', [$workspace, $initiative])
            ->with('success', 'Iniciativa atualizada.');
    }

    public function kanban(Workspace $workspace): Response
    {
        $initiatives = $workspace->initiatives()
            ->with('owner')
            ->whereNotIn('status', [InitiativeStatus::Cancelled->value])
            ->orderBy('due_date')
            ->get();

        return Inertia::render('Initiatives/Kanban', [
            'workspace' => $workspace,
            'initiatives' => $initiatives,
        ]);
    }

    public function updateStatus(Request $request, Workspace $workspace, Initiative $initiative, UpdateInitiativeStatus $action): JsonResponse
    {
        abort_unless($initiative->workspace_id === $workspace->id, 404);
        abort_unless($request->user()->can('edit_initiative'), 403);

        $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_column(InitiativeStatus::cases(), 'value'))],
        ]);

        $updated = $action->execute($initiative, InitiativeStatus::from($request->string('status')->toString()));

        return response()->json(['status' => $updated->status->value]);
    }
}
