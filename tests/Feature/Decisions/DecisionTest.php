<?php

use App\Models\User;
use App\Modules\Decisions\Application\Actions\CreateDecision;
use App\Modules\Decisions\Application\Actions\UpdateDecision;
use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Decisions\Infrastructure\Decision;
use App\Modules\Initiatives\Application\Actions\CreateInitiative;
use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    setPermissionsTeamId(null);
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('decision can be created', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $decision = app(CreateDecision::class)->execute(
        workspace: $workspace,
        author: $owner,
        title: 'Use PostgreSQL',
        context: 'We need a relational database.',
        decision: 'We will use PostgreSQL.',
    );

    expect($decision)->toBeInstanceOf(Decision::class)
        ->and($decision->title)->toBe('Use PostgreSQL')
        ->and($decision->status)->toBe(DecisionStatus::Proposed)
        ->and($decision->workspace_id)->toBe($workspace->id);
});

test('decision can be linked to an initiative', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Launch Product');

    $decision = app(CreateDecision::class)->execute(
        workspace: $workspace,
        author: $owner,
        title: 'Adopt Modular Monolith',
        context: 'Architecture decision needed.',
        decision: 'We adopt a modular monolith.',
        initiative: $initiative,
    );

    expect($decision->initiative_id)->toBe($initiative->id);
});

test('decision is scoped to its workspace', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    app(CreateDecision::class)->execute($workspaceA, $ownerA, 'Decision A', 'ctx', 'dec');

    expect($workspaceA->decisions()->count())->toBe(1)
        ->and($workspaceB->decisions()->count())->toBe(0);
});

test('decision can be updated', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $decision = app(CreateDecision::class)->execute($workspace, $owner, 'Draft Decision', 'ctx', 'dec');

    $updated = app(UpdateDecision::class)->execute(
        decision: $decision,
        title: 'Final Decision',
        context: 'Updated context.',
        decision_text: 'Updated decision text.',
        status: DecisionStatus::Accepted,
    );

    expect($updated->title)->toBe('Final Decision')
        ->and($updated->status)->toBe(DecisionStatus::Accepted);
});

test('workspace member can create decision via POST', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/decisions", [
            'title' => 'Use Redis for cache',
            'context' => 'We need caching.',
            'decision' => 'We will use Redis.',
        ])
        ->assertRedirect();

    expect(Decision::where('workspace_id', $workspace->id)->where('title', 'Use Redis for cache')->exists())->toBeTrue();
});

test('workspace viewer cannot create decision', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $viewer = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $viewer->assignRole('workspace_viewer');

    $this->actingAs($viewer)
        ->post("/workspaces/{$workspace->id}/decisions", [
            'title' => 'Unauthorized',
            'context' => 'ctx',
            'decision' => 'dec',
        ])
        ->assertForbidden();
});

test('decision from another workspace returns 404', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    $decision = app(CreateDecision::class)->execute($workspaceA, $ownerA, 'Decision A', 'ctx', 'dec');

    $this->actingAs($ownerA)
        ->get("/workspaces/{$workspaceB->id}/decisions/{$decision->id}")
        ->assertForbidden();
});

test('decisions are visible in initiative show page', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'My Initiative');
    app(CreateDecision::class)->execute($workspace, $owner, 'Linked Decision', 'ctx', 'dec', initiative: $initiative);

    $this->actingAs($owner)
        ->get("/workspaces/{$workspace->id}/initiatives/{$initiative->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Initiatives/Show')
            ->has('decisions', 1)
        );
});
