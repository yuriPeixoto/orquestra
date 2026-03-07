<?php

namespace App\Modules\Decisions\Interfaces\Http\Controllers;

use App\Modules\Decisions\Application\Actions\CreateDecision;
use App\Modules\Decisions\Application\Actions\UpdateDecision;
use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Decisions\Infrastructure\Decision;
use App\Modules\Decisions\Interfaces\Http\Requests\DecisionRequest;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DecisionController extends Controller
{
    public function index(Workspace $workspace): Response
    {
        $decisions = $workspace->decisions()
            ->with(['author', 'initiative'])
            ->latest()
            ->get();

        return Inertia::render('Decisions/Index', [
            'workspace' => $workspace,
            'decisions' => $decisions,
        ]);
    }

    public function create(Workspace $workspace): Response
    {
        $initiatives = $workspace->initiatives()->orderBy('title')->get(['id', 'title']);

        return Inertia::render('Decisions/Create', [
            'workspace' => $workspace,
            'statuses' => DecisionStatus::cases(),
            'initiatives' => $initiatives,
        ]);
    }

    public function store(DecisionRequest $request, Workspace $workspace, CreateDecision $action): RedirectResponse
    {
        abort_unless($request->user()->can('create_decision'), 403);

        $data = $request->validated();

        $initiative = isset($data['initiative_id'])
            ? Initiative::find($data['initiative_id'])
            : null;

        $decision = $action->execute(
            workspace: $workspace,
            author: $request->user(),
            title: $data['title'],
            context: $data['context'],
            decision: $data['decision'],
            consequences: $data['consequences'] ?? null,
            status: isset($data['status']) ? DecisionStatus::from($data['status']) : DecisionStatus::Proposed,
            initiative: $initiative,
        );

        return redirect()->route('decisions.show', [$workspace, $decision])
            ->with('success', 'Decisão criada com sucesso.');
    }

    public function show(Workspace $workspace, Decision $decision): Response
    {
        abort_unless($decision->workspace_id === $workspace->id, 404);

        return Inertia::render('Decisions/Show', [
            'workspace' => $workspace,
            'decision' => $decision->load(['author', 'initiative']),
        ]);
    }

    public function edit(Workspace $workspace, Decision $decision): Response
    {
        abort_unless($decision->workspace_id === $workspace->id, 404);
        abort_unless(request()->user()->can('edit_decision'), 403);

        $initiatives = $workspace->initiatives()->orderBy('title')->get(['id', 'title']);

        return Inertia::render('Decisions/Edit', [
            'workspace' => $workspace,
            'decision' => $decision->load('initiative'),
            'statuses' => DecisionStatus::cases(),
            'initiatives' => $initiatives,
        ]);
    }

    public function update(DecisionRequest $request, Workspace $workspace, Decision $decision, UpdateDecision $action): RedirectResponse
    {
        abort_unless($decision->workspace_id === $workspace->id, 404);
        abort_unless($request->user()->can('edit_decision'), 403);

        $data = $request->validated();

        $initiative = isset($data['initiative_id'])
            ? Initiative::find($data['initiative_id'])
            : null;

        $action->execute(
            decision: $decision,
            title: $data['title'],
            context: $data['context'],
            decision_text: $data['decision'],
            consequences: $data['consequences'] ?? null,
            status: isset($data['status']) ? DecisionStatus::from($data['status']) : null,
            initiative: $initiative,
            unlinkInitiative: ! isset($data['initiative_id']),
        );

        return redirect()->route('decisions.show', [$workspace, $decision])
            ->with('success', 'Decisão atualizada.');
    }
}
